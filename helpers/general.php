<?php

function nonce_verification_report(): void {
	$nonce = $_POST['nonce'];
	if ( ! wp_verify_nonce( $nonce, 'ajax-nonce-lms-report' ) ) {
		wp_send_json( [ 'status' => 0, 'message' => 'Nonce error' ] );
	}
}