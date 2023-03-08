<?php

class Shortage extends Controller {

    public function all() {
	 	$data[] = $this->GSheetsRead('pw.shortage','1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','shortage');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
	        $this->f3->set('customer','yes');
		$this->f3->set('t1',count($data[0])-50);
                $this->f3->set('layout','layout.htm');
		$this->f3->set('menu','nav_pw.htm');
		$this->f3->set('headers','shortage/headers.htm');
                $this->f3->set('fields','shortage/fields.htm');
                $this->f3->set('content','shortage/list.htm');
    }
    public function form() {
                $this->f3->set('breadcrumbs','shortage');
                $this->f3->set('mode','create');
                $this->f3->set('layout','layout.htm');
		$this->f3->set('menu','nav_pw.htm');
		$this->f3->set('content','shortage/form.htm');
    }
    public function insertRow() {
		// Gathering data from form
		$fields[] = $this->f3->get('POST');
		// Creating array to insert into Google Sheets
		date_default_timezone_set("America/Mexico_City");
		$row = array(
                        'DateTime'=>date('m/d/Y H:i:s'),
                        'Customer'=>$fields[0]['customer'],
                        'PartNumber'=>strtoupper($fields[0]['partnumber']),
                        'Harness'=>$fields[0]['harness'],
                        'Quantity'=>$fields[0]['quantity'],
                        'Unit'=>$fields[0]['unit'],
                	);
		// Inserting data into Google Sheets
		$data[] = $this->GSheetsInsert('pw.shortage',$row,'1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
		// Displaying new data
		$this->f3->reroute('/shortagelist');

    }
}
?>

