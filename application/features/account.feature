@account
@login_user
Feature: Account
  A user authenticated as user can display it own profile


  Scenario: Show profile
    Given I am on "/"
    When I follow "menu.profile"
    Then I should be on "/account/show"
    And I should see "Profile"
    And I should see "btn.edit_profile"

  Scenario: Access to the Edit profile page from homepage
    Given I am on "/"
    When I follow "menu.profile_edit"
    Then I should be on "/account/profile/edit"
    And I should see "Edit profile"

  Scenario: Access to Edit profile page from the profile page
    Given I am on "/account/show"
    When I follow "btn.edit_profile"
    Then I should see "Edit Profile"

  Scenario: Access to Edit account page from homepage
    Given I am on "/"
    When I follow "menu.account_edit"
    Then I should be on "/account/edit"
    And I should see "Edit account"

  Scenario: Access to Edit account page from profile page
    Given I am on "/account/show"
    When I follow "Edit account"
    Then I should be on "/account/edit"
    And I should see "Edit account"

  Scenario: Display fidelity cards
    Given I am on "/account/show"
    When I follow "Fidelity cards"
    Then  I should see "Add card"

  Scenario: Display fidelity offers
    Given I am on "/account/show"
    When I follow "Offers"
    Then  I should see "No offer available"

  Scenario: Display games played
    Given I am on "/account/show"
    When I follow "Games"
    Then  I should see "No games played"

  Scenario: Update profile with form profile
    Given I am on "/account/profile/edit"
    When I press "btn.save"
    Then  I should see "Profile updated"

  Scenario: Failed update profile with form profile
    Given I am on "/account/profile/edit"
    When I fill in "nickname" with ""
    And I press "btn.save"
    Then  I should see "Nickname is required"

  Scenario: Update Email with form account
    Given I am on "/account/edit"
    When I press "btn.save"
    Then  I should see "Account updated"

  Scenario: Failed Update Email with form account
    Given I am on "/account/edit"
    When I fill in "email" with ""
    And I press "btn.save"
    Then  I should see "Email invalid"
