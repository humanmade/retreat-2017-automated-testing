@db
Feature: Manage movie collection
  In order to show people a collection of favourite movies
  As a curator
  I need to be able to manage the movies in my collection

  Scenario: Finding a film in the movie search
    Given I am on the homepage
    When I search for a movie in the Movie Database
    Then the Add Movie form should be populated.

  Scenario: Trying to find an invalid film in the movie search
    Given I am on the homepage
    When I search for "this movie won't exist" in the Movie Database
    Then I should see an error message that says "Could not find movie."

  Scenario: Adding a movie to the collection
    Given I am on the homepage
    When I search for a movie in the Movie Database
      And I add a movie to the collection
    Then I should see a status message that says "Added movie to collection."
