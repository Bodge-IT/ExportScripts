<?php
/**
 * Created by PhpStorm.
 * Exports English image titles
 * User: garyb
 * Date: 16/09/2015
 * Time: 12:19
 * version 0.9.0
 */
include ('db_connect.php');
// include ('flatten_array.php');
// include ('create_csv.php');

//header('Content-Type: text/csv; charset=utf-8');
//header('Content-Disposition: attachment; filename=2-listingsdbelements_features_Facilities_export.csv');
//$output = fopen('php://output', 'w');

$order  	= '';
$cat 		= '';
$fname 		= '';
$tvStore 	= '';
$cStore 	= '';
for ( $propId = 6157; $propId < 6159; $propId++ ) {

	if ($data 	= $db->query("SELECT listingsdbelements_field_name,listingsdbelements_field_value,listingsdb_id,instance 
								FROM hc_en_listingsdbelements 
								WHERE listingsdb_id = $propId 
								AND listingsdbelements_field_name IN ('bathroomfeatures','suitable','bedroomfeatures','parking','kitchenfeatures','outdoordiningfeatures','livingroomtvchannels','livingroomtvsize','livingroomfeatures','gardenfeatures','otherrooms') 
								ORDER BY FIELD('bathroomfeatures','suitable','bedroomfeatures','parking','kitchenfeatures','outdoordiningfeatures','livingroomtvchannels','livingroomtvsize','livingroomfeatures','gardenfeatures','otherrooms'),listingsdb_id")) {
		$results = $data->fetch_all(MYSQLI_ASSOC);

		foreach ($results as $result) { // Do stuff with each in $results
			$instance = trim($result['instance']);
			//var_dump($result);
			//$test = strpos($result['listingsdbelements_field_value'], '||');
			//if ($test === false ) {  // Are we working with checkbox features or something else
			//	echo 'false: No features as such or only 1';

			//} else { // Lets Convert Txt Features to IDs
				$values = preg_replace("/\\|\\|/", ",", $result['listingsdbelements_field_value']);
				$values = explode(",", $values);    // Split features into array

				$fnum = count($values);
				//echo 'fnum: ' . $fnum . '<br>';
				$featuresList = '';
				for ($i=0; $i<$fnum; $i++) { 	// loop through each feature in array

					$feature = mysqli_escape_string($db, $values[$i]);
					// echo $feature . ' ';
					//if (strpos($feature, '/')) {
					//	$feature = substr($feature, 0, strpos($feature, '/'));
					//}
					//echo $feature;
					$sep 	= ',';
					$fname 	= $result['listingsdbelements_field_name'];

					switch ($fname) {            // get ids from dbase

						case "bathroomfeatures" :
							$roomType 	= '13';
							$fetchid 	= mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '%$feature%' AND cat_id = '$roomType'");
							if ($id 	= mysqli_fetch_row($fetchid)) {
								$featuresList .= $id[0];
								if ($i < $fnum-1) {
									$featuresList .= $sep;
								}
							} else {
								echo 'Not Found' . '<br>';
							}
							if ($instance == 0 ) {
								$cat = "Bathroom";
							}
							$cat 	= "Bathroom" . ' ' . $instance;
							$order 	= 80 + ((int) $instance * 10);
							break;

						case "suitable" :
							$roomType	= '17';
							$fetchid 	= mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '%$feature%' AND cat_id = '$roomType'");
							if ($id 	= mysqli_fetch_row($fetchid)) {
								$featuresList .= $id[0];
								if ($i < $fnum-1) {
									$featuresList .= $sep;
								}
							} else {
								echo 'Not Found' . '<br>';
							}
							$cat 	= "Suitable for";
							$order 	= 150;
							break;

						case "parking" :
							$roomType 	= '14';
							$fetchid 	= mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '%$feature%' AND cat_id = $roomType");
							if ($id 	= mysqli_fetch_row($fetchid)) {
								$featuresList .= $id[0];
								if ($i < $fnum - 1) {
									$featuresList .= $sep;
								}
							} else {
								echo ' Not Found' . '<br>';
							}
							$cat 	= "Parking";
							$order 	= 130;
							break;

						case "kitchenfeatures" :
							$roomType 	= '11';
							$fetchid 	= mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '%$feature%' AND cat_id = '$roomType'");
							if ($id 	= mysqli_fetch_row($fetchid)) {
								$featuresList .= $id[0];
								if ($i < $fnum - 1) {
									$featuresList .= $sep;
								}
							} else {
								echo ' Not Found' . '<br>';
							}
							$cat 	= "Kitchen";
							$order 	= 10;
							break;

						case "livingroomtvchannels" :
							$fetchid 	 = mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '%$feature%' AND cat_id = '5'");
							if ($id 	 = mysqli_fetch_row($fetchid)) {
								$cStore .= $id[0];
								$cStore .= $sep;
							}
							//echo "store: " . $flStore;
							break;

						case "livingroomtvsize" :
							$fetchid 	 = mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '$feature%' AND cat_id = '5'");
							if ($id 	 = mysqli_fetch_row($fetchid)) {
								$tvStore = $id[0];
								$tvStore .= $sep;
							}
							//echo "store: " . $flStore;
							break;

						case "livingroomfeatures" :
							$roomType = '5';
							if (isset($cStore) AND $cStore != '') {
								// echo $flStore;
								$featuresList .= $cStore;
							}
							if (isset($tvStore) AND $tvStore != '') {
							// echo $flStore;
								$featuresList .= $tvStore;
							}
							$fetchid 	= mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '%$feature%' AND cat_id = '$roomType'");
							echo "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '%$feature%' AND cat_id = '$roomType'<br>";
							if ($id 	= mysqli_fetch_row($fetchid)) {
								$featuresList .= $id[0];
								if ($i < $fnum-1) {
									$featuresList .= $sep;
								}
							} else {
								echo 'Not Found' . '<br>';
							}
							$cat		= "Living Room";
							$order 		= 20;
							$tvStore 	= '';
							$cStore 	= '';
							break;

						case "gardenfeatures" :
							$roomType 	= '18';
							$fetchid 	= mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '%$feature%' AND cat_id = '$roomType'");
							if ($id 	= mysqli_fetch_row($fetchid)) {
								$featuresList .= $id[0];
								if ($i < $fnum-1) {
									$featuresList .= $sep;
								}
							} else {
								echo 'Not Found' . '<br>';
							}
							$cat 	= "Garden";
							$order 	= 110;
							break;

						case "otherrooms" :
							$roomType 	= '16';
							$fetchid 	= mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '%$feature%' AND cat_id = '$roomType'");
							if ($id 	= mysqli_fetch_row($fetchid)) {
								$featuresList .= $id[0];
								if ($i < $fnum-1) {
									$featuresList .= $sep;
								}
							} else {
								echo 'Not Found' . '<br>';
							}
							$cat 	= "Other Rooms";
							$order 	= 140;
							break;

						case "bedroomfeatures" :
							$roomType 	= '12';
							$fetchid 	= mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc LIKE '%$feature%' AND cat_id = '$roomType'");
							if ($id 	= mysqli_fetch_row($fetchid)) {
								$featuresList .= $id[0];
								if ($i < $fnum-1) {
									$featuresList .= $sep;
								}
							} else {
								echo 'Not Found' . '<br>';
							}
							if ($instance == 0 ) {
								$cat = "Bedroom";
							}
							$cat 	= "Bedroom" . ' ' . $instance;
							$order 	= 30 + ((int) $instance * 10);
							break;

						case "outdoordiningfeatures" :
							$roomType = '19';
							$fetchid = mysqli_query($db, "SELECT hotel_features_uid FROM pxwjp_jomres_hotel_features WHERE hotel_feature_full_desc like '%$feature%' AND cat_id = '$roomType'");
							if ($id = mysqli_fetch_row($fetchid)) {
								$featuresList .= $id[0];
								if ($i < $fnum-1) {
									$featuresList .= $sep;
								}
							} else {
								echo 'Not Found' . '<br>';
							}
							$cat 	= "Outdoor Dining Features";
							$order 	= 120;
							break;
					}

				} // end feature array iteration
			// echo $fname;
				if ($fname != "livingroomtvchannels" AND $fname != "livingroomtvsize") {
					$result = ',' . (int)$result['listingsdb_id'] . ',"' . $cat . '",' . $roomType . ',"' . $featuresList . '","","' . (int)$order . '"';
					// echo $result . (isset($output) ? "\r\n" : "<br>");

				}
				// $sql =  "INSERT INTO `pxwjp_jomres_customtable_rooms` (`id`, `prop_id`, `room_name`, `room_type`, `features` , `custom_fields` , `display_order`) VALUES ('', '$propId', '$cat', '$roomType', '$featuresList', '', '$order')";

				//if( mysqli_query($db, $sql)) {
				//	echo "Records added successfully.";
				//} else {
				//	echo "ERROR: Could not able to execute $sql . " . mysqli_error($db);
				//}
		}

	} else {
		echo mysqli_error($db);
	}
}
