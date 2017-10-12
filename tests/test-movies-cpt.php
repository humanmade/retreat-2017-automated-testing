<?php


/**
 * Sample test case.
 */
class Movie_CPT_Test extends WP_UnitTestCase {

	/**
	 * @var WP_UnitTest_Factory_For_Movie_CPT
	 */
	public static $movie;

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


	/**
	 * @param $movie_id
	 * @param $age
	 * @param $expected_result
	 *
	 * @dataProvider paramTester
	 */
	public function test_is_suitable( $id, $age, $expected ) {
		$result = is_suitable_for( $id, $age );
		$this->assertEquals( $expected, $result );
	}

	public function paramTester() {
		$this->term = $this->factory->term->create( [ 'taxonomy' => 'rating' ] );
		update_term_meta( $this->term, 'age_relation', 18 );
		self::$movie = $this->factory->post->create();
		wp_set_object_terms(self::$movie, $this->term, 'rating' );
		return [
			[0, 10, null],
			[self::$movie, 9, false],
			[self::$movie, 18, true],
			[self::$movie, 20, true],
		];
	}
}
