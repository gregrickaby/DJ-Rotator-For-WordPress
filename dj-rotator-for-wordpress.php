<?php
/**
 * Plugin Name: DJ Rotator for WordPress
 * Plugin URI:  https://github.com/gregrickaby/DJ-Rotator-For-WordPress
 * Description: Easily manage disc jockey on-air schedules and display them in your sidebar.
 * Version:     1.0.0
 * Author:      Greg Rickaby
 * Author URI:  https://gregrickaby.com
 * Donate link: https://github.com/gregrickaby/DJ-Rotator-For-WordPress
 * License:     GPLv3
 * Text Domain: dj-rotator-for-wordpress
 * Domain Path: /languages
 *
 * @link https://github.com/gregrickaby/DJ-Rotator-For-WordPress
 *
 * @package DJ Rotator for WordPress
 * @version 1.0.0
 */

/**
 * Copyright (c) 2016 Greg Rickaby (email : greg@gregrickaby.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using generator-plugin-wp
 */


/**
 * Autoloads files with classes when needed
 *
 * @since  1.0.0
 * @param  string $class_name Name of the class being requested.
 * @return void
 */
function grd_rotator_autoload_classes( $class_name ) {
	if ( 0 !== strpos( $class_name, 'GRDR_' ) ) {
		return;
	}

	$filename = strtolower( str_replace(
		'_', '-',
		substr( $class_name, strlen( 'GRDR_' ) )
	) );

	GRD_Rotator::include_file( $filename );
}
spl_autoload_register( 'grd_rotator_autoload_classes' );

/**
 * Main initiation class
 *
 * @since  1.0.0
 */
final class GRD_Rotator {

	/**
	 * Current version
	 *
	 * @var  string
	 * @since  1.0.0
	 */
	const VERSION = '1.0.0';

	/**
	 * URL of plugin directory
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $path = '';

	/**
	 * Plugin basename
	 *
	 * @var string
	 * @since  1.0.0
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin
	 *
	 * @var GRD_Rotator
	 * @since  1.0.0
	 */
	protected static $single_instance = null;

	/**
	 * Instance of GRDR_Grd_Dj_Cpt
	 *
	 * @since 1.0.0
	 * @var GRDR_Grd_Dj_Cpt
	 */
	protected $grd_dj_cpt;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since  1.0.0
	 * @return GRD_Rotator A single instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin
	 *
	 * @since  1.0.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( __FILE__ );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function plugin_classes() {
		// Attach other plugin classes to the base plugin class.
		$this->grd_dj_cpt = new GRDR_Grd_Dj_Cpt( $this );
	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add hooks and filters
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function hooks() {

		add_action( 'init', array( $this, 'init' ), 4 );
	}

	/**
	 * Activate the plugin
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function _activate() {
		// Make sure any rewrite functionality has been loaded.
		flush_rewrite_rules();
	}

	/**
	 * Deactivate the plugin
	 * Uninstall routines should be in uninstall.php
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function _deactivate() {}

	/**
	 * Init hooks
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function init() {
		if ( $this->check_requirements() ) {
			load_plugin_textdomain( 'dj-rotator-for-wordpress', false, dirname( $this->basename ) . '/languages/' );
			$this->plugin_classes();
		}
	}

	/**
	 * Check if the plugin meets requirements and
	 * disable it if they are not present.
	 *
	 * @since  1.0.0
	 * @return boolean result of meets_requirements
	 */
	public function check_requirements() {
		if ( ! $this->meets_requirements() ) {

			// Add a dashboard notice.
			add_action( 'all_admin_notices', array( $this, 'requirements_not_met_notice' ) );

			// Deactivate our plugin.
			add_action( 'admin_init', array( $this, 'deactivate_me' ) );

			return false;
		}

		return true;
	}

	/**
	 * Deactivates this plugin, hook this function on admin_init.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function deactivate_me() {
		deactivate_plugins( $this->basename );
	}

	/**
	 * Check that all plugin requirements are met
	 *
	 * @since  1.0.0
	 * @return boolean True if requirements are met.
	 */
	public static function meets_requirements() {
		// Do checks for required classes / functions
		// function_exists('') & class_exists('').
		// We have met all requirements.
		return true;
	}

	/**
	 * Adds a notice to the dashboard if the plugin requirements are not met
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public function requirements_not_met_notice() {
		// Output our error.
		echo '<div id="message" class="error">';
		echo '<p>' . sprintf( __( 'DJ Rotator for WordPress is missing requirements and has been <a href="%s">deactivated</a>. Please make sure all requirements are available.', 'dj-rotator-for-wordpress' ), admin_url( 'plugins.php' ) ) . '</p>';
		echo '</div>';
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  1.0.0
	 * @param string $field Field to get.
	 * @throws Exception Throws an exception if the field is invalid.
	 * @return mixed
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'grd_dj_cpt':
				return $this->$field;
			default:
				throw new Exception( 'Invalid '. __CLASS__ .' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory
	 *
	 * @since  1.0.0
	 * @param  string $filename Name of the file to be included.
	 * @return bool   Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( 'includes/class-'. $filename .'.php' );
		if ( file_exists( $file ) ) {
			return include_once( $file );
		}
		return false;
	}

	/**
	 * This plugin's directory
	 *
	 * @since  1.0.0
	 * @param  string $path (optional) appended path.
	 * @return string       Directory and path
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );
		return $dir . $path;
	}

	/**
	 * This plugin's url
	 *
	 * @since  1.0.0
	 * @param  string $path (optional) appended path.
	 * @return string       URL and path
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );
		return $url . $path;
	}
}

/**
 * Grab the GRD_Rotator object and return it.
 * Wrapper for GRD_Rotator::get_instance()
 *
 * @since  1.0.0
 * @return GRD_Rotator  Singleton instance of plugin class.
 */
function grd_rotator() {
	return GRD_Rotator::get_instance();
}

// Include files.
require_once( 'includes/class-grd-dj-widget.php' );

/**
 * Register this widget with WordPress. Can also move this function to the parent plugin.
 *
 * @since  1.0.0
 * @return void
 */
function grd_register_widget() {
	register_widget( 'GRDR_Grd_Dj_Widget' );
}
add_action( 'widgets_init', 'grd_register_widget' );

// Kick it off.
add_action( 'plugins_loaded', array( grd_rotator(), 'hooks' ) );

register_activation_hook( __FILE__, array( grd_rotator(), '_activate' ) );
register_deactivation_hook( __FILE__, array( grd_rotator(), '_deactivate' ) );
