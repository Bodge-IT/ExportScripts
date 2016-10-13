<?php
/**
 * Created by PhpStorm.
 * Exports English image titles
 * User: garyb
 * Date: 16/09/2015
 * Time: 12:19
 */
include ('db_connect.php');
include ('flatten_array.php');
// include ('create_csv.php');

$propId = 6000;
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=2-listingsdb_property_export.csv');
$output = fopen('php://output', 'w');

while ($propId && $propId >= 6000 && $propId < 6254) {

    if ($data = $db->query("SELECT a.listingsdb_id,a.listingsdb_title,a.listingsdb_active,a.listingsdb_last_modified,a.listingsdb_title_en,a.listingsdb_displayto,b.class_id 
							FROM hc_en_listingsdb AS a 
							LEFT JOIN hc_classlistingsdb AS b 
							ON a.listingsdb_id = b.listingsdb_id 
							WHERE a.listingsdb_id = $propId 
							ORDER BY listingsdb_id")) {
		$results 		= $data->fetch_all(MYSQLI_ASSOC);
		foreach($results as $result) {
			$published 	= ($result['listingsdb_active'] == "yes" ? 1 : 0);
			$displayTo 	= str_replace('||',",",$result['listingsdb_displayto']);
			$xData 		= $db->query("SELECT listingsdbelements_field_value FROM hc_en_listingsdbelements
									  WHERE listingsdbelements_field_name 
									  IN ('latitude','longitude','address','town','postcode','locationnotes_en','sortcategory')
									  AND listingsdb_id = $propId 
									  ORDER BY FIELD(listingsdbelements_field_name, 'latitude','longitude','address','town','postcode','locationnotes_en','sortcategory')");

			$xResults 	= $xData->fetch_all(MYSQLI_ASSOC);
			// print_r($xResults);

			$lat 		= $xResults[0]['listingsdbelements_field_value'];
			$long 		= $xResults[1]['listingsdbelements_field_value'];
			$street 	= $xResults[2]['listingsdbelements_field_value'];
			$town 		= $xResults[3]['listingsdbelements_field_value'];
			$postCode 	= $xResults[4]['listingsdbelements_field_value'];
			$locNotes 	= $xResults[5]['listingsdbelements_field_value'];
			$order	 	= $xResults[6]['listingsdbelements_field_value'];
			$result 	= $result['listingsdb_id'] . ',"' . $result['listingsdb_title'] . '","' . $street . '","' . $town . '",,"' . $displayTo . '",,' . $order . ',"es","' . $postCode . '",,,,,,"' . $result['listingsdb_title_en'] . '","","' . preg_replace( "/\r|\n|\t/", "", $locNotes) . '","","","","","","' . $published . '","0","0","' . $result['class_id'] . '","","' . $lat . '","' . $long . '","","","","","1","",""';
			//echo 'Result: ' . $result;
			echo $result . "\r\n";
		}

		$propId++;
    } else {
        echo mysql_error();
    }
}
?>
