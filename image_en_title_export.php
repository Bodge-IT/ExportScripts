<?php
/**
 * Created by PhpStorm.
 * User: garyb
 * Date: 16/09/2015
 * Time: 12:19
 */
include ('db_connect.php');
include ('create_csv.php');

$propId = 6000;
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=2-jomres_jpd_images_export.csv');
$output = fopen('php://output', 'w');

fputcsv($output, array());

while ($propId < 6254 ) {

	if ($data = $db->query("SELECT listingsdb_id, listingsimages_file_name, listingsimages_caption_en 
							FROM hc_en_listingsimages 
							WHERE listingsdb_id = $propId 
							ORDER BY listingsimages_rank")) {
		if ($data->num_rows)
		{
			$results = $data->fetch_all(MYSQLI_ASSOC);
			foreach($results as $result) {
				$result = '"",' . $result['listingsdb_id'] . ',"' . $result['listingsimages_file_name'] . '","' . $result['listingsimages_caption_en'];
				echo $result . "\r\n";
			}

			//  print_r($results);

		}

		$propId++;
	} else {
		echo mysql_error();
	}
}
?>
