<?php
/**
 * Created by PhpStorm.
 * User: garyb
 * Date: 06/10/2016
 * Time: 22:07
 */

include ('db_connect.php');
// include ('flatten_array.php');
// include ('create_csv.php');


$propId 		= 6000;
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=jomres_room_export.csv');
$output = fopen('php://output', 'w');

while ($propId && $propId >= 6000 && $propId < 6254) {

	if ($data = $db->query("SELECT a.listingsdb_id,a.listingsdb_title,b.class_id FROM hc_en_listingsdb AS a 
							LEFT JOIN hc_classlistingsdb AS b
							ON a.listingsdb_id = b.listingsdb_id 
							WHERE a.listingsdb_id = $propId ORDER BY listingsdb_id")) {

		$results = $data->fetch_all(MYSQLI_ASSOC);
		foreach ($results as $result) {
			$xData = $db->query("SELECT listingsdbelements_field_value FROM hc_en_listingsdbelements 
								WHERE listingsdb_id = $propId
								AND listingsdbelements_field_name = 'sleeps'");
			$sleeps = $xData->fetch_row();
// print_r($sleeps);
			$result = '"","' . $result['class_id'] . '","' . $result['listingsdb_id'] . '","","' . $result['listingsdb_title'] . '","1","","' . $sleeps[0] .'",""';

			echo $result . "\r\n";
		}
		$propId++;
	} else {
		echo mysql_error();
	}
}
?>
