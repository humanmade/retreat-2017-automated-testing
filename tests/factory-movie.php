<?php

/**
 * Class WP_UnitTest_Factory_For_Content_Type
 */
class WP_UnitTest_Factory_For_Movie_CPT extends \WP_UnitTest_Factory_For_Thing {

	/**
	 * WP_UnitTest_Factory_For_Content_Type constructor.
	 *
	 * @param null $factory
	 */
	function __construct( $factory = null ) {
		parent::__construct( $factory );
		$this->default_generation_definitions = array(
			'post_status'  => 'draft',
			'post_title'   => new \WP_UnitTest_Generator_Sequence( 'Post title %s' ),
			'post_content' => new \WP_UnitTest_Generator_Sequence( 'Post content %s' ),
			'post_excerpt' => new \WP_UnitTest_Generator_Sequence( 'Post excerpt %s' ),
		);
	}

	/**
	 * @param $args
	 *
	 * @return int|WP_Error
	 *
	 */
	function create_object( $args ) {
		if ( ! isset( $args['post_type'] ) ) {
			return new WP_Error( 'missing_arg', 'No Content Type Declared' );
		}

		$result = wp_insert_post( $args );

		// Attach any include rating, arrays of slug accepted
		if ( ! empty( $args['age_rating'] ) && ! is_wp_error( $result ) ) {
			wp_set_object_terms( $result, $args['age_rating'], 'rating' );
		}

		// Attach any include genre, arrays of slug accepted
		if ( ! empty( $args['genre'] ) && ! is_wp_error( $result ) ) {
			wp_set_object_terms( $result, $args['genre'], 'genre' );
		}

		return $result;
	}

	/**
	 * @param $post_id
	 * @param $fields
	 *
	 * @return int|WP_Error
	 */
	function update_object( $post_id, $fields ) {
		$fields['ID'] = $post_id;

		return wp_update_post( $fields );
	}

	/**
	 * @param $post_id
	 *
	 * @return array|null|WP_Post
	 */
	function get_object_by_id( $post_id ) {
		return get_post( $post_id );
	}
}
