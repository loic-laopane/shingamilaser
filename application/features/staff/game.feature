@game
@login_staff
Feature: Games
  A user authenticated as staff can manage games


  Scenario: Show Staff Main Menu
    Given I am on "/"
    Then I should see "menu.card"
    And I should see "menu.game"


  Scenario: Access to Game list page from main menu
    Given I am on "/"
    When I follow "menu.game.list"
    Then I should be on "/staff/games"
    And I should see "Game list"
    And I should see "btn.new"

  Scenario: Access to Game create page from main menu
    Given I am on "/"
    When I follow "menu.game.create"
    Then I should be on "/staff/game/create"
    And I should see "Create new game"
    And I should see "btn.create.game"

  Scenario: Access to Game create page from Game list page
    Given I am on "/staff/games"
    When I follow "btn.new"
    Then I should be on "/staff/game/create"
    And I should see "Create new game"
    And I should see "btn.create.game"

  @create_fake_game
  Scenario: Create a new game
    A staff user can create a new game
    Given I am on "/staff/game/create"
    When I fill in "title" with "NewFakeGame"
    And I press "btn.create.game"
    Then I should see "Game created"
    And I should see "Edit game"
    And I should see "btn.edit.game"
    And I should see "btn.manage"

  @create_fake_game
  Scenario: Edit an existing game
    A staff user can edit a game
    Given I am on the edit game page
    When  I press "btn.edit.game"
    Then I should see "Game updated"

  @create_fake_game
  Scenario: Failed editing an existing game
  A staff user can fail to edit a game if no title exists
    Given I am on the edit game page
    When I fill in "title" with ""
    And I press "btn.edit.game"
    Then I should see "Title is required"

  @create_fake_game
  Scenario: Access on the Game manage page
    Given I am on the edit game page
    When I follow "btn.manage"
    Then I should be on the manage page
    And I should see "Search customer"

  @create_fake_game
  Scenario: Acces on the Game manage page from the Game list page
    Given I am on "/staff/games"
    When I follow "btn.manage"
    Then I should be on the manage page
    And I should see "Search customer"

  @create_fake_game
  Scenario: Acces on a Close Game manage page
    Given I am on the edit game page
    And The game is over
    When I follow "btn.manage"
    Then I should be on the manage page
    And I should not see "Search customer"