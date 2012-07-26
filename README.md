# Demo of multiple Mink sessions using Behat

Please refer to the supporting [blog post](http://swdesigns.co.uk/2012/07/multiple-sessions-with-behat-mink-and-selenium2/)

## Usage

Clone this repo:

``` bash
git clone https://github.com/swestcott/MinkMultipleSessions
```

Install the dependencies with [composer](http://getcomposer.org/):

``` bash
curl http://getcomposer.org/installer | php
php composer.phar install
```

Download, extract to the same directory and run [Selenium2](http://seleniumhq.org/download/) with the [ChromeDriver](http://code.google.com/p/chromedriver/downloads/list)

``` bash
java -jar selenium-server-standalone-2.*.jar -Dwebdriver.chrome.driver=chromedriver
```

Now to launch Behat, just run:

``` bash
bin/behat
```

You should see something like:

``` gherkin
$ bin/behat 
Feature: User can communicate with each other

  Scenario: Simple 1-way chat            # features/ConcurrentUsers.feature:3
    Given "User1" enters the chat room   # FeatureContext::entersTheChatRoom()
    And "User2" enters the chat room     # FeatureContext::entersTheChatRoom()
    When "User1" posts a message         # FeatureContext::postsAMessage()
    Then "User2" should see that message # FeatureContext::shouldSeeThatMessage()

1 scenario (1 passed)
4 steps (4 passed)
```