@card
@login_staff
Feature: Card
  A user authenticated as staff can Request card et list them


#  Scenario: Access to the Card list page
#    Given I am on the homepage
#    When I follow "menu.card.list"
#    Then I should be on "/staff/cards"

#  Scenario: Access to the Cards Purchase page
#    Given I am on the homepage
#    When I follow "menu.purchase.create"
#    Then I should be on the cards purchase page
#    And I should see "btn.create"

#  Scenario: Access to the Purchases list page
#    Given I am on the homepage
#    When I follow "menu.purchase.list"
#    Then I should be on the cards purchase list
#    And I should see "btn.new"

#  Scenario: Create a failed card purchase
#    Given I am on the cards purchase page
#    When I fill in "quantity" with ""
#    And I press "btn.create"
#    Then I should see "Quantity cannot be null"
#    And I should be on the cards purchase page

#  @delete_last_purchase
#  Scenario: Create a cards purchase
#    Given I am on the cards purchase page
#    When I fill in "quantity" with "2"
#    And I press "btn.create"
#    Then I should see "Manage purchase"
#    And I should see "btn.send.request"

#  @fake_purchase
#  Scenario: Edit a cards purchase
#    Given I am on the edit cards purchase page
#    When I fill in "quantity" with "2"
#    And I press "btn.edit"
#    Then I should see "Purchase updated"

#  @fake_purchase
#  Scenario: Send a failed request of cards purchase
#    Given I am on the edit cards purchase page
#    When I fill in "quantity" with "0"
#    And I press "btn.send.request"
#    Then I should see "No center associated on current user"

  @remove_purchase_list
  Scenario: List cards purchases
    Given Purchases exist
    And I am on the cards purchase list
    Then I should see "btn.edit"
    And I should see "btn.show"
    And I should see "Status"


  Scenario: List cards purchases
    Given I am on the cards purchase list
    Then I should see "No purchase"



