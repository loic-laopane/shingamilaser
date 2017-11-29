@account
Feature: Account

  Scenario: Show profile
    Given I am authenticated as "user"
    And I am on "/account/show"
    Then I should see "Profile"

  @login_as_user
  Scenario: Edit profile
    Given I am authenticated as "user" with password "user"
    And I am on "/account/profile/edit"
    Then I should see "Firstname"

    