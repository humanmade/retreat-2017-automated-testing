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
	 *
	 * @dataProvider provider_is_suitable
	 */
	public function test_is_suitable( $movie_id, $age, $expected_result ) {
		$actual = is_suitable_for( $movie_id, $age );
		$this->assertSame( $actual, $expected_result );
	}

	public function provider_is_suitable() {

		$r_term_id = $this->factory->term->create([
			'taxonomy' => 'rating',
			'name'     => 'R',
		]);
		add_term_meta( $r_term_id, 'age_relation', 18 );


		$this->movie_id = $this->factory->post->create([
			'post_type' => 'movie',
			'post_title' => 'Revenge of the nerds.',
		]);

		wp_set_object_terms( $this->movie_id, $r_term_id, 'rating' );


		/*
		 * 1. Movie ID
		 * 2. Age
		 * 3. Exepected Result
		 */
		return [
			[
				$this->movie_id,
				18,
				true,
			],
			[
				$this->movie_id,
				17,
				false,
			],
			[
				$this->movie_id + 1,
				17,
				false,
			],
		];
	}
}
