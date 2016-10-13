<?php
	$output = fopen('php://output', 'w');
function create_csv() {
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=data.csv');
	$output = fopen('php://output', 'w');

	// output the column headings
	fputcsv($output, array('propId', 'filename', 'title'));

	return;
}
?>