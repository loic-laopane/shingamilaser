@registration
Feature: Register
  An anonymous user can register on the application

#  Scenario: Access to the register page
#    Given I am on homepage
#    When I follow "menu.register"
#    Then I should be on the register page
#
#  Scenario: Register failed
#    Given I am on the register page
#    When I press "btn.register"
#    Then I should see "The field nickname is required"

  @remove_registered_customer
  Scenario: Register success
    Given I am on the register page
#    And print last response
    When I correctly fill all fields form
    And  I press "btn.register"
    Then I should be on the login page
#    And I should see "registration.success"

