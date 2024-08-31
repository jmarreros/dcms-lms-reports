<?php

namespace dcms\reports\includes;

class Enqueue {
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts_backend' ] );
	}

	// Backend scripts
	public function register_scripts_backend(): void {

		// Javascript
		wp_register_script( 'lms-report-script',
			DCMS_REPORTS_URL . '/assets/script.js',
			[ 'jquery' ],
			DCMS_REPORTS_VERSION,
			true );

		wp_localize_script( 'lms-report-script',
			'lms_report',
			[
				'ajaxurl'         => admin_url( 'admin-ajax.php' ),
				'nonce_lms_report' => wp_create_nonce( 'ajax-nonce-lms-report' ),
				'sending'         => __( 'Enviando...', 'dcms-reports-lms' ),
				'processing'      => __( 'Procesando...', 'dcms-reports-lms' )
			] );


		// CSS
		wp_register_style( 'lms-report-style',
			DCMS_REPORTS_URL . '/assets/style.css',
			[],
			DCMS_REPORTS_VERSION );

	}
}