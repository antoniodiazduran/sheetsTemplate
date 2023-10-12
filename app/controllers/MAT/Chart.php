<?php

namespace MAT;

class Chart extends \Controller {

    public function ajax() {
		$this->f3->set('layout','flat.htm');
                $this->f3->set('content','materials/tableajax.htm');
    }
    public function duedate() {
                $this->f3->set('breadcrumbs','/mat/chart/duedate');
	        $this->f3->set('navs','yes');
		$this->f3->set('isMobile',parent::isMobile());
	        $this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('layout','charts.htm');
                $this->f3->set('content','materials/duedate.htm');
    }
    public function chartdata01() {
		// Creating object for database
		$clist = new \Customer($this->db);

		// Customer name's array
		$data[] = $clist->customerlist();
		// Transactions by customer
		$event[] = $clist->eventlist();

		$ctx = $event[0][0]['customer'];
		$cht = array();
		// Gathering customer names
		foreach ($data[0] as $ikey => $ival) {
		  $cht += [ $ival['custx'] => ''];
		}
		$wkx = "";
		$vlx = "";
		$evt = 0;
		$tot = array();

		// Creating the json variables
		foreach ($event[0] as $ikey => $ival) {
 			if ($ctx <> $ival['customer']) {
		  		$cht[$ctx] = $wkx."::".$vlx.$evt;
				$tot[$ctx] = $evt;
				$wkx="";
				$vlx="";
				$ctx = $ival['customer'];
				$evt = 0;
		 	}
				$wkx = $wkx.$ival['weekx'].",";
				$vlx = $vlx.$ival['events'].",";
				$evt = $evt + $ival['events'];
		}
		// Adding last value
	  	$cht[$ctx] = $wkx."::".$vlx.$evt;
		$tot[$ctx] = $evt;

		$this->f3->set('custx',$data[0]);
		$this->f3->set('chart',$cht);
		$this->f3->set('total',$tot);
		$this->f3->set('maxbar',50);
		$this->f3->set('canvas_width',400);
		$this->f3->set('canvas_height',240);
		$this->f3->set('customer_route','/mat/chart/cust');
                $this->f3->set('breadcrumbs','/mat/chart/duedate');
	        $this->f3->set('navs','yes');
		$this->f3->set('isMobile',parent::isMobile());
	        $this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('layout','charts.htm');
                $this->f3->set('content','materials/duedate.htm');
    }
    public function chartdata02() {
		$clist = new \Customer($this->db);
		$data[] = array('custx'=>$this->f3->get('PARAMS.cus'));
		$event[] = $clist->eventdaylist($this->f3->get('PARAMS.cus'));

		$ctx = $event[0][0]['customer'];
		$cht = array();
		// Gathering customer names
		foreach ($data as $ikey => $ival) {
		  $cht += [ $ival['custx'] => ''];
		}
		$wkx = "";
		$vlx = "";
		// Creating the json variables
		foreach ($event[0] as $ikey => $ival) {
 			if ($ctx <> $ival['customer']) {
		  		$cht[$ctx] = $wkx."::".$vlx;
				$wkx="";
				$vlx="";
				$ctx = $ival['customer'];
		 	}
				$wkx = $wkx."'".$ival['daysx']."',";
				$vlx = $vlx.$ival['events'].",";
		}
		// Adding last value
	  	$cht[$ctx] = $wkx."::".$vlx;

		$this->f3->set('custx',$data);
		$this->f3->set('chart',$cht);
		$this->f3->set('maxbar',20);
		$this->f3->set('canvas_width',800);
		$this->f3->set('canvas_height',480);
		$this->f3->set('customer_route','/mat/rank/customer');
                $this->f3->set('breadcrumbs','/mat/chart/duedate');
	        $this->f3->set('navs','yes');
		$this->f3->set('isMobile',parent::isMobile());
	        $this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('layout','charts.htm');
                $this->f3->set('content','materials/duedate.htm');
    }

    public function apimatuni() {
		$sqlstr  = "SELECT PartNumber, COUNT(rid) as rowid, SUM(qty) as qty ";
		$sqlstr .= "FROM enc_matlog ";
		$sqlstr .= "WHERE duedate = '' ";
		$sqlstr .= "GROUP BY PartNumber ";
                $data[] = $this->db->exec($sqlstr);
		$json_data = json_encode($data[0]);
//		echo $json_data;
//		exit;
                $this->f3->set('details',$data);
		$this->f3->set('layout','plain.htm');
                $this->f3->set('content','materials/api.htm');
    }
    public function apidbs() {
		$sqlstr  = "SELECT rid,Line, UnitID,Description, PartNumber, Qty,Buyer, DueDate, count(rid) as Rows ";
		$sqlstr .= "FROM enc_matlog ";
		$sqlstr .= "WHERE DueDate >= date('now','-7 hours') and DueDate <= date('now','+1 day','-7 hours')";
		$sqlstr .= "GROUP BY partnumber ORDER BY DueDate";
		$data[] = $this->db->exec($sqlstr);
		$json_data = json_encode($data);
		echo $json_data;
		exit;
    }

}

?>
