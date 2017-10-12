@db
Feature: View movie collection
  In order to see the movies in a collection
  As a visitor
  I need to be able to browse through the collection

  Background:
    Given there are movies in the collection:
      | name                    | rating    | genre  | description                                                |
      | Minions                 | Universal | Family | Stuart, Kevin and Bob are recruited by Scarlet Overkill... |

  Scenario: Finding a movie in the site search
    Given I am on the movie collection page
    When I search for "Minions" in the toolbar
    Then I should see "Minions" in the search results

  Scenario: Finding a movie by age classification
    Given I am on the movie collection page
    When I look for movies rated Universal
    Then I should see "Minions"
