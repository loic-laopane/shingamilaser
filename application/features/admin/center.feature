@center
@login_admin
Feature: Center
  A user authenticated as admin can manage centers
  In those scenarios, a user is already authenticated as admin

  Scenario: Access to the Center list page
    Given I am on the dashboard page
    Then I follow "menu.center.main"
    Then I should be on the centers list page

  Scenario: Access to the center create page
    Given I am on the centers list page
    When I follow "btn.create"
    Then I should be on the center create page
    And I should see "title.center.new"

  @fake_center
  Scenario: Access to the center edit page
    Given I am on the centers list page
    When I follow "btn.edit"
    Then I should be on the center edit page
    And I should see "title.center.edit"