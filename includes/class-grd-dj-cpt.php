<?php
/**
 * DJ Rotator for WordPress Grd Dj Cpt
 *
 * @since 1.0.0
 * @package DJ Rotator for WordPress
 */

require_once dirname(__FILE__) . '/../vendor/cpt-core/CPT_Core.php';
require_once dirname(__FILE__) . '/../vendor/cmb2/init.php';

/**
 * DJ Rotator for WordPress Grd Dj Cpt post type class.
 *
 * @see https://github.com/WebDevStudios/CPT_Core
 * @since 1.0.0
 */
class GRDR_Grd_Dj_Cpt extends CPT_Core {
	/**
	 * Parent plugin class
	 *
	 * @var class
	 * @since  1.0.0
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 * Register Custom Post Types. See documentation in CPT_Core, and in wp-includes/post.php
	 *
	 * @since  1.0.0
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		// Register this cpt
		// First parameter should be an array with Singular, Plural, and Registered name.
		parent::__construct(
			array(
				__( 'DJ', 'grd-rotator' ),
				__( 'DJs', 'grd-rotator' ),
				'grd-djs',
			),
			array(
				'menu_icon' => 'dashicons-microphone',
				'rewrite'   => array( 'slug' => 'djs' ),
				'supports'  => array( 'title', 'revisions' ),
			)
		);
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'cmb2_init', array( $this, 'fields' ) );
	}

	/**
	 * Add custom fields to the CPT
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function fields() {
		$prefix = 'grd_dj_';

		$cmb = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => __( 'DJ Options', 'grd-rotator' ),
			'object_types'  => array( 'grd-djs' ),
		) );

		/**
		 * Bio.
		 */
		$cmb->add_field( array(
			'name' => __( 'Image', 'grd-rotator' ),
			'desc' => __( 'Upload an image or enter a URL. 640x640px (jpg, png)', 'grd-rotator' ),
			'id'   => $prefix . 'image',
			'type' => 'file',
		) );

		$cmb->add_field( array(
			'name'    => __( 'Biography', 'grd-rotator' ),
			'desc'    => __( 'Optional. Write a quick blurb about this DJ.', 'grd-rotator' ),
			'id'      => $prefix . 'wysiwyg',
			'type'    => 'wysiwyg',
			'options' => array(
				'media_buttons' => false,
				'textarea_rows' => 5,
			),
		) );

		$cmb->add_field( array(
			'name' => __( 'Website URL', 'grd-rotator' ),
			'desc' => __( 'Optional. http://gregrickaby.com', 'grd-rotator' ),
			'id'   => $prefix . '_website_url',
			'type' => 'text_url',
		) );

		$cmb->add_field( array(
			'name' => __( 'Facebook URL', 'grd-rotator' ),
			'desc' => __( 'Optional. https://www.facebook.com/gregrickaby/', 'grd-rotator' ),
			'id'   => $prefix . '_facebook_url',
			'type' => 'text_url',
		) );

		$cmb->add_field( array(
			'name' => __( 'Twitter URL', 'grd-rotator' ),
			'desc' => __( 'Optional. https://twitter.com/gregrickaby/', 'grd-rotator' ),
			'id'   => $prefix . '_twitter_url',
			'type' => 'text_url',
		) );

		$cmb->add_field( array(
			'name' => __( 'Instagram URL', 'grd-rotator' ),
			'desc' => __( 'Optional. https://www.instagram.com/gregoryrickaby/', 'grd-rotator' ),
			'id'   => $prefix . 'instagram_url',
			'type' => 'text_url',
		) );

		/**
		 * Schedule.
		 */
		$cmb_group = new_cmb2_box( array(
			'id'           => $prefix . 'metabox',
			'title'        => __( 'DJ Schedule', 'grd-rotator' ),
			'object_types' => array( 'grd-djs' ),
		) );

		$group_field_id = $cmb_group->add_field( array(
			'id'          => $prefix . 'schedule_group',
			'type'        => 'group',
			'description' => __( 'Create a schedule for this DJ.', 'grd-rotator' ),
			'options'     => array(
				'group_title'   => __( 'Day {#}', 'grd-rotator' ), // {#} gets replaced by row number
				'add_button'    => __( 'Add Another Day', 'grd-rotator' ),
				'remove_button' => __( 'Remove Day', 'grd-rotator' ),
				'sortable'      => false, // beta
				'closed'        => true, // true to have the groups closed by default
			),
		) );

		$cmb_group->add_group_field( $group_field_id, array(
			'name'             => __( 'Day of the week', 'grd-rotator' ),
			'desc'             => __( 'Required.', 'grd-rotator' ),
			'id'               => $prefix . 'weekday',
			'type'             => 'select',
			'show_option_none' => false,
			'options'          => array(
				'sun' => __( 'Sunday', 'grd-rotator' ),
				'mon' => __( 'Monday', 'grd-rotator' ),
				'tue' => __( 'Tuesday', 'grd-rotator' ),
				'wed' => __( 'Wednesday', 'grd-rotator' ),
				'thu' => __( 'Thursday', 'grd-rotator' ),
				'fri' => __( 'Friday', 'grd-rotator' ),
				'sat' => __( 'Saturday', 'grd-rotator' ),
			),
		) );

		$cmb_group->add_group_field( $group_field_id, array(
			'name'        => __( 'Start Time', 'grd-rotator' ),
			'desc'        => __( 'Required.', 'grd-rotator' ),
			'id'          => $prefix . 'start_time',
			'type'        => 'text_time',
			'time_format' => 'H:i',
		) );

		$cmb_group->add_group_field( $group_field_id, array(
			'name'        => __( 'End Time', 'grd-rotator' ),
			'desc'        => __( 'Required.', 'grd-rotator' ),
			'id'          => $prefix . 'end_time',
			'type'        => 'text_time',
			'time_format' => 'H:i',
		) );
	}

	/**
	 * Registers admin columns to display. Hooked in via CPT_Core.
	 *
	 * @since  1.0.0
	 * @param  array $columns Array of registered column names/labels.
	 * @return array          Modified array
	 */
	public function columns( $columns ) {
		$new_column = array();
		return array_merge( $new_column, $columns );
	}

	/**
	 * Handles admin column display. Hooked in via CPT_Core.
	 *
	 * @since  1.0.0
	 * @param array $column  Column currently being rendered.
	 * @param int   $post_id ID of post to display column for.
	 */
	public function columns_display( $column, $post_id ) {
		switch ( $column ) {
		}
	}
}
