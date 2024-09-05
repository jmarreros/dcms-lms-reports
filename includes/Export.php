<?php

namespace dcms\reports\includes;

// Class for the operations of plugin
class Export {

	public function __construct() {
		add_action( 'admin_post_process_export_data_report', [ $this, 'export_data_report' ] );
	}

	// Export data
	public function export_data_report(): void {

		$courses_ids = $_POST['courses_ids'];

		// Check if the data is empty
//		if ( empty( $courses_ids ) ) {
//			return;
//		}

		$courses_ids = explode( ',', $courses_ids );

		$db   = new Database();
		$rows = $db->get_students_by_courses( $courses_ids );

		$data   = [];
		$data[] = [ 'ID', 'Nombre', 'Correo', 'Teléfono', 'Cursos' ];
		foreach ( $rows as $row ) {
			$data[] = [ $row['ID'], $row['user_name'], $row['user_email'], $row['user_phone'], $row['courses'] ];
		}

		$this->download_send_headers( "lista_estudiantes_" . date( "Y-m-d" ) . ".csv" );
		echo $this->array_to_csv( $data );
		die();
	}

	private function array_to_csv( array &$array ) {
		if ( count( $array ) == 0 ) {
			return null;
		}
		ob_start();
		$df = fopen( "php://output", 'w' );
		foreach ( $array as $row ) {
			fputcsv( $df, $row );
		}
		fclose( $df );

		return ob_get_clean();
	}

	private function download_send_headers( $filename ): void {
		// disable caching
		$now = gmdate( "D, d M Y H:i:s" );
		header( "Expires: Tue, 03 Jul 2001 06:00:00 GMT" );
		header( "Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate" );
		header( "Last-Modified: {$now} GMT" );

		// force download
		header( "Content-Type: application/force-download" );
		header( "Content-Type: application/octet-stream" );
		header( "Content-Type: application/download" );

		// disposition / encoding on response body
		header( "Content-Disposition: attachment;filename={$filename}" );
		header( "Content-Transfer-Encoding: binary" );
	}

}