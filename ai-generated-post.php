<?php
/**
 * Plugin Name:       AI Generated Post For WordPress
 * Plugin URI:        https://github.com/marufnahid/ai-generated-post
 * Description:       This is a plugin for WordPress for creating post, writing content for your blog post, social post, buddypress post , forum post etc.
 * Author:            Maruf
 * Author URI:        https://marufnahid.me
 * Version:           1.0.0
 * Text Domain:       ai-generated-post
 * Domain Path:       /languages
 * License:           GPLv3
 * Requires PHP:      7.3
 * Requires at least: 5.6
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'AIGN_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'AIGN_PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

class AIGN_Post {

	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'aignpost_plugins_loaded' ) );
		add_action( 'init', array( $this, 'aignpost_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'aignpost_admin_scripts' ) );
	}

	function aignpost_plugins_loaded() {
		load_plugin_textdomain( 'ai-generated-post', false, plugin_dir_url( __FILE__ ) . "/languages" );
	}

	function aignpost_admin_scripts( $page_hook ) {

		if ( 'toplevel_page_aignpost-dashboard' == $page_hook || 'aign-post_page_aignpost-settings' == $page_hook || 'aign-post_page_aignpost-generate' == $page_hook ) {
			wp_enqueue_style( 'milligram-css', AIGN_PLUGIN_DIR_URL . "admin/css/milligram.min.css", null, '1.0.0' );
		}
		if ( 'aign-post_page_aignpost-generate' == $page_hook ) {
			wp_enqueue_script( 'aignpost-main', AIGN_PLUGIN_DIR_URL . "admin/js/main.js", array(
				'jquery',
				'wp-tinymce'
			), '1.0.0', true );
		}
	}

	function aignpost_init() {
		require_once AIGN_PLUGIN_DIR_PATH . 'includes/admin-options-handler.php';
		require_once AIGN_PLUGIN_DIR_PATH . 'includes/post-request-handler.php';
	}

}

new AIGN_Post();
