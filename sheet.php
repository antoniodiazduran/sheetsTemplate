<?php

require '/vendor/autoload.php';

$client = Google_Spreadsheet::getClient('data/***.json');
// Get the sheet instance by sheets_id and sheet name
$sheet = $client->file('your-google-sheet-id')->sheet('your-spreadsheet-tab');
// Fetch data from remote (or cache)
$sheet->fetch();
// Flush all rows in the sheet
var_dump($sheet->items);

foreach($sheet->items as $prod) {
//	echo $prod[];
}
