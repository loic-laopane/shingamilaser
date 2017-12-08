@user
@login_admin
Feature: User
  A user authenticated as admin can manage users
  In those scenarios, a user is already authenticated as admin

  Scenario: Access to the User list page
    Given I am on the dashboard page
    Then I follow "menu.user.main"
    Then I should be on the users list page

  @fake_user
  Scenario: Access to the user edit page
    Given I am on the users list page
    When I follow "btn.edit"
    Then I should be on the user edit page
    And I should see "title.user.edit"
