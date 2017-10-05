<?php
/**
 * Plugin Name: Movie Plugin
 * Description: 🔆 🎥 🤹‍♂️
 * Author:      Happy Made
 * Version:     1
 * License:     wtfpl
 */

// Set up content types.
add_action( 'init', function() {
	register_post_type( 'movie', [
		'has_archive'   => true,
		'label'         => 'Movies',
		'public'        => true,
		'show_in_rest'  => true,
		'rewrite'       => [
			'slug' => 'movies',
		],
		'supports'      => [
			'editor',
			'title',
		],

		// Below dashboard.
		'menu_position' => 2,
	] );

	register_taxonomy( 'rating', 'movie', [
		'label'         => 'Movie Rating',
		'public'        => true,
		'show_in_rest'  => true,
	] );

	register_taxonomy( 'genre', 'movie', [
		'label'         => 'Movie Genre',
		'public'        => true,
		'show_in_rest'  => true,
	] );
} );

// "Add movie" shortcode.
add_shortcode( 'add_movie_form', function() {
	wp_enqueue_script( 'movieplugin-js', plugins_url( 'script.js', __FILE__ ), [], time() );
	?>

	<form method="get" action="/" class="movie-form movie-search-form">
		<p>Search for a movie to add to the database:</p>

		<label for="moviesearch">Movie name:</label>
		<input type="search" name="moviesearch" class="moviesearch" placeholder="e.g. 'Beauty and the Beast'" required>

		<input type="submit" value="Start search">
	</form>

	<form method="post" action="/" class="movie-form movie-entry-form initially-hidden">
	</form>
<?php
} );

/**
 * Conditional Check for age suitable for movies
 *
 * @param int $age
 * @param int $movie_id
 * @test data Providers
 *
 * @return bool
 */
function is_suitable_for( int $age, int $movie_id = 0 ) : bool {
	$movie = get_post( $movie_id );

	if ( false === $movie ) {
		return false;
	}

	$movie_id       = $movie->ID;
	$certifications = get_terms( 'rating',
		array(
			'hide_empty' => false,
			'meta_query' => array(
				'meta_query' => array(
					'meta_key'   => 'age_relation',
					'meta_value' => $age,
					'compare'    => '<',
				)
			)
		)
	);

	$certifications = wp_list_pluck( $certifications, 'term_id' );

	if ( has_term( $certifications, 'rating', $movie_id ) ) {
		return true;
	}

	return false;
}

/**
 * Create Movie Wrapper
 *
 * @param array $post_args
 * @param string|array $age_rating
 * @param string|array $genre
 *
 * @return int|WP_Error
 */
function add_movie( array $post_args, $age_rating = false, $genre = false ) {

	$post_id = wp_insert_post( $post_args );

	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	// Attach any include rating, arrays of slug accepted
	if ( ! empty ( $age_rating ) ) {
		wp_set_object_terms( $post_id, $age_rating, 'rating' );
	}

	// Attach any include genre, arrays of slug accepted
	if ( ! empty( $genre ) ) {
		wp_set_object_terms( $post_id, $genre, 'genre' );
	}

	return $post_id;
}


/**
 * Import command for movies
 *
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	class WP_CLI_Movies extends WP_CLI_Command {
		/**
		 * @subcommand populate
		 * @synopsis [--include=<whitelist-regex>] [--verbose]
		 */
		public function import_movies() {
			$genres_json = file_get_contents( __DIR__ . '/genres.json' );
			$genres      = json_decode( $genres_json )->genres;

			$movies_json = file_get_contents( __DIR__ . '/films.json' );
			$movies      = json_decode( $movies_json )->results;

			foreach ( $movies as $movie ) {
				$movie->post_title   = $movie->title;
				$movie->post_content = $movie->overview;
				$movie->post_type    = 'movie';
				$movie->post_status  = 'publish';

				$genre = [];

				foreach ( $movie->genre_ids as $id ) {
					$key = array_search( $id, array_column( $genres, 'id' ), true );

					$genre[] = $genres[ $key ]->name;
				}

				add_movie( (array) $movie, (array) $movie->rating, $genre );
			}
		}
	}
	WP_CLI::add_command( 'movie', 'WP_CLI_Movies' );
}
