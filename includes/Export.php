<?php

namespace dcms\reports\includes;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Class for the operations of plugin
class Export {

	public function __construct() {
		add_action( 'admin_post_process_export_data_report', [ $this, 'export_data_report' ] );
	}

	// Export data
	public function export_data_report(): void {

		$courses_ids = $_POST['courses_ids'];

		// Check if the data is empty
		if ( empty( $courses_ids ) ) {
			return;
		}

		$courses_ids = explode( ',', $courses_ids );

		$db = new Database();

		$spreadsheet = new Spreadsheet();
		$writer      = new Xlsx( $spreadsheet );

		$sheet = $spreadsheet->getActiveSheet();

		// Headers
		$sheet->setCellValue( 'A1', 'Identificativo' );
		$sheet->setCellValue( 'B1', 'Nombre' );
		$sheet->setCellValue( 'C1', 'Correo' );
		$sheet->setCellValue( 'D1', 'Teléfono' );

		// Get data from table
		$data = $db->get_students_by_courses( $courses_ids );

		$i = 2;
		foreach ( $data as $row ) {
			$sheet->setCellValue( 'A' . $i, $row['ID'] );
			$sheet->setCellValue( 'B' . $i, $row['user_name'] );
			$sheet->setCellValue( 'C' . $i, $row['user_email'] );
			$sheet->setCellValue( 'D' . $i, $row['user_phone'] );
			$i ++;
		}

		$filename = 'lista_estudiantes.xlsx';

		header( 'Content-Type: application/vnd.ms-excel' );
		header( 'Content-Disposition: attachment;filename=' . $filename );
		header( 'Cache-Control: max-age=0' );
		$writer->save( 'php://output' );
	}

}