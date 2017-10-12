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

	<?php if ( ! isset( $GLOBALS['movie_status'] ) ) : ?>
		<form method="get" action="/" class="movie-form movie-search-form">
			<p>Search for a movie to add to the database:</p>

			<label for="moviesearch">Movie name:</label>
			<input type="search" name="moviesearch" class="moviesearch" placeholder="e.g. 'Beauty and the Beast'" required>

			<input type="submit" value="Start search">
		</form>
	<?php else: ?>
		<div class="notice published updated"><?php echo esc_html( $GLOBALS['movie_status'] ); ?></div>
	<?php endif; ?>

	<form method="post" action="/" class="movie-form movie-entry-form initially-hidden">
		<label for="moviename">Movie name:</label>
		<input type="text" name="moviename" required>

		<label for="moviedescription">Movie summary:</label>
		<textarea name="moviedescription" required></textarea>

		<label for="movierating">Movie rating:</label>
		<?php wp_dropdown_categories( 'taxonomy=rating&name=movierating&required=1&value_field=slug&hide_empty=0' ); ?>

		<fieldset class="fieldset-genres">
			<legend>Movie genres:</legend>

			<?php
			$terms = get_terms( 'genre', [ 'hide_empty' => false ] );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					echo '<div><label><input type="checkbox" name="moviegenre[]" value="' . esc_attr( $term->term_id ) . '" data-jsonid="' . esc_attr( get_term_meta( $term->term_id, 'json_id', true ) ) . '">' . esc_html( $term->name ) . '</label></div>';
				}
			}
			?>
		</fieldset>

		<input type="submit" value="Add movie to collection">
	</form>
<?php
} );

add_action( 'template_redirect', function() {
	if ( strtoupper( $_SERVER['REQUEST_METHOD'] ) !== 'POST' ) {
		return;
	}

	// Handle "add movie" form submission.
	if ( is_admin() || ! isset( $_POST['moviename'], $_POST['moviedescription'], $_POST['movierating'], $_POST['moviegenre'] ) ) {
		return;
	}

	$movie_desc   = wp_kses_post( wp_unslash( $_POST['moviedescription'] ) );
	$movie_genre  = array_map( 'absint', (array) $_POST['moviegenre'] );
	$movie_name   = sanitize_text_field( wp_unslash( $_POST['moviename'] ) );
	$movie_rating = sanitize_text_field( wp_unslash( $_POST['movierating'] ) );

	add_movie(
		[
			'post_content' => $movie_desc,
			'post_status'  => 'publish',
			'post_title'   => $movie_name,
			'post_type'    => 'movie',
		],
		$movie_rating,
		$movie_genre
	);

	$GLOBALS['movie_status'] = 'Added movie to collection.';
} );

/**
 * Conditional Check for age suitable for movies
 *
 * @param int $movie_id
 * @param int $age
 * @test data Providers
 *
 * @return bool
 */
function is_suitable_for( int $movie_id = 0, int $age ) : bool {
	$movie = get_post( $movie_id );

	if ( is_null( $movie ) ) {
		return false;
	}

	$movie_id       = $movie->ID;

	$movie_age_rating = wp_get_object_terms( $movie_id, 'rating' );

	if ( (int) get_term_meta( $movie_age_rating[0]->term_id, 'age_relation', true ) <= $age ) {
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

c

	return $post_id;
}

/**
 * Importer function for movies from Json
 */
function import_movies() {
	$genres_json = file_get_contents( __DIR__ . '/genres.json' ); // @codingStandardsIgnoreLine
	$genres      = json_decode( $genres_json )->genres;

	$movies_json = file_get_contents( __DIR__ . '/films.json' ); // @codingStandardsIgnoreLine
	$movies      = json_decode( $movies_json )->results;

	$movie_ids = [];

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

		$movie_id = add_movie( (array) $movie, (array) $movie->rating, $genre );

		if ( is_int( $movie_id ) ) {
			$movie_ids[] = $movie_id;
		}
	}

	return $movie_ids;
}


/**
 * Import command for movies
 */
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	class WP_CLI_Movies extends WP_CLI_Command {
		/**
		 * @subcommand populate
		 * @synopsis [--include=<whitelist-regex>] [--verbose]
		 */
		public function import_movies() {
			import_movies();
		}
	}
	WP_CLI::add_command( 'movie', 'WP_CLI_Movies' );
}
