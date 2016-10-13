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
header('Content-Disposition: attachment; filename=2-listingsdbelements_fulldesc_export.csv');
$output = fopen('php://output', 'w');

for ( $propId; $propId < 6254; $propId++ ) {

	if ($data = $db->query("SELECT listingsdbelements_field_name,listingsdbelements_field_value,listingsdb_id FROM hc_en_listingsdbelements 
							WHERE listingsdb_id = $propId 
							AND listingsdbelements_field_name IN ('full_description_en','full_description_es','full_description_de','full_description_fr') 
							ORDER BY listingsdb_id")) {
		$results = $data->fetch_all();

		foreach ($results as $result) {
			switch ($result[0]) {
				case "full_description_en":
					$lang = "en-GB";
					break;
				case "full_description_es":
					$lang = "es-ES";
					break;
				case "full_description_de":
					$lang = "de-DE";
					break;
				case "full_description_fr":
					$lang = "fr-FR";
					break;
			}
			if ($result[1] != null) {
				$result = ',"_JOMRES_CUSTOMTEXT_ROOMTYPE_DESCRIPTION","' . preg_replace( "/\r|\n|\t/", "", $result[1] ) . '","' . $result[2] . '","' . $lang . '",""';
				echo $result . "\r\n";
			}
		}

	} else {
		echo mysqli_error($db);
	}
}
?>
