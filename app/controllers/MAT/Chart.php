<?php

namespace MAT;

class Chart extends \Controller {

    public function isMobile()  {
	 return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
    public function ajax() {
		$this->f3->set('layout','flat.htm');
                $this->f3->set('content','materials/tableajax.htm');
    }
    public function duedate() {
                $this->f3->set('breadcrumbs','/mat/chart/deudate');
	        $this->f3->set('navs','yes');
		$this->f3->set('isMobile',$this->isMobile());
	        $this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('layout','charts.htm');
                $this->f3->set('content','materials/duedate.htm');
    }
    public function chartdata01() {
	$sqlstr  = "SELECT ";
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
