<?php
/**
 * Main Plugin Class
 *
 * Coordinates integration with WordPress
 */
class WP_Asciinema_Plugin {

	public function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts_styles' ) );
		$this->register_shortcodes();
	}

	/**
	 * Enqueue scripts and styles needed by this plugin
	 */
	public static function enqueue_scripts_styles() {
		wp_register_script( 'asciinema', WP_Asciinema_Plugin::get_plugin_folder( 'url' ) . 'assets/vendor/asciinema-player.min.js', array(), 'v3.7.0', true );
		wp_register_style( 'asciinema', WP_Asciinema_Plugin::get_plugin_folder( 'url' ) . 'assets/vendor/asciinema-player.css', array(), 'v3.7.0' );
	}

	/**
	 * Registers shortcodes used by this plugin
	 */
	private function register_shortcodes() {
		if ( ! is_admin() ) {
			WP_Asciinema_Shortcode_Asciinema::register();
		}
	}

	/**
	 * Returns the plugin folder for the given type
	 *
	 * @param string $type url|path
	 *
	 * @return string Either the url or the path to this plugin's folder
	 */
	public static function get_plugin_folder( $type ) {
		$plugin_path = __DIR__;
		switch ( $type ) {
			case 'url' :
				return plugin_dir_url( $plugin_path );
				break;
			case 'path' :
			default:
				return plugin_dir_path( $plugin_path );
				break;
		}
	}

	/**
	 * Gets the folder where asciicasts are stored
	 *
	 * @param string $type url|path
	 *
	 * @return string Either the url or the path to the folder containing
	 *		the asciicasts
	 */
	public static function get_asciicast_folder( $type ) {

		// Allow filtering the location of the asciicast folder
		$asciicast_folder = trailingslashit( apply_filters( 'wp_asciinema_asciicasts_folder', 'asciicasts' ) );

		$upload = wp_upload_dir();

		$asciicast_path = $upload['basedir'] . '/' . $asciicast_folder;
		if ( ! file_exists( $asciicast_path ) ) {
			wp_mkdir_p( $asciicast_path );
		}

		switch ( $type ) {
			case 'url':
				return $upload['baseurl'] . '/' . $asciicast_folder;
				break;
			case 'path':
			default:
				return $asciicast_path;
				break;
		}

	}

}

