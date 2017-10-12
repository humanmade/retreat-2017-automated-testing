<?php

use MoviePlugin;


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

		do_action( 'init' );
	}

	public function provider_default_terms() {
		return [
			[
				'Universal',
				'0',
				false,
			],
			[
				'12A',
				'11',
				false,
			],
			[
				'12',
				'12',
				false,
			],
			[
				'15',
				'15',
				false,
			],
			[
				'PG',
				'8',
				false
			],
			[
				'18',
				'18',
				false
			],
			[
				'1002',
				false,
				true,
			]
		];
	}

	public function provider_is_suitable_for() {

		$this->movie = new WP_UnitTest_Factory_For_Movie_CPT();

		$this->movie_db['15'] = $this->movie->create( [
			'post_title' => 'Movie 1',
			'post_type'  => 'movie',
			'age_rating' => '12',
		] );

		$this->movie_db['Universal'] = $this->movie->create( [
			'post_title' => 'Movie 2',
			'post_type'  => 'movie',
			'age_rating' => 'Universal',
		] );
		$this->movie_db['18'] = $this->movie->create( [
			'post_title' => 'Movie 3',
			'post_type'  => 'movie',
			'age_rating' => '18',
		] );
		$this->movie_db['12A'] = $this->movie->create( [
			'post_title' => 'Movie 4',
			'post_type'  => 'movie',
			'age_rating' => '12A',
		] );

		return [
			[
				1000,
				1000,
				false
			],
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
		];
	}

	/**
	 * [test_create_term_defaults description]
	 * @param  [type] $term_slug [description]
	 * @param  [type] $expected  [description]
	 *
	 * @dataProvider provider_default_terms
	 * @covers MoviePlugin\create_term_defaults
	 */
	public function test_create_term_defaults( $term_slug, $age_relation, $expected ) {
		$term_id = term_exists( $term_slug );

		$this->assertNotSame( $expected, $term_id );
		$this->assertSame( $age_relation, get_term_meta( $term_id, 'age_relation', true ) );
	}

	/**
	 *
	 * @covers MoviePlugin\register_movie_post_type
	 */
	public function test_register_movie_post_type() {
		$this->assertTrue( post_type_exists( 'movie' ) );
	}

	/**
	 * @covers MoviePlugin\register_movie_taxonomies
	 */
	public function test_register_movie_rating_taxonomy() {
		$this->assertTrue( taxonomy_exists( 'rating' ) );
	}

	/**
	 * @covers MoviePlugin\register_movie_taxonomies
	 */
	public function test_register_movie_genre_taxonomy() {
		$this->assertTrue( taxonomy_exists( 'genre' ) );
	}

	/**
	 * @covers MoviePlugin\register_movie_form_shortcode
	 */
	public function test_register_movie_form_shortcode() {
		$this->assertTrue( shortcode_exists( 'add_movie_form' ) );
	}

	/**
	 * @param $movie_id
	 * @param $age
	 * @param $expected_result
	 *
	 * @dataProvider provider_is_suitable_for
	 * @covers MoviePlugin\is_suitable_for
	 */
	public function test_is_suitable( $movie_id, $age, $expected_result ) {
		$this->assertSame( $expected_result, MoviePlugin\is_suitable_for( $movie_id, $age ) );
	}
}
