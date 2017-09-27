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
			'thumbnail',
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

	<form method="get" action="/" class="movie-entry-form">
		<fieldset>
			<legend>Search for a movie to add to the database:</legend>

			<label for="moviesearch">Title</label>
			<input type="search" name="moviesearch" id="moviesearch" placeholder="e.g. 'Beauty and the Beast'" required>

			<input type="submit" value="Start search">
		</fieldset>
	</form>

<?php
} );
