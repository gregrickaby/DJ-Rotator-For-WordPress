<?php
/**
 * DJ Rotator for WordPress Template Tags
 *
 * @since 1.0.0
 * @package DJ Rotator for WordPress
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Helper to get all published DJs.
 *
 * @since 1.0.0
 * @param  array   $args  WP_Query args. Optional.
 * @return object         WP_Query results.
 */
function grd_get_all_djs( $args = array() ) {
	return grd_rotator()->grd_dj_cpt->get_all_djs( $args );
}


/**
 * Helper to get current local time
 * as set in General --> Settings.
 *
 * @since 1.0.0
 * @return string  Local time in UNIX format.
 */
function grd_get_current_time() {
	return current_time( 'timestamp', true );
}


/**
 * Helper to get DJ image ID.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return int               The DJ image ID.
 */
function grd_get_dj_image_ID( $post_ID ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_image_id', true );
}


/**
 * Helper to get DJ image URL.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return string            The DJ image URL.
 */
function grd_get_dj_image_url( $post_ID ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_image', true );
}


/**
 * Helper to get DJ biography.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return string            The DJ biography.
 */
function grd_get_dj_bio( $post_ID ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_bio', true );
}
