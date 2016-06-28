<?php
/**
 * Plugin Name: Carlos Rios' Widget Library
 * Description: This library contains widgets that are frequently used by Carlos Rios
 * Author: Carlos Rios
 * Author URI: http://crios.me
 * Version: 1.0
 * Plugin URI: https://github.com/CarlosRios/sermonaudio-embed
 * License: GPL2+
 *
 * @package  Carlos Rios' Widget Library
 * @category WordPress/Plugin
 * @author   Carlos Rios
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
	die;
}

/**
 * CR_Widget_Library class
 *
 * @version  1.0
 */
class CR_Widget_Library {

	/**
	 * The current version of this class
	 *
	 * @since 1.0
	 * @var   string
	 */
	public $version = '1.0';

	/**
	 * @var The single instance of the CR_Widget_Library class
	 * @since  1.0
	 */
	private static $instance;

	/**
	 * Singleton Instance
	 * Ensures only one instance of CR_Widget_Library is loaded or can be loaded ever.
	 *
	 * @since  1.0
	 * @static
	 * @see CRWL()
	 * @return CR_Widget_Library - Main instance
	 */
	public static function get_instance()
	{
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof CR_Widget_Library ) ) {
			self::$instance = new CR_Widget_Library;
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'crwl' ), $this->version );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'crwl' ), $this->version );
	}

	/**
	 * Setup the constant variables for the object
	 *
	 * @since  1.0
	 * @return void
	 */
	public function setup_constants()
	{
		if( ! defined( 'CRWL_DIR' ) ) {
			define( 'CRWL_DIR', plugin_dir_path( __FILE__ ) );
		}

		if( ! defined( 'CRWL_URL' ) ) {
			define( 'CRWL_URL', plugin_dir_url( __FILE__ ) );
		}
	}

	/**
	 * Loads all of the widget files
	 *
	 * @since  1.0
	 * @return void
	 */
	public function includes()
	{
		include_once( CRWL_DIR . 'widgets/class-crwl-image-with-description.php' );
		include_once( CRWL_DIR . 'widgets/class-crwl-random-post-message.php' );
	}

	/**
	 * Registers the hooks for the class
	 *
	 * @since  1.0
	 * @return void
	 */
	public function hooks()
	{
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
	}

	/**
	 * Registers the widgets with WordPress
	 *
	 * @since  1.0
	 * @return void
	 */
	public function register_widgets()
	{
		register_widget( 'CRWL_Image_With_Description' );
		register_widget( 'CRWL_Random_Post_Message' );
	}

}

/**
 * Returns an instance of CR_Widget_Library
 *
 * @since  1.0
 * @return CR_Widget_Library
 */
function CRWLINST()
{
	return CR_Widget_Library::get_instance();
}

// Instantiate the Widget Library
CRWLINST();
