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

	public function providersuitable() {

		$this->movie = new WP_UnitTest_Factory_For_Movie_CPT();

		$this->age_ratings = [
			[
				'slug' => 'Universal',
				'meta' => [
					'age_relation' => 0,
				],
			],
			[
				'slug' => '12A',
				'meta' => [
					'age_relation' => 11,
				],
			],
			[
				'slug' => '12',
				'meta' => [
					'age_relation' => 12,
				],
			],
			[
				'slug' => '15',
				'meta' => [
					'age_relation' => 15,
				],
			],
			[
				'slug' => 'PG',
				'meta' => [
					'age_relation' => 8,
				],
			],
			[
				'slug' => '18',
				'meta' => [
					'age_relation' => 18,
				],
			],
		];
		foreach ( $this->age_ratings as $age_rating ) {
			$term_id = wp_insert_term( $age_rating['slug'], 'rating' );
			if ( is_wp_error( $term_id ) ) {
				continue;
			}
			update_term_meta( $term_id['term_id'], 'age_relation', $age_rating['meta']['age_relation'] );
		}

		$this->movie_db['15'] = $this->movie->create( [
			'post_title' => 'Movie 1',
			'post_type'  => 'movie',
			'age_rating' => $this->age_ratings[3]['slug'],
		] );

		$this->movie_db['Universal'] = $this->movie->create( [
			'post_title' => 'Movie 2',
			'post_type'  => 'movie',
			'age_rating' => $this->age_ratings[0]['slug'],
		] );
		$this->movie_db['18'] = $this->movie->create( [
			'post_title' => 'Movie 3',
			'post_type'  => 'movie',
			'age_rating' => $this->age_ratings[5]['slug'],
		] );
		$this->movie_db['12A'] = $this->movie->create( [
			'post_title' => 'Movie 4',
			'post_type'  => 'movie',
			'age_rating' => $this->age_ratings[1]['slug'],
		] );

		return [
			[
				$this->movie_db['15'],
				18,
				true,
			],
			[
				$this->movie_db['Universal'],
				12,
				true,
			],
			[
				$this->movie_db['18'],
				17,
				false,
			],
			[
				$this->movie_db['Universal'],
				12,
				true,
			],
			[
				$this->movie_db['12A'],
				6,
				false,
			],
			[
				999,
				6,
				false,
			],
		];
	}

	/**
	 * @param $movie_id
	 * @param $age
	 * @param $expected_result
	 *
	 * @dataProvider providersuitable
	 */
	public function test_is_suitable( $movie_id, $age, $expected_result ) {
		$this->assertSame( $expected_result, is_suitable_for( $movie_id, $age ) );
	}
}
