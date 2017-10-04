<?php
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;

use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;

/**
 * Define application features from the specific context.
 */
class FeatureContext extends RawWordpressContext implements SnippetAcceptingContext {

    /**
     * Initialise context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the context constructor through behat.yml.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * @When I search for a movie in the Movie Database
     */
    public function iSearchForAMovieInTheMovieDatabase()
    {
        throw new PendingException();
    }

    /**
     * @Then the Add Movie form should be populated.
     */
    public function theAddMovieFormShouldBePopulated()
    {
        throw new PendingException();
    }

    /**
     * @When I search for :arg1 in the Movie Database
     */
    public function iSearchForInTheMovieDatabase($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I add a movie to the collection
     */
    public function iAddAMovieToTheCollection()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see a message that says :arg1
     */
    public function iShouldSeeAMessageThatSays($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given there are movies in the collection
     */
    public function thereAreMoviesInTheCollection()
    {
        throw new PendingException();
    }

    /**
     * @Given I am on the movie collection page
     */
    public function iAmOnTheMovieCollectionPage()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see :arg1 in the search results
     */
    public function iShouldSeeInTheSearchResults($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I look for movies in the :arg1 genre
     */
    public function iLookForMoviesInTheGenre($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I look for movies rated Universal
     */
    public function iLookForMoviesRatedUniversal()
    {
        throw new PendingException();
    }

    /**
     * @When I click on :arg1
     */
    public function iClickOn($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be redirected to the movie :arg1
     */
    public function iShouldBeRedirectedToTheMovie($arg1)
    {
        throw new PendingException();
    }
}
