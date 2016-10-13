<?php
/**
 * Created by PhpStorm.
 * Exports English image titles
 * User: garyb
 * Date: 16/09/2015
 * Time: 12:19
 */
include ('db_connect.php');
// include ('flatten_array.php');
// include ('create_csv.php');


$propId 		= 6000;
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=2-listingsdbelements_features_export.csv');
$output = fopen('php://output', 'w');

for ( $propId; $propId < 6254; $propId++ ) {

	if ($data = $db->query("SELECT listingsdbelements_field_name,listingsdbelements_field_value,listingsdb_id FROM hc_en_listingsdbelements 
							WHERE listingsdb_id = $propId 
							AND listingsdbelements_field_name IN ('propertysize','plotsize','breakagesdeposit','entrytime','exittime') 
							ORDER BY listingsdb_id")) {
		$results = $data->fetch_all();

		foreach ($results as $result) {
			if ($result[1] != null) {
				$result = ',' . $result[2] . ',"' . strtoupper($result[0]) . '","' . $result[1] . '"';
				echo $result . "\r\n";
			}
		}

	} else {
		// echo mysqli_error($db);
	}
}
?>
