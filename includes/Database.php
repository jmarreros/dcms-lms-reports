<?php

namespace dcms\reports\includes;

use wpdb;

class Database {
	private wpdb $wpdb;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	public function get_courses_by_hint( string $courses_name_hint, string $start_date, string $end_date ): array {
		$post_table     = $this->wpdb->posts;
		$postmeta_table = $this->wpdb->postmeta;

		$sql = "SELECT c.ID, c.post_title name FROM $post_table c
				INNER JOIN $postmeta_table cm 
				    ON c.ID = cm.post_id
				WHERE 
				c.post_type = 'stm-courses' AND 
				cm.meta_key = '" . DCMS_COURSE_END_DATE . "' AND
				DATE(cm.meta_value) BETWEEN '$start_date' AND '$end_date' AND
				c.post_title LIKE '%$courses_name_hint%'";

		return $this->wpdb->get_results( $sql, ARRAY_A );
	}
}
