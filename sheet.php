<?php

require '/home/antoniodiazduran/vendor/autoload.php';

$client = Google_Spreadsheet::getClient('/home/antoniodiazduran/data/credentials.json');
// Get the sheet instance by sheets_id and sheet name
$sheet = $client->file('1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w')->sheet('Response1');
// Fetch data from remote (or cache)
$sheet->fetch();
// Flush all rows in the sheet
var_dump($sheet->items);

foreach($sheet->items as $prod) {
//	echo $prod[];
}
