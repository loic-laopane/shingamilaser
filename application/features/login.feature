@security
Feature: Login

  Scenario: Access to the login page
    Given I am on "/"
    When I follow "menu.login"
    Then I should be on "/login"
  
  Scenario: Connection success
    Given I am on "/login"
    When I fill in "_username" with "user"
    And I fill in "_password" with "user"
    And I press "btn.login"
    Then I should be on "/"

  Scenario: Connection failed
    Given I am on "/login"
    When I fill in "_username" with "bad user"
    And I fill in "_password" with "bad user"
    And I press "btn.login"
    Then I should see "Invalid credentials."
    