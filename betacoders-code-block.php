<?php
/**
 * Plugin Name: BetaCoders Code Block
 * Plugin URI: https://github.com/ayoubkhan558/betacoders-code-block
 * Description: Add code block with syntax highlighting using prism.js. (Available for Gutenberg and Classic Editor)
 * Version: 1.2.9
 * Author: < The Beta Coders />  
 * Author URI: https://www.mayoubkhan.com/
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ayoub-bccb
 * Domain Path: /languages
 */
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! function_exists( 'register_block_type' ) ) return;

/**
 * Defined HCB const.
 */
define( 'AYOUB_BCCB_VERSION', ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? date('mdGis') : '1.2.9');
define( 'AYOUB_BCCB_PATH', plugin_dir_path( __FILE__ ) );
define( 'AYOUB_BCCB_BASENAME', plugin_basename( __FILE__ ) );
define( 'AYOUB_BCCB_URL', plugins_url( '/', __FILE__ ) );

/**
 * Autoload Class files.
 */
spl_autoload_register( function( $classname ) {

	if ( false === strpos( $classname, 'AYOUB_BCCB' ) ) return;
	$file = AYOUB_BCCB_PATH .'class/'. mb_strtolower( $classname ) .'.php';
	if ( file_exists( $file ) ) require $file;
});

/**
 * Activation hooks.
 */
register_activation_hook( __FILE__, ['AYOUB_BCCB_Activation', 'plugin_activate'] );
register_uninstall_hook( __FILE__, ['AYOUB_BCCB_Activation', 'plugin_uninstall'] );

/**
 * Start
 */
add_action( 'plugins_loaded', function() {
	// 翻訳
	// $locale = apply_filters( 'plugin_locale', determine_locale(), 'ayoub-bccb' );
	// load_textdomain( 'ayoub-bccb', AYOUB_BCCB_PATH . 'languages/ayoub-bccb-' . $locale . '.mo' );
	if ( 'ja' === determine_locale() ) {
		load_textdomain( 'ayoub-bccb', AYOUB_BCCB_PATH . 'languages/ayoub-bccb-ja.mo' );
	} else {
		load_plugin_textdomain( 'ayoub-bccb' );
	}

	// 実行
	new AYOUB_BCCB();
});
