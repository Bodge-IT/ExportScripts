<?php
/**
 * Created by PhpStorm.
 * User: garyb
 * Date: 03/10/2016
 * Time: 09:39
 */
include ('db_connect.php');

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=2-hc_owner_property_export.csv');
$output = fopen('php://output', 'w');

if ($data = $db->query("SELECT * FROM hc_owners_properties order by id")) {
	$results = $data->fetch_all(MYSQLI_ASSOC);
	$owner = array();
	foreach ($results as $result) { // Do stuff with each in $results

		$owner_props = '"","' . $result['listingsdb_id'] . '","' . $result['owner_id'] . '"';

		echo $owner_props . "\n";
	}

} else {
	echo mysqli_error($db);
}

?>
