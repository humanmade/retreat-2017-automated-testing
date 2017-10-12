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

	public function wpSetupBeforeClass( $factory ){
		$movie_db = $factory->post->create_many(3); // returns a array of IDs.

		foreach ($movie_db as $key => $movie_id){
			// Attach any include rating, arrays of slug accepted

			wp_set_object_terms( $movie_id, 15, 'rating' );
		}
	}


	public function setUp() {
		parent::setUp();


	}

	/**
	 * @param $movie_id
	 * @param $age
	 * @param $expected_result
	 *
	 * @dataProvider is_suitable_provider
	 */
	public function test_is_suitable( ) {

		// if movie does not exist - fail

		// if issset $movie_id - pass


		// check if wp_get_object_terms() has gotten anything


		//

	}

	public function is_suitable_provider( ) {

		return array(
			array( $this->movie_db[0], 16 ),
			array( $this->movie_db[1], 120 ),
			array( $this->movie_db[2], 18 ),
			array( $this->movie_db[3], 29 ),

		);
	}
}
