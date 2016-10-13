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
header('Content-Disposition: attachment; filename=2-jomres_rates_export.csv');
$output = fopen('php://output', 'w');

while ( $propId < 6254 ) {

	if ($data = $db->query("SELECT * FROM hc_rental_prices
							WHERE listingsdb_id = $propId 
							AND period_end_date > '2016-01-01 00:00:00'
							ORDER BY listingsdb_id")) {
		$results = $data->fetch_all();
		// print_r($results);
		foreach ($results as $result) {
			$xData = $db->query("SELECT room_classes_uid, max_people FROM pxwjp_jomres_rooms
								WHERE propertys_uid = $propId");
				$roomUid = $xData->fetch_row();
				// echo $roomUid[0] . '<br />';
			$specialOffer = ($result[5] == '0' ? "no" : "yes");
			$result = ',"Default","Default","' . $result[1] . '","' . $result[2] . '","' . number_format(($result[3]/7),3,".",",") . '","' . number_format(($result[4]/7),3,".",",")
					. '","' . $specialOffer . '","' . $result[6] . '","100","1","' . $roomUid[1] . '","' . $roomUid[0] . '","1","0","1","0","' . $result[1] . '","' . $result[2] . '","6","0","100",' . $result[7];
			echo $result . "\r\n";
		}
		$propId++;
	} else {
		echo mysqli_error($db);
	}
}
?>
