<?php
/**
 * Created by PhpStorm.
 * Exports English image titles
 * User: garyb
 * Date: 16/09/2015
 * Time: 12:19
 */

//include ('flatten_array.php');
// include ('create_csv.php');

include ('db_connect.php');

$propId = 6000;
//header('Content-Type: text/csv; charset=utf-8');
//header('Content-Disposition: attachment; filename=2-listingsdbelements_features_export.csv');
//$output = fopen('php://output', 'w');

for ( $propId; $propId < 6254; $propId++ ) {
	if ($data = $db->query("SELECT listingsdbelements_field_name,listingsdbelements_field_value,listingsdb_id FROM hc_en_listingsdbelements 
							WHERE listingsdb_id = $propId 
							AND listingsdbelements_field_name IN ('distancebeach','aircon','internetaccessavailable','internetaccesschargeable','petsallowed','smokingallowed','winterletsavailable') 
							ORDER BY FIELD(`listingsdbelements_field_name`, 'distancebeach','aircon','internetaccessavailable','internetaccesschargeable','petsallowed','smokingallowed','winterletsavailable'), listingsdb_id")) {
		$results = $data->fetch_all(MYSQLI_ASSOC);
		$featuresList = '';
		$fetchid = '';
		$sep = '';
		$result = array();
//		var_dump ($results);
		foreach ($results as $result) { // Do stuff with each in $results
//			var_dump ($result);
			$fname 	= $result['listingsdbelements_field_name'];
			$fvalue = $result['listingsdbelements_field_value'];
			$sep 	= ',';
				switch ($fname) {            // get ids from dbase
					case "distancebeach" :
						if ($fvalue != null) {
							if ($fvalue == "0-3km") {
								$fetchid = '218';
								$featuresList .= $fetchid . $sep;
							} elseif ($fvalue == "3-10km") {
								$fetchid = '219';
								$featuresList .= $fetchid . $sep;
							} elseif ($fvalue == "10km+") {
								$fetchid = '220';
								$featuresList .= $fetchid . $sep;
							} elseif ($fvalue == "") {
							}
						}
					break;

					case "aircon" :
						if ($fvalue != '') {
							if ($fvalue == "Yes") {
								$fetchid = '214';
								$featuresList .= $fetchid . $sep;
							} elseif ($fvalue == "No") {
							}
						}
					break;

					case "internetaccessavailable" :
						if ($fvalue != null) {
							if ($fvalue == "WiFi") {
								$fetchid = '77';
								$featuresList .= $fetchid . $sep;
							} elseif ($fvalue == "Ethernet Cable") {
								$fetchid = '47';
								$featuresList .= $fetchid . $sep;
							} elseif ($fvalue == "") {
							}
						}
					break;

					case "petsallowed" :
						if ($fvalue != '') {
							if ($fvalue == "Yes") {
								$fetchid = '224';
								$featuresList .= $fetchid . $sep;
							} elseif ($fvalue == "No") {
							}
						}
					break;

					case "winterletsavailable" :
						if ($fvalue != '') {
							if ($fvalue == "Yes") {
								$fetchid = '227';
								$featuresList .= $fetchid . $sep;
							} elseif ($fvalue == "No") {
							}
						}
					break;

					case "smokingallowed" :
						if ($fvalue != '') {
							if ($fvalue == "Yes") {
								$fetchid = '228';
								$featuresList .= $fetchid . $sep;
							} elseif ($fvalue == "No") {
							}
						}
					break;

					case "internetaccesschargeable" :
						if ($fvalue != '') {
							if ($fvalue == "Yes") {
								$fetchid = '233';
								$featuresList .= $fetchid . $sep;
							} elseif ($fvalue == "No") {
							}
						}
					break;
					}
				}

		$sql =  "UPDATE `pxwjp_jomres_propertys` SET `property_features` = '$featuresList' WHERE `propertys_uid` = '$propId'";

		if( mysqli_query($db, $sql)) {
			echo $propId.': '. $featuresList . '<br />';
		} else {
			echo "ERROR: Could not able to execute $sql . " . mysqli_error($db);
		}

	} else {
		echo mysqli_error($db);
	}
}
?>
