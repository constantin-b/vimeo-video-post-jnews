<?php
/*
* Plugin Name: Vimeo Video Post PRO - Theme JNews compatibility
* Plugin URI: https://vimeotheque.com
* Description: Add-on plugin for Vimeo Video Post PRO - Vimeo videos WordPress importer which introduces compatibility with theme JNews
* Author: CodeFlavors
* Version: 1.1
* Author URI: https://vimeotheque.com
*/

class CVM_Jnews_Compatibility{
	/**
	 * Holds compatible theme name
	 */
	const THEME = 'JNews';
	/**
	 * Holds class instance
	 * @var CVM_Jnews_Compatibility|null
	 */
	private static $instance = null;

	/**
	 * CVM_Jnews_Compatibility constructor.
	 */
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'on_init' ) );
	}

	/**
	 * @return CVM_Jnews_Compatibility|null
	 */
	public static function get_instance(){
		if( null === self::$instance ){
			self::$instance = new CVM_Jnews_Compatibility();
		}
		return self::$instance;
	}

	/**
	 * Hook "init" callback, verifies that plugin is loaded and
	 * that loaded theme is the right theme
	 */
	public function on_init(){
		if( !did_action('vimeotheque_pro_loaded') ){
			return;
		}
		$theme = $this->get_theme();
		if( !$theme ||  self::THEME != $theme->get('Name') ){
			return;
		}

		require_once plugin_dir_path( __FILE__ ) . '/includes/class.cvm-jnews-compatibility.php';
		new CVM_Jnews_Actions_Compatibility( self::THEME );
	}

	/**
	 * Get currently installed parent theme
	 * @return bool|false|WP_Theme
	 */
	private function get_theme(){
		// get template details
		$theme = wp_get_theme();
		if( is_a( $theme, 'WP_Theme' ) ){
			// check if it's child theme
			if( is_a( $theme->parent(), 'WP_Theme' ) ){
				// set theme to parent
				$theme = $theme->parent();
			}
		}else{
			$theme = false;
		}
		return $theme;
	}
}
CVM_Jnews_Compatibility::get_instance();
