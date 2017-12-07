@account
@login_user
Feature: Account
  A user authenticated as user can display it own profile


  Scenario: Show profile
    Given I am on "/"
    When I follow "menu.profile"
    Then I should be on "/account/show"
    And I should see "title.profile.main"
    And I should see "btn.profile.edit"

  Scenario: Access to the Edit profile page from homepage
    Given I am on "/"
    When I follow "menu.profile.edit"
    Then I should be on "/account/profile/edit"
    And I should see "title.profile.edit"

  Scenario: Access to Edit profile page from the profile page
    Given I am on "/account/show"
    When I follow "btn.profile.edit"
    Then I should see "title.profile.edit"

  Scenario: Access to Edit account page from homepage
    Given I am on "/"
    When I follow "menu.account.edit"
    Then I should be on "/account/edit"
    And I should see "title.account.edit"

  Scenario: Access to Edit account page from profile page
    Given I am on "/account/show"
    When I follow "menu.account.edit"
    Then I should be on "/account/edit"
    And I should see "title.account.edit"

  Scenario: Display fidelity cards
    Given I am on "/account/show"
    When I follow "fidelity cards"
    Then  I should see "btn.card.add"

  Scenario: Display offers
    Given I am on "/account/show"
    When I follow "offers"
    Then  I should see "msg.no_offer_available"

  Scenario: Display games played
    Given I am on "/account/show"
    When I follow "games"
    Then  I should see "msg.no_game_played"

  Scenario: Update profile with form profile
    Given I am on "/account/profile/edit"
    When I press "btn.save"
    Then  I should see "Profile updated"

  Scenario: Failed update profile with form profile
    Given I am on "/account/profile/edit"
    When I fill in "nickname" with ""
    And I press "btn.save"
    Then  I should see "alert.customer.nickname.required"

  Scenario: Update Email with form account
    Given I am on "/account/edit"
    When I press "btn.save"
    Then  I should see "Account updated"

  Scenario: Failed Update Email with form account
    Given I am on "/account/edit"
    When I fill in "email" with ""
    And I press "btn.save"
    Then  I should see "alert.email_invalid"
