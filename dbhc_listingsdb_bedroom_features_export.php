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


$lastinstance 	= '';
$id 			= '';
$fetchid 		= '';
//$propId 		= 6000;
//header('Content-Type: text/csv; charset=utf-8');
//header('Content-Disposition: attachment; filename=listingsdbelements_features_export.csv');
//$output = fopen('php://output', 'w');

for ( $propId = 6000; $propId < 6254; $propId++ ) {

	if ($data  = $db->query("SELECT listingsdbelements_field_name,listingsdbelements_field_value,listingsdb_id,instance 
							FROM hc_en_listingsdbelements 
							WHERE listingsdb_id = $propId 
							AND listingsdbelements_field_name IN ('smallsinglesizenum','singlesizenum','doublesizenum','queensizenum','kingsizenum','singlesofanum','soublesofaenum','cotnum','bunkbedsnum') 
							ORDER BY listingsdb_id, instance")) {
		$results 	= $data->fetch_all(MYSQLI_ASSOC);
		$result 	= array();

		foreach ($results as $result) { // Do stuff with each in $results

			$instance = $result['instance'];

			if ($instance != $lastinstance) {$fieldlist = ''; $count = 0;}

			$fieldname 	= strtoupper($result['listingsdbelements_field_name']);

			if ($result['listingsdbelements_field_value'] != '') {

				$fetchid = $db->query("SELECT id FROM pxwjp_jomres_custom_room_fields_fields WHERE fieldname = '$fieldname'");
				// print_r($fetchid->fetch_field()).'<br/>';

				$id = $fetchid->fetch_row();

				if (empty($fieldlist)) {
					$sep = '';
				} else {
					$sep = ',';
				}

				// $result = ',' . strtoupper($result['listingsdbelements_field_name']) . ',"' . $result['listingsdbelements_field_value'] . '",' . $result['listingsdb_id'] . "\n";
				// $sql =  "UPDATE `pxwjp_jomres_customtable_rooms` SET `custom_fields` = '' WHERE `prop_id` = $propId AND `room_name` = 'Bedroom&nbsp;$instance';

				//echo $result . '<br/>';
				$fieldlist .= $sep . $id[0][0] . ':' . $result['listingsdbelements_field_value'];

				// echo $instance . ' :: ' . $fieldlist . '<br/>';
				//$result = ',' . $result['listingsdb_id'] . ',' . 'Bedroom ' . $instance . ',' . $fieldlist;
				$sql = "UPDATE pxwjp_jomres_customtable_rooms SET custom_fields = '$fieldlist' WHERE prop_id = '$propId' AND room_name LIKE 'Bedroom%$instance'";

				if (mysqli_query($db, $sql)) {
					// echo $result . '<br/>';
					echo 'Record updated successfully.'. '<br/>';
				} else {
					echo 'ERROR: Not able to execute ' . $sql . '<br>' . mysqli_error($db).'<br />';
				}

				//print_r($result . "<br>");

			}

			//if (mysqli_query($db, $fetchid)) {
			//	echo 'Record updated successfully.';
			//} else {
			//	echo "ERROR: Could not able to execute $sql . ' . mysqli_error($db);
			//}

			//var_dump($result) . '<br/>';
			//echo $fieldlist . '<br/>';

			$lastinstance 	= $instance;
		}

	} else {
		echo mysqli_error($db);
	}
}
