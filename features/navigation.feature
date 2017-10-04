Feature: View movie collection
  In order to see the movies in a collection
  As a visitor
  I need to be able to browse through the collection

  Background:
    Given there are movies in the collection

  Scenario: Finding a movie in the site search
    Given I am on the movie collection page
    When I search for "Minions" in the toolbar
    Then I should see "Minions" in the search results

  Scenario: Finding a movie by genre
    Given I am on the movie collection page
    When I look for movies in the "Family" genre
    Then I should see "Minions"

  Scenario: Finding a movie by age classification
    Given I am on the movie collection page
    When I look for movies rated Universal
    Then I should see "Minions"

  Scenario: Viewing a specific movie in the collection
    Given I am on the movie collection page
    When I click on "Minions"
    Then I should be redirected to the movie "Minions"
      And I should see "Minions Stuart, Kevin and Bob are recruited by Scarlet Overkill, a super-villain who, alongside her inventor husband Herb, hatches a plot to take over the world."
