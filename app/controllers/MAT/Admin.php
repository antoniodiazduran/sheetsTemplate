<?php

namespace MAT;

class Admin extends \Controller {

    public function index() {
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','welcome.htm');  
    }

    public function batchload() {
		$e = $this->f3->get('PARAMS.e');
		$t = date('y-m-d h:i',time());
		$d = $this->f3->get('PARAMS.d');
		$l = $this->f3->get('PARAMS.l');
		$p = $this->f3->get('PARAMS.p');
		$b = $this->f3->get('PARAMS.b');
		$u = $this->f3->get('PARAMS.u');
		$str = "INSERT INTO enc_matlog (Epoch, DateTime, Description, Line, PartNumber, Buyer, DueDate) VALUES (?,?,?,?,?,?,?)";
	echo $str;
		$this->aev->exec($str,array($e,$t,$d,$l,$p,$b,$u) );
	exit;
    }
    public function screen() {
                $this->f3->set('breadcrumbs','mat');
	        $this->f3->set('navs','no');
		$this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('mode','display');
		$this->f3->set('layout','kiosk.htm');
                $this->f3->set('content','materials/ajax.htm');
    }
    public function list() {
		$fld = $this->f3->get('PARAMS.field');
		$val = $this->f3->get('PARAMS.value');
		if ($fld == '') {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m ORDER BY rid DESC Limit 150");
		} else {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE $fld = ? ORDER BY rid DESC",$val);
		}
		$this->f3->set('ourip',$_SERVER['REMOTE_ADDR']);
                $this->f3->set('details',$data);
		$this->f3->set('rowcount',count($data[0]));
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('isMobile',parent::isMobile());
	        $this->f3->set('customer','yes');
		$this->f3->set('bgcolor','navy');
                $this->f3->set('headers','materials/adminheaders.htm');
                $this->f3->set('fields','materials/fields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
    public function rank() {
                $fld = $this->f3->get('PARAMS.field');
                $val = $this->f3->get('PARAMS.value');
                if ($fld == '') {
                $data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE (arriveddate = '' or arriveddate is null) ORDER BY rid DESC LIMIT 150");
                } else {
                $data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE $fld = ? AND (arriveddate = '' or arriveddate is null) ORDER BY rid DESC",$val);
                }
                $this->f3->set('details',$data);
		$this->f3->set('rowcount',count($data[0]));
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('field','all');
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navbuyers.htm');
                $this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('customer','yes');
                $this->f3->set('bgcolor','red');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/buyersfields.htm');
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
    public function sort() {
		$fld = $this->f3->get('PARAMS.field');
		$val = $this->f3->get('PARAMS.value');
		if ($fld == '') {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE arrivedDate IS NUL ORDER BY rid DESC LIMIT 150");
		} else {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE $fld = ? AND arrivedDate IS NULL ORDER BY rid DESC",$val);
		}
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navleaders.htm');
	        $this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('customer','yes');
		$this->f3->set('bgcolor','green');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/sortfields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
    public function shipmonth() {
		$datet = date('m',time());
                $data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE strftime('%m',shipDate) = ? ORDER BY rid DESC",$datet);
                $this->f3->set('details',$data);
		$this->f3->set('rowcount',count($data[0]));
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('field','all');
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navleaders.htm');
                $this->f3->set('customer','yes');
                $this->f3->set('bgcolor','maroon');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/sortfields.htm');
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
    public function unique() {
		$data[] = $this->db->exec("SELECT PartNumber FROM enc_matlog GROUP BY PartNumber");
                $this->f3->set('details',$data);
		$this->f3->set('rowcount',count($data[0]));
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navbuyers.htm');
	        $this->f3->set('customer','yes');
		$this->f3->set('bgcolor','red');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/buyersfields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
    public function apidbs() {
		$sqlstr  = "SELECT rid,Line,iif(supplier is not null, supplier, '-') as supplier,Description, PartNumber, Qty,Buyer, DueDate, count(rid) as Rows  ";
		$sqlstr .= "FROM enc_matlog ";
		$sqlstr .= "WHERE DueDate >= date('now','-7 hours') and DueDate <= date('now','+1 day','-7 hours')";
		$sqlstr .= "GROUP BY PartNumber ORDER BY supplier DESC";
		$data[] = $this->db->exec($sqlstr);
		$json_data = json_encode($data);
		echo $json_data;
		exit;
    }

}
?>
