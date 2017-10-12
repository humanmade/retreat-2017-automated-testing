<?php
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;

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
				throw new \Exception( 'Movie name, description, or rating, are blank and should not be.' );
			}

			$genre_is_set = false;

			foreach ( $moviegenres as $moviegenre ) {
				if ( $moviegenre->isChecked() ) {
					$genre_is_set = true;
					break;
				}
			}

			if ( empty( $moviegenres ) ) {
				throw new \Exception( 'Movie genres are blank. At least one should be set' );
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
	 * @Given there are movies in the collection:
	 *
	 * @param TableNode $movies
	 */
	public function thereAreMoviesInTheCollection( TableNode $movies )
	{
		foreach ( $movies->getHash() as $movie ) {
			$this->createContent( [
				'post_content' => $movie['description'],
				'post_status'  => 'publish',
				'post_title'   => $movie['name'],
				'post_type'    => 'movie',
				'tax_input'    => [
					'rating' => $movie['rating'],
					'genre'  => $movie['genre'],
				],
			] );
		}
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
		$screen  = $this->getSession()->getPage();
		$results = $screen->findAll( 'css', '.search-results #main article' );

		foreach ( $results as $result ) {
			$link = $result->find( 'named', [ 'link', $arg1 ] );
			if ( $link !== null && $link->getText() === $arg1 ) {
				return;
			}
		}

		throw new \Exception(
			sprintf( '"Then I should see xyz in the search results" step failed to find "%1$s".', $arg1 )
		);
	}

	/**
	 * @When I look for movies in the :arg1 genre
	 */
	public function iLookForMoviesInTheGenre($arg1)
	{
		$screen = $this->getSession()->getPage();
		$genres = $screen->findAll( 'css', 'li.genre li a' );

		// Find the term link whose text is the same as $arg1.
		foreach ( $genres as $genre ) {
			if ( $genre->getText() === $arg1 ) {

				$genre->click();
				return;
			}
		}

		throw new \Exception(
			sprintf( '"When I look for movies in the xyz genre" step failed to find genre "%1$s".', $arg1 )
		);
	}

	/**
	 * @When I look for movies rated Universal
	 */
	public function iLookForMoviesRatedUniversal()
	{
		$screen  = $this->getSession()->getPage();
		$ratings = $screen->findAll( 'css', 'li.rating li a' );

		// Find the term link whose text is "Universal".
		foreach ( $ratings as $rating ) {
			if ( $rating->getText() === 'Universal' ) {

				$rating->click();
				return;
			}
		}

		throw new \Exception(
			sprintf( '"When I look for movies rated Universal" step failed to find rating "%1$s".', 'Universal' )
		);
	}

	/**
	 * @When I click on :arg1
	 */
	public function iClickOn( $link_name ) {
		$link = $this->getSession()->getPage()->findLink( $link_name );

		if ( $link === null ) {
			throw new \Exception(
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

		$screen = $this->getSession()->getPage();
		$title = $screen->find( 'css', '.entry-header' );

		if ( $title === null || $title->getText() !== $movie_name ) {
			throw new \Exception( 'Then I should be redirect to the movie xyz" step failed.' );
		}
	}
}
