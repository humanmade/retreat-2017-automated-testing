<?php
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Exception;

/**
 * Define application features from the specific context.
 */
class FeatureContext extends RawWordpressContext implements SnippetAcceptingContext {

	/**
	 * Initialise context.
	 *
	 * Every scenario gets its own context instance.
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * @When I search for a movie in the Movie Database
	 */
	public function iSearchForAMovieInTheMovieDatabase() {
		$screen = $this->getSession()->getPage();

		$field = $screen->findField( 'moviesearch' );
		$field->setValue( 'Minions' );

		$screen->findButton( 'Start search' )->click();
	}

	/**
	 * @Then the Add Movie form should be populated.
	 */
	public function theAddMovieFormShouldBePopulated()
	{
		$context = $this;

		$logic = function() use ( $context ) {
			$screen           = $context->getSession()->getPage();
			$moviename        = $screen->findField( 'moviename' );
			$moviedescription = $screen->findField( 'moviedescription' );
			$movierating      = $screen->findField( 'movierating' );
			$moviegenres      = $screen->findAll( 'css', '.fieldset-genres input[type="checkbox"]' );

			if ( ! $moviename->getValue() || ! $moviedescription->getValue() || ! $movierating->getValue() ) {
				throw new Exception( 'Movie name, description, or rating, are blank and should not be.' );
			}

			$genre_is_set = false;

			foreach ( $moviegenres as $moviegenre ) {
				if ( $moviegenre->isChecked() ) {
					$genre_is_set = true;
					break;
				}
			}

			if ( empty( $moviegenres ) ) {
				throw new Exception( 'Movie genres are blank. At least one should be set' );
			}
		};

		// Spins() retries the callback method mutiple times - in case the JS/DOM hasn't been fully loaded yet.
		$this->spins( $logic );
	}

	/**
	 * @When I search for :arg1 in the Movie Database
	 */
	public function iSearchForInTheMovieDatabase( $movie ) {
		$screen = $this->getSession()->getPage();

		$field = $screen->findField( 'moviesearch' );
		$field->setValue( $movie );

		$screen->findButton( 'Start search' )->click();
	}

	/**
	 * @When I add a movie to the collection
	 */
	public function iAddAMovieToTheCollection()
	{
		$screen = $this->getSession()->getPage();
		$screen->findButton( 'Add movie to collection' )->click();
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
	public function iAmOnTheMovieCollectionPage() {
		$this->visitPath( '/movies/' );
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
	public function iClickOn( $link_name ) {
		$link = $this->getSession()->getPage()->findLink( $link_name );

		if ( $link === null ) {
			throw new Exception(
				sprintf( '"When I click on" step failed to find link "%1$s".', $link_name )
			);
		}

		$link->click();
	}

	/**
	 * @Then I should be redirected to the movie :arg1
	 */
	public function iShouldBeRedirectedToTheMovie( $movie_name ) {
		$movie = $this->getDriver()->content->get( $movie_name, [ 'by' => 'title', 'post_type' => 'movie' ] );
		$this->visitPath( '/movies/' . $movie->post_name );
	}
}
