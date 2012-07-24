Feature: User can communicate with each other

  Scenario: Simple 1-way chat
  	Given "User1" enters the chat room
  	And "User2" enters the chat room
  	When "User1" posts a message
  	Then "User2" should see that message