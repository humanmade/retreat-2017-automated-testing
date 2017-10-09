<?php
/**
 * The template for displaying archive pages
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">Movie Collection</h1>
			</header><!-- .page-header -->

			<div class="page-content">
				<?php
				wp_list_categories( [
					'taxonomy' => 'rating',
					'title_li' => 'Movie Rating:'
				] );
				echo '<br>';

				wp_list_categories( [
					'taxonomy' => 'genre',
					'title_li' => 'Movie Genres:'
				] );
				?>
			</div>

			<?php
			// Start the Loop.
			while ( have_posts() ) : the_post();

				get_template_part( 'content', 'movie' );

			// End the loop.
			endwhile;

			// Previous/next page navigation.
			the_posts_pagination( array(
				'prev_text'          => __( 'Previous page', 'twentyfifteen' ),
				'next_text'          => __( 'Next page', 'twentyfifteen' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>',
			) );

		// If no content, include the "No posts found" template.
		else :
			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
