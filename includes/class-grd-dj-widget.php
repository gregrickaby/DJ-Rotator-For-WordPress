<?php
/**
 * DJ Rotator for WordPress Grd Dj Widget
 *
 * @since 1.0.0
 * @package DJ Rotator for WordPress
 */

/**
 * DJ Rotator for WordPress Grd Dj Widget class.
 *
 * @since 1.0.0
 */
class GRDR_Grd_Dj_Widget extends WP_Widget {

	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $widget_slug = 'grd-rotator';


	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $widget_name = '';


	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $default_widget_title = '';


	/**
	 * Shortcode name for this widget
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected static $shortcode = 'grd-rotator';


	/**
	 * Construct widget class.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {

		$this->widget_name          = esc_html__( 'DJ Rotator', 'grd-rotator' );
		$this->default_widget_title = esc_html__( 'DJ Rotator', 'grd-rotator' );

		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			array(
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'Display the current DJ in a sidebar.', 'grd-rotator' ),
			)
		);

		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		add_shortcode( self::$shortcode, array( __CLASS__, 'get_widget' ) );
	}


	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}


	/**
	 * Front-end display of widget.
	 *
	 * @since  1.0.0
	 * @param  array $args     The widget arguments set up when a sidebar is registered.
	 * @param  array $instance The widget settings as set by user.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		echo self::get_widget( array(
			'before_widget' => $args['before_widget'],
			'after_widget'  => $args['after_widget'],
			'before_title'  => $args['before_title'],
			'after_title'   => $args['after_title'],
			'title'         => $instance['title'],
		) );
	}


	/**
	 * Return the widget/shortcode output
	 *
	 * @since  1.0.0
	 * @param  array $atts Array of widget/shortcode attributes/args.
	 * @return string       Widget output
	 */
	public static function get_widget( $atts ) {
		$widget = '';

		// Set up default values for attributes.
		$atts = shortcode_atts(
			array(
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => '',
				'title'         => '',
			),
			(array) $atts,
			self::$shortcode
		);

		// Setup data variables.
		$dj_query = grd_get_all_djs();
		$blog_time = grd_get_current_time();
		$current_weekday = date( 'D', $blog_time );
		$current_time = date( 'U' , $blog_time );

		// Start the widget markup.
		ob_start();

		echo $atts['before_widget'];
		echo ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '';

		// If there are DJs...
		if ( $dj_query->have_posts() ) : ?>

			<ul class="dj-rotator">

			<?php
				while ( $dj_query->have_posts() ) : $dj_query->the_post();

					// Get this DJ's shifts.
					$shifts = grd_get_dj_schedule();

					// Loop through shifts.
					foreach ( $shifts as $shift ) :

						// Only echo if the shift falls between this date/time comparison.
						if ( $shift['grd_dj_weekday'] === $current_weekday && $shift['grd_dj_start_time'] <= $current_time && $shift['grd_dj_end_time'] >= $current_time ) :
							echo grd_get_dj_markup();
						endif;

					endforeach;
				endwhile;
				wp_reset_postdata();
			?>

			</ul><!-- .dj-rotator -->

		<?php endif;
		echo $atts['after_widget'];

		// Return widget markup.
		return ob_get_clean();
	}


	/**
	 * Update form values as they are saved.
	 *
	 * @since  1.0.0
	 * @param  array $new_instance New settings for this instance as input by the user.
	 * @param  array $old_instance Old settings for this instance.
	 * @return array               Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		// Previously saved values.
		$instance = $old_instance;

		// Sanitize title before saving to database.
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		// Flush cache.
		$this->flush_widget_cache();

		return $instance;
	}


	/**
	 * Back-end widget form with defaults.
	 *
	 * @since  1.0.0
	 * @param  array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance,
			array(
				'title' => $this->default_widget_title,
			)
		);

		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'grd-rotator' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" /></p>
		<?php
	}
}