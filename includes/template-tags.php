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
 * Helper to flush transient cache.
 *
 * @since 1.0.0
 * @return void
 */
function grd_cache_flush() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return false;
	}
	delete_transient( 'grd_all_djs' );
	wp_cache_delete( 'grd-rotator', 'widget' );
}
add_action( 'delete_category', 'grd_cache_flush' );
add_action( 'save_post', 'grd_cache_flush' );


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
 * Helper to get blog time as set in General -> Settings.
 *
 * @since 1.0.0
 * @return string  Local time in UNIX format.
 */
function grd_get_current_time() {
	return current_time( 'timestamp', 0 );
}


/**
 * Helper to get DJ's image ID.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return int               The DJ image ID.
 */
function grd_get_dj_image_ID( $post_ID = false ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_image_id', true );
}


/**
 * Helper to get DJ's image URL.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return string            The DJ image URL.
 */
function grd_get_dj_image_url( $post_ID = false ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_image', true );
}


/**
 * Helper to get DJ's biography.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return string            The DJ biography.
 */
function grd_get_dj_bio( $post_ID = false ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_bio', true );
}


/**
 * Helper to get DJ's schedule.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return array             The DJ schedule in a multidimensional array.
 */
function grd_get_dj_schedule( $post_ID = false ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_schedule_group', true );
}


/**
 * Helper to get DJ's website URL.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return string            The DJ's website URL.
 */
function grd_get_dj_website_url( $post_ID = false ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_website_url', true );
}


/**
 * Helper to get DJ's Facebook URL.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return string            The DJ's Facebook URL.
 */
function grd_get_dj_facebook_url( $post_ID = false ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_facebook_url', true );
}


/**
 * Helper to get DJ's Twitter URL.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return string            The DJ's Facebook URL.
 */
function grd_get_dj_twitter_url( $post_ID = false ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_twitter_url', true );
}


/**
 * Helper to get DJ's Instagram URL.
 *
 * @since 1.0.0
 * @param  int    $post_ID   The post ID.
 * @return string            The DJ's Instagram URL.
 */
function grd_get_dj_instagram_url( $post_ID = false ) {
	$post_ID = ( $post_ID ) ? $post_ID : get_the_ID();
	return get_post_meta( $post_ID, 'grd_dj_instagram_url', true );
}


/**
 * Create the DJ rotator HTML markup.
 *
 * @since 1.0.0
 * @return string   The DJ rotator markup.
 */
function grd_get_dj_markup() {

	ob_start(); ?>

	<figure class="dj">
		<img src="<?php echo esc_url( grd_get_dj_image_url() ); ?>" alt="<?php echo get_the_title(); ?>">
		<figcaption>
			<p class="dj-name"><?php the_title(); ?></p>
			<p class="dj-bio"><?php echo wp_kses_post( grd_get_dj_bio() ); ?></p>

			<?php if ( grd_get_dj_website_url() ) : ?>
				<a class="dj-url dj-website" href="<?php echo esc_url( grd_get_dj_website_url() ); ?>"><?php esc_html_e( 'Website', 'grd-rotator' ); ?></a>
			<?php endif; ?>

			<?php if ( grd_get_dj_facebook_url() ) : ?>
				<a class="dj-url dj-facebook" href="<?php echo esc_url( grd_get_dj_facebook_url() ); ?>"><?php esc_html_e( 'Facebook', 'grd-rotator' ); ?></a>
			<?php endif; ?>

			<?php if ( grd_get_dj_twitter_url() ) : ?>
				<a class="dj-url dj-twitter" href="<?php echo esc_url( grd_get_dj_twitter_url() ); ?>"><?php esc_html_e( 'Twitter', 'grd-rotator' ); ?></a>
			<?php endif; ?>

			<?php if ( grd_get_dj_instagram_url() ) : ?>
				<a class="dj-url dj-instagram" href="<?php echo esc_url( grd_get_dj_instagram_url() ); ?>"><?php esc_html_e( 'Instagram', 'grd-rotator' ); ?></a>
			<?php endif; ?>
		</figcaption>
	</figure>

	<?php
	return ob_get_clean();
}