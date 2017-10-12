<?php

// class Movie_Rating_Factory extends WP_UnitTest_Factory_For_Term {
// 	private $taxonomy = 'rating';
// }

/**
 * Sample test case.
 */
class Movie_CPT_Test extends WP_UnitTestCase {

	/**
	 * @var WP_UnitTest_Factory_For_Movie_CPT
	 */
	public static $movie;

	public static $movie2;

	/**
	 * A collection of movies
	 *
	 * @var
	 */
	public $movie_db;

	/**
	 * @var]
	 */
	public $age_rating;

	/**
	 * Setup
	 */
	public function setUp() {
		parent::setUp();
	}

	public static function wpSetUpBeforeClass( $factory ) {
		$rating_factory = new WP_UnitTest_Factory_For_Term( $factory, 'rating' );

		$rating_18 = $rating_factory->create_object( [ 'name' => '18' ] );
		update_term_meta( $rating_18, 'age_relation', 18 );

		self::$movie = $factory->post->create([
			'title' => 'Movie 18',
			'post_type' => 'movie',
		]);

		wp_set_object_terms( self::$movie, $rating_18, 'rating' );

		self::$movie2 = $factory->post->create([
			'title' => 'Movie No Rating',
			'post_type' => 'movie',
		]);
	}

	/**
	 * @param $movie_id
	 * @param $age
	 * @param $expected_result
	 */
	public function test_is_suitable( ) {
		// Test 1 - test null guard condition
		$this->assertFalse( is_suitable_for( 0, 18 ) );

		// Test 2, test 18 rated movie.
		$this->assertTrue( is_suitable_for( self::$movie, 19 ) );
		$this->assertTrue( is_suitable_for( self::$movie, 18 ) );
		$this->assertFalse( is_suitable_for( self::$movie, 13 ) );

		// Test 3 - movie with no rating.
		$this->assertFalse( is_suitable_for( self::$movie2, 20 ) );
	}
}
