<?php
// Load parent theme CSS.
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_style( 'twentyfifteen-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'movietheme-style', get_stylesheet_directory_uri() . '/style.css', ['twentyfifteen-style'], '1' );
} );
