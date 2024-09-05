<?php
/*
Plugin Name: Extra Reports LMS
Plugin URI: https://decodecms.com
Description: Add extra reports of courses
Version: 1.2
Author: Jhon Marreros Guzmán
Author URI: https://decodecms.com
Text Domain: dcms-reports-lms
Domain Path: languages
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

namespace dcms\reports;

require __DIR__ . '/vendor/autoload.php';

use dcms\reports\includes\Plugin;
use dcms\reports\includes\Submenu;
use dcms\reports\includes\Enqueue;
use dcms\reports\includes\Report;
use dcms\reports\includes\Export;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin class to handle settings constants and loading files
 **/
final class Loader {

	// Define all the constants we need
	public function define_constants(): void {
		define( 'DCMS_REPORTS_VERSION', '1.2' );
		define( 'DCMS_REPORTS_PATH', plugin_dir_path( __FILE__ ) );
		define( 'DCMS_REPORTS_URL', plugin_dir_url( __FILE__ ) );
		define( 'DCMS_REPORTS_BASE_NAME', plugin_basename( __FILE__ ) );
		define( 'DCMS_REPORTS_SUBMENU', 'stm-lms-settings' );
		if ( ! defined( 'DCMS_COURSE_END_DATE' ) ) {
			define( 'DCMS_COURSE_END_DATE', 'dcms_course_end_date' );
		}
	}

	// Load tex domain
	public function load_domain() {
		add_action( 'plugins_loaded', function () {
			$path_languages = dirname( DCMS_REPORTS_BASE_NAME ) . '/languages/';
			load_plugin_textdomain( 'dcms-reports-lms', false, $path_languages );
		} );
	}

	// Add link to plugin list
	public function add_link_plugin(): void {
		add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), function ( $links ) {
			return array_merge( array(
				'<a href="' . esc_url( admin_url( DCMS_REPORTS_SUBMENU . '?page=lemans-migration' ) ) . '">' . __( 'Settings', 'dcms-reports-lms' ) . '</a>'
			), $links );
		} );
	}

	// Initialize all
	public function init(): void {
		$this->define_constants();
		$this->load_domain();
		$this->add_link_plugin();
		new Plugin();
		new SubMenu();
		new Enqueue();
		new Report();
		new Export();
	}
}

$dcms_reports_process = new Loader();
$dcms_reports_process->init();
