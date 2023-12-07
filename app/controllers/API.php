<?php

class API extends Controller {

    public function stock() {
		// Gathering data from form
		//$fields[] = $this->f3->get('POST');
		$json = file_get_contents('php://input');
		$data = json_decode($json);

echo "json".$json."\r\n";
		$headers = apache_request_headers();

$jobj = json_decode(json_encode($headers));
echo "user".$jobj->{'User-Agent'}."\r\n";
echo "Auth".$jobj->{'Authorization'}."\r\n";

foreach ($headers as $header => $value) {
//	if (stripos($header,"Authorization") === 0) {
//		echo $header."::".$value."\r\n";
//	}
}
exit;
		// Creating array to insert into Google Sheets
		/*date_default_timezone_set("America/Mexico_City");
		$row = array(
                        'DateTime'=>date('m/d/Y H:i:s'),
                        'Customer'=>$fields[0]['customer'],
                        'Area'=>$fields[0]['area'],
                        'PartNumber'=>$fields[0]['partnumber'],
                        'Traveler'=>$fields[0]['traveler'],
                        'Quantity'=>$fields[0]['quantity'],
                        'Notes'=>$fields[0]['notes']
                	);
		// Inserting data into Google Sheets
		$data[] = $this->GSheetsInsert('pw.wip',$row,'1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
		// Displaying new data
		$this->f3->reroute('/wip');*/
    }
}
?>

