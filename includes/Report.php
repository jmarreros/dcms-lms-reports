<?php

namespace dcms\reports\includes;

class Report {

	public function __construct() {
		add_action( 'wp_ajax_dcms_get_courses_by_hint', [ $this, 'get_courses_by_hint' ] );
	}

	public function get_courses_by_hint(): void {

		nonce_verification_report();

		$start_date         = $_POST['start_date'];
		$end_date           = $_POST['end_date'];
		$course_search_hint = $_POST['course_search_hint'];

		$database = new Database();
		$courses  = $database->get_courses_by_hint( $course_search_hint , $start_date, $end_date);

		wp_send_json(
			[
				'status'  => 1,
				'message' => 'success',
				'courses'    => $courses
			]
		);
	}

}
