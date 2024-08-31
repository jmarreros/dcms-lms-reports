<?php

namespace dcms\reports\includes;

/**
 * Class for creating a dashboard submenu
 */
class Submenu {

	// Constructor
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'register_submenu' ], 99999, 0);
	}

	// Register submenu
	public function register_submenu(): void {
		add_submenu_page(
			DCMS_REPORTS_SUBMENU,
			__( 'Reportes Extra', 'dcms-reports-lms' ),
			__( 'Reportes Extra', 'dcms-reports-lms' ),
			'manage_options',
			'dcms-reports-lms',
			[ $this, 'submenu_page_callback' ],
			2
		);
	}

	// Callback, show view
	public function submenu_page_callback(): void {
		wp_enqueue_script( 'lms-report-script' );
		wp_enqueue_style( 'lms-report-style' );

		$val_start = '';
		$val_end = '';
		$rows = [];

		include_once( DCMS_REPORTS_PATH . '/views/main-screen.php' );
	}
}
