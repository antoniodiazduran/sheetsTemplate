<?php

class Wip extends Controller {

    public function headerList() {
		$field = $this->f3->get('POST');
		$param = $this->f3->get('PARAMS');
                  foreach (getallheaders() as $name => $value) {
                           echo "$name: $value\n";
                  }
		var_dump($field);
		var_dump($param);
		exit;
    }
    public function tlist() 
    {
                $data[] = $this->GSheetsRead('pw.wip','1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('traveler',$this->f3->get('PARAMS.traveler'));
                $this->f3->set('layout','layout.htm');
                $this->f3->set('menu','nav_pw.htm');
		$this->f3->set('headers','wip/headers.htm');
                $this->f3->set('fields','wip/fields.htm');
                $this->f3->set('field','traveler');
                $this->f3->set('details',$data);
                $this->f3->set('content','wip/list.htm');
    }
    public function plist() 
    {
                $data[] = $this->GSheetsRead('pw.wip','1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('partnumber',$this->f3->get('PARAMS.partnumber'));
                $this->f3->set('layout','layout.htm');
                $this->f3->set('menu','nav_pw.htm');
		$this->f3->set('headers','wip/headers.htm');
                $this->f3->set('fields','wip/fields.htm');
                $this->f3->set('field','partnumber');
                $this->f3->set('details',$data);
                $this->f3->set('content','wip/list.htm');
    }
    public function clist() 
    {
                $data[] = $this->GSheetsRead('pw.wip','1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('customer',$this->f3->get('PARAMS.customer'));
                $this->f3->set('layout','layout.htm');
                $this->f3->set('menu','nav_pw.htm');
		$this->f3->set('headers','wip/headers.htm');
                $this->f3->set('fields','wip/fields.htm');
                $this->f3->set('field','customer');
                $this->f3->set('details',$data);
                $this->f3->set('content','wip/list.htm');
    }
    public function alist() {
		$data[] = $this->GSheetsRead('pw.wip','1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('area',$this->f3->get('PARAMS.area'));
                $this->f3->set('details',$data);
                $this->f3->set('layout','layout.htm');
                $this->f3->set('menu','nav_pw.htm');
		$this->f3->set('field','area');
                $this->f3->set('headers','wip/headers.htm');
                $this->f3->set('fields','wip/fields.htm');
                $this->f3->set('content','wip/list.htm');
    }
    public function wlog() {
	 	$data[] = $this->GSheetsRead('pw.log','1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','wlog');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
	        $this->f3->set('customer','yes');
		$this->f3->set('t1',count($data[0])-350);
                $this->f3->set('layout','layout.htm');
                $this->f3->set('menu','nav_pw.htm');
		$this->f3->set('headers','wip/headers.htm');
                $this->f3->set('fields','wip/fields.htm');
                $this->f3->set('content','wip/list.htm');
    }
    public function all() {
	 	$data[] = $this->GSheetsRead('pw.wip','1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','wip');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
	        $this->f3->set('customer','no');
		$this->f3->set('t1',count($data[0])-50);
                $this->f3->set('layout','layout.htm');
                $this->f3->set('menu','nav_pw.htm');
		$this->f3->set('headers','wip/headers.htm');
                $this->f3->set('fields','wip/fields.htm');
                $this->f3->set('content','wip/list.htm');
    }
    public function form() {
                $this->f3->set('breadcrumbs','wip','1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
                $this->f3->set('mode','create');
                $this->f3->set('layout','layout.htm');
                $this->f3->set('menu','nav_pw.htm');
		$this->f3->set('content','wip/form.htm');
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
		$data[] = $this->GSheetsInsert('pw.wip',$row,'1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w');
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

