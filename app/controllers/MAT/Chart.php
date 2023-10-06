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
		// Customer name's array
		$sqlstr  = "SELECT SUBSTR(s.customer,1,3) AS custx ";
		$sqlstr .= "FROM enc_so s ";
		$sqlstr .= "WHERE s.ax IN (SELECT m.unitid FROM enc_matlog m) ";
		$sqlstr .= "GROUP BY custx";
		$data[] = $this->db->exec($sqlstr);
		// Transactions by customer
		$sqlsty  = "SELECT * ";
		$sqlsty .= "FROM enc_veventweeks  ";
		$sqlsty .= "WHERE customer IS NOT NULL ";
		$sqlsty .= "GROUP BY customer, weekx ORDER BY customer, weekx";
		$event[] = $this->db->exec($sqlsty);
		$ctx = $event[0][0]['customer'];
		$cht = array();
		// Gathering customer names
		foreach ($data[0] as $ikey => $ival) {
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
				$wkx = $wkx.$ival['weekx'].",";
				$vlx = $vlx.$ival['events'].",";
		}
		// Adding last value
	  	$cht[$ctx] = $wkx."::".$vlx;

		$this->f3->set('custx',$data[0]);
		$this->f3->set('chart',$cht);
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
