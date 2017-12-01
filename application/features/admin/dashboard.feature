@dashboard
@login_admin
Feature: Dashboard
  A user authenticated as admin can see the admin panel

  Scenario: Show the admin menu
    Given I am on the homepage
    Then I should see "menu.home"
    And I should see "menu.admin"
    And I should see "menu.logout"

Scenario: Access to the dashboard page
  Given I am on the homepage
  When I follow "menu.admin"
  Then I should be on the admin dashboard page
  And I should see "Dashboard"

