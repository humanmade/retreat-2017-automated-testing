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
	 */
	public function test_is_suitable( ) {
		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);
	}
}
