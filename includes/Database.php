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
		$post_meta_table = $this->wpdb->postmeta;

		if ( empty( $start_date )  || empty( $end_date ) ) {
			return [];
		}

		$sql = "SELECT c.ID, c.post_title name FROM $post_table c
				INNER JOIN $post_meta_table cm 
				    ON c.ID = cm.post_id
				WHERE 
				c.post_type = 'stm-courses' AND 
				cm.meta_key = '" . DCMS_COURSE_END_DATE . "' AND
				DATE(cm.meta_value) BETWEEN '$start_date' AND '$end_date' AND
				c.post_title LIKE '%$courses_name_hint%'";

		return $this->wpdb->get_results( $sql, ARRAY_A );
	}

	public function get_students_by_courses($courses_ids): array {
		$user_table     = $this->wpdb->users;
		$user_meta_table = $this->wpdb->usermeta;
		$user_courses_table = $this->wpdb->prefix . 'stm_lms_user_courses';

		if ( empty( $courses_ids ) ) {
			return [];
		}

		$sql = "SELECT ID, 
	                user_login, 
	                display_name user_name, 
	                user_email, 
	                um.meta_value user_phone 
				FROM $user_table u
				INNER JOIN (
						SELECT DISTINCT user_id FROM $user_courses_table 
                        WHERE course_id IN (" . implode(',', $courses_ids) . ")
				) uc ON u.ID = uc.user_id
				LEFT JOIN $user_meta_table um ON u.ID = um.user_id AND meta_key = 'billing_phone'";

		return $this->wpdb->get_results( $sql, ARRAY_A );
	}
}
