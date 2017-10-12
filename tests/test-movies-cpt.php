<?php


/**
 * Sample test case.
 */
class Movie_CPT_Test extends WP_UnitTestCase {

	/**
	 * @var WP_UnitTest_Factory_For_Movie_CPT
	 */
	public $movie;

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
	 * @dataProvider dataSuitable
	 */
	public function test_is_suitable( $movie_id, $age, $expected_result ) {
		$this->assertSame( $expected_result, is_suitable_for( $movie_id, $age ) );
	}

	public static function dataSuitable() {
		$factory = new WP_UnitTest_Factory_For_Movie_CPT;
		$movie = $factory->create( [ 'age_rating' => '18', 'post_type' => 'movie' ] );
		return [
			[
				0, 21, false
			],
			[
				$movie, 21, true
			],
			[
				$movie, 15, false
			],
			[
				$movie, 18, true
			],
		];
	}
}
