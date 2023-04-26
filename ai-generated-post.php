<?php
/**
 * Plugin Name:       AI Generated Post
 * Plugin URI:        https://github.com/marufnahid/ai-generated-post
 * Description:       This is a plugin for WordPress for creating post, writing content for your blog post, social post, buddypress post , forum post etc.
 * Author:            Maruf
 * Author URI:        https://github.com/marufnahid
 * Version:           1.0.0
 * Text Domain:       aignpost
 * Domain Path:       /languages/
 * License:           GPLv2 or later (license.txt)
 * Requires PHP:      5.6.20
 * Requires at least: 5.0
 */


define( 'PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'PLUGIN_DIR_PATH', plugin_dir_path( __FILE__ ) );

class AI_Generated_Post {

	function __construct() {
		add_action( 'plugins_loaded', array( $this, 'aignpost_plugins_loaded' ) );
		add_action( 'init', array( $this, 'aignpost_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'aignpost_admin_scripts' ) );
	}

	function aignpost_plugins_loaded() {
		load_plugin_textdomain( 'aignpost', false, plugin_dir_url( __FILE__ ) . "/languages" );
	}

	function aignpost_admin_scripts( $page_hook ) {

		if ( 'toplevel_page_aignpost-dashboard' == $page_hook || 'aign-post_page_aignpost-settings' == $page_hook || 'aign-post_page_aignpost-generate' == $page_hook ) {
			wp_enqueue_style( 'milligram-css', PLUGIN_DIR_URL . "admin/css/milligram.min.css", null, time() );
		}
		if ( 'aign-post_page_aignpost-generate' == $page_hook ) {
			wp_enqueue_script( 'aignpost-main', PLUGIN_DIR_URL . "admin/js/main.js", array(
				'jquery',
				'wp-tinymce'
			), time(), true );
		}
	}

	function aignpost_init() {
		require_once PLUGIN_DIR_PATH . 'includes/admin-options-handler.php';
		require_once PLUGIN_DIR_PATH . 'includes/post-request-handler.php';
	}

}

new AI_Generated_Post();