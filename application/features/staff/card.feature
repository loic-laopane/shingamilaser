@card
@login_staff
Feature: Card
  A user authenticated as staff can Request card et list them


  Scenario: Access to the Card list page
    Given I am on "/"
    When I follow "menu.card.list"
    Then I should be on "/staff/cards"
#
#  Scenario: Access to the Cards Purchase page
#    Given I am on "/"
#    When I follow "menu.purchase.create"
#    Then I should be on "/staff/cards"

  #Create a cards purchase
