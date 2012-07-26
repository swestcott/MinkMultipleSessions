<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    const MAX_WAIT = 10000;
    private $params = array();
    private $defaultDriver;

    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array   $parameters     context parameters (set them up through behat.yml)
     */ 
    public function __construct(array $parameters)
    {
        $this->params = $parameters;
    }

    /**
     * @Given /^"([^"]*)" enters the chat room$/
     */
    public function entersTheChatRoom($user)
    {
        if(is_null($this->defaultDriver)) {
            $this->defaultDriver = $this->getSession()->getDriver();
        }

        $newDriver = clone $this->defaultDriver;
        $newDriver->setWebDriver(new \WebDriver\WebDriver(
            $this->params['wd_host']
        ));
        $this->getMink()->registerSession($user, new \Behat\Mink\Session($newDriver));
        $this->getSession($user)->restart();

        $this->getSession($user)->visit($this->locatePath(
            "WebSockets/ChatDemo/wsdemo.html"
        ));
        $this->getSession($user)->wait(self::MAX_WAIT,
            "document.getElementById('status').innerHTML == 'Socket open'"
        );

        $this->assertSession($user)->pageTextContains("Socket open");
    }

    /**
     * @When /^"([^"]*)" posts a message$/
     */
    public function postsAMessage($user)
    {
        // Ensure message is unique
        $this->message = 'Selenium Test ' . microtime(true);

        $this->getSession($user)->getPage()->fillField("chat", $this->message);
        $this->getSession($user)->executeScript(
            "$('form').submit();"
        );

        $this->assertSession($user)->pageTextContains($this->message);
    }

    /**
     * @Then /^"([^"]*)" should see that message$/
     */
    public function shouldSeeThatMessage($user)
    {
        $this->getSession($user)->wait(self::MAX_WAIT,
            "$('.them:contains(\"" . $this->message . "\")').size() == 1"
        );

        $this->assertSession($user)->pageTextContains($this->message);
    }
}
