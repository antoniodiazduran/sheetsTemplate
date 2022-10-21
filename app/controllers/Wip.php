<?php

class Wip extends Controller {

    public function tlist() 
    {
                $data[] = $this->GSheetsRead('pw.wip');
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('traveler',$this->f3->get('PARAMS.traveler'));
                $this->f3->set('headers','wipheaders.htm');
                $this->f3->set('fields','wipfields.htm');
                $this->f3->set('field','traveler');
                $this->f3->set('details',$data);
                $this->f3->set('content','list.htm');
    }
    public function plist() 
    {
                $data[] = $this->GSheetsRead('pw.wip');
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('partnumber',$this->f3->get('PARAMS.partnumber'));
                $this->f3->set('headers','wipheaders.htm');
                $this->f3->set('fields','wipfields.htm');
                $this->f3->set('field','partnumber');
                $this->f3->set('details',$data);
                $this->f3->set('content','list.htm');
    }
    public function clist() 
    {
                $data[] = $this->GSheetsRead('pw.wip');
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('customer',$this->f3->get('PARAMS.customer'));
                $this->f3->set('headers','wipheaders.htm');
                $this->f3->set('fields','wipfields.htm');
                $this->f3->set('field','customer');
                $this->f3->set('details',$data);
                $this->f3->set('content','list.htm');
    }
    public function alist() {
		$data[] = $this->GSheetsRead('pw.wip');
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('area',$this->f3->get('PARAMS.area'));
                $this->f3->set('details',$data);
                $this->f3->set('field','area');
                $this->f3->set('headers','wipheaders.htm');
                $this->f3->set('fields','wipfields.htm');
                $this->f3->set('content','list.htm');
    }
    public function all() {
	 	$data[] = $this->GSheetsRead('pw.wip');
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
		$this->f3->set('t1',count($data[0])-30);
                $this->f3->set('headers','wipheaders.htm');
                $this->f3->set('fields','wipfields.htm');
                $this->f3->set('content','list.htm');
    }
    public function form() {
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('mode','create');
                $this->f3->set('content','wipform.htm');
    }
    public function insertRow() {
		// Gathering data from form
		$fields[] = $this->f3->get('POST');
		// Creating array to insert into Google Sheets
		date_default_timezone_set("America/Mexico_City");
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
		$data[] = $this->GSheetsInsert('pw.wip',$row);
		// Displaying new data
		$this->f3->reroute('/wip');

//		$this->f3->set('details',$data);
//                $this->f3->set('breadcrumbs','wip');
//		$this->f3->set('field','all');
//                $this->f3->set('headers','wipheaders.htm');
//                $this->f3->set('fields','wipfields.htm');
//		$this->f3->set('content','list.htm');
    }
}
?>

