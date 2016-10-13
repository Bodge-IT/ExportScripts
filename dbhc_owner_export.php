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

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=hc_owner_export.csv');
$output = fopen('php://output', 'w');

	if ($data = $db->query("SELECT * FROM hc_owners order by id")) {
		$results = $data->fetch_all(MYSQLI_ASSOC);
		$owner = array();
		foreach ($results as $result) { // Do stuff with each in $results
			$owner 	= $result['id'] . ',"' . $result['owner_dni'] . '","' . $result['owner_name'] . '","' . $result['owner_lang'] . '","' . $result['owner_address'] . '","'
					. $result['meet_greet_email'] . '","' . $result['other_email'] . '","' . $result['owner_tel'] . '","' . $result['owner_mobile'] . '","' . $result['account_bank_name'] . '","'
					. $result['account_bank_office'] . '","' . $result['account_name'] . '","' . $result['account_bank_address'] . '","' . $result['account_iban'] . '","' . $result['account_bic'] . '","'
					. $result['notes'] . '","1","' . $result['owner_email'] . '","","' . $result['entry_date'] . '",""';

			echo $owner . "\r\n";
		}

	} else {
		echo mysqli_error($db);
	}
?>
