<?php
/**
 * Created by PhpStorm.
 * User: garyb
 * Date: 16/09/2015
 * Time: 12:19
 */
include ('db_connect.php');
// include ('create_csv.php');

$propId = 6000;
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=2-jomres_customtext_images_titles.csv');
$output = fopen('php://output', 'w');

// fputcsv($output, array('uid','constant','customtext','property_uid','language','reserved'));

while ($propId < 6254 ) {

	if ($data = $db->query("SELECT h.listingsdb_id, h.listingsimages_file_name, h.listingsimages_caption_en, h.listingsimages_caption_es, h.listingsimages_caption_de, h.listingsimages_caption_fr, j.id, j.property_id, j.image_name
							FROM hc_en_listingsimages AS h
							INNER JOIN pxwjp_jomres_jpd_images_title AS j
							WHERE h.listingsdb_id = j.property_id
							AND h.listingsimages_file_name = j.image_name
							AND h.listingsdb_id = $propId")) {
		if ($data->num_rows)
		{
			$results = $data->fetch_all(MYSQLI_ASSOC);

			foreach($results as $result) {
				echo '"","_JOMRES_CUSTOMTEXT_IMAGE_TITLE_' . $result['id'] . '","' . $result['listingsimages_caption_en'] . '",' . $result['listingsdb_id'] . ',"en_GB",""' . "\r\n";
				echo '"","_JOMRES_CUSTOMTEXT_IMAGE_TITLE_' . $result['id'] . '","' . $result['listingsimages_caption_es'] . '",' . $result['listingsdb_id'] . ',"es_ES",""' . "\r\n";
				echo '"","_JOMRES_CUSTOMTEXT_IMAGE_TITLE_' . $result['id'] . '","' . $result['listingsimages_caption_de'] . '",' . $result['listingsdb_id'] . ',"de_DE",""' . "\r\n";
				echo '"","_JOMRES_CUSTOMTEXT_IMAGE_TITLE_' . $result['id'] . '","' . $result['listingsimages_caption_fr'] . '",' . $result['listingsdb_id'] . ',"fr_FR",""' . "\r\n";
			}

			// print_r($result);

		}

		$propId++;
	} else {
		echo mysql_error();
	}
}
?>
