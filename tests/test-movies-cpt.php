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
	 * MovieHelper constructor.
	 */
	public function __construct() {
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
	}

	public function setUp() {
		parent::setUp();

		foreach ( $this->age_ratings as $age_rating ) {
			$term_id = wp_insert_term( $age_rating['slug'], 'rating' );
			if ( is_wp_error( $term_id ) ) {
				continue;
			}
			update_term_meta( $term_id['term_id'], 'age_relation', $age_rating['meta']['age_relation'] );
		}

	}

	/**
	 * @return array
	 */
	public static function provider_is_suitable() {
		$movie = new Movie_CPT_Test();

		// This will setup the db with an existing move DB
		$movie->movie_db['15'] = $movie->movie->create( [
			'post_title' => 'Movie 1',
			'post_type'  => 'movie',
			'age_rating' => $movie->age_ratings[3]['slug']
		] );
		$movie->movie_db['Universal'] = $movie->movie->create( [
			'post_title' => 'Movie 2',
			'post_type'  => 'movie',
			'age_rating' => $movie->age_ratings[0]['slug']
		] );
		$movie->movie_db['18'] = $movie->movie->create( [
			'post_title' => 'Movie 3',
			'post_type'  => 'movie',
			'age_rating' => $movie->age_ratings[5]['slug']
		] );
		$movie->movie_db['12A'] = $movie->movie->create( [
			'post_title' => 'Movie 4',
			'post_type'  => 'movie',
			'age_rating' => $movie->age_ratings[1]['slug']
		] );


		$test = [
			[
				$movie->movie_db['15'],
				18,
				1,
			],
			[
				$movie->movie_db['Universal'],
				12,
				1,
			],
			[
				$movie->movie_db['18'],
				17,
				0,
			],
			[
				$movie->movie_db['Universal'],
				12,
				1,
			],
			[
				$movie->movie_db['12A'],
				10,
				0,
			],
		];

		var_dump( $test );

		ob_flush();

		return $test;
	}

	/**
	 * @dataProvider provider_is_suitable
	 * @covers ::is_suitable_for
	 */
	public function test_is_suitable( $movie_id = 0 ) {

		var_dump( $movie_id );

		ob_flush();




//		$this->assertEquals( $expected_result, is_suitable_for( $movie_id, $age ) );

	}

}