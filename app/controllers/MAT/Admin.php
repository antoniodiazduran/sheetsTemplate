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
    public function mat_remove() {
                $this->db->exec('DELETE FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->reroute('/mat/admin');

    }
    public function receive_edit() {
                $this->f3->set('breadcrumbs','mat/rec');
                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $record = $this->db->exec('SELECT rid,Epoch,DueDate,ArrivedDate,Display FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('record',$record[0]);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }
    public function buyer_edit() {
                $this->f3->set('breadcrumbs','mat/buyer');
                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $record = $this->db->exec('SELECT rid,Epoch,PartNumber,Description,DueDate,Buyer FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('record',$record[0]);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/buyer.htm');
    }
    public function receive_upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                $rowv = array(
                        $reqs['arriveddate'],
                        $reqs['display'],
                        $reqs['epoch']
                        );
                // Inserting into sqlite database
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET ArrivedDate=?, Display=? ";
                $sql_update .= "WHERE rid = ?";
                $this->db->exec($sql_update,$rowv);
                $this->f3->reroute('/mat/admin');
    }
    public function buyer_upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                $rowv = array(
                        $reqs['duedate'],
                        $reqs['buyer'],
                        $reqs['epoch']
                        );
                // Inserting into sqlite database
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET DueDate=?, Buyer=? ";
                $sql_update .= "WHERE rid = ?";
                $this->db->exec($sql_update,$rowv);
	        $this->f3->set('nav_menu','navbuyers.htm');

		$this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }
    public function mat_upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                $rowv = array(
                        $reqs['line'],
                        $reqs['unitid'],
                        $reqs['description'],
                        $reqs['partnumber'],
                        $reqs['qty'],
                        $reqs['duedate'],
                        $reqs['buyer'],
                        $reqs['notes'],
                        $reqs['epoch']
                        );
                // Inserting into sqlite database
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET Line=?, UnitID=?, Description=?, PartNumber=?, Qty=?,";
                $sql_update .= "DueDate=?, Buyer=?, Notes=? ";
                $sql_update .= "WHERE rid = ?";

                $this->db->exec($sql_update,$rowv);
                $this->f3->reroute('/mat/admin');
    }
    public function mat_edit() {
                $this->f3->set('breadcrumbs','mat');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$record = $this->db->exec('SELECT Epoch,DateTime,Line,UnitID,Description,PartNumber,Qty,DueDate,Buyer,Notes FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
		$this->f3->set('record', $record[0]);
	        $this->f3->set('navs','no');
		$this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('mode','upd');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/edit.htm');
    }
    public function material() {
                $this->f3->set('breadcrumbs','mat');
	        $this->f3->set('navs','no');
		$this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('mode','create');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/form.htm');
    }
    public function screen() {
                $this->f3->set('breadcrumbs','mat');
	        $this->f3->set('navs','no');
		$this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('mode','display');
		$this->f3->set('layout','kiosk.htm');
                $this->f3->set('content','materials/ajax.htm');
    }
    public function mat_add() {
		// Getting POST variables, epoch and datetime for logs
		$record = $this->f3->get('POST');
		$line =  $record['line'];
		$unit =  $record['unitid'];
		$desc =  $record['description'];
		$part =  $record['partnumber'];
		$qty  =  $record['qty'];
		$dued =  "";
		$buye =  "";
		$note =  $record['notes'];
		$disp =  "y";
		$epoch = time();
		$datet = date('Y-m-d',time());
		// Logging changes
		$this->db->exec('INSERT INTO enc_matlog (Epoch,DateTime,Line,UnitID,Description,PartNumber,Qty,DueDate,Buyer,Notes,Display) VALUES(?,?,?,?,?,?,?,?,?,?,?)',array($epoch,$datet,$line,$unit,$desc,$part,$qty,$dued,$buye,$note,$disp));
		// Returning to empty form
		$this->f3->set('res','Information sent...');
                $this->f3->set('breadcrumbs','mat');
	        $this->f3->set('navs','no');
		$this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('mode','create');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/form.htm');
    }
    public function list() {
		$fld = $this->f3->get('PARAMS.field');
		$val = $this->f3->get('PARAMS.value');
		if ($fld == '') {
		$data[] = $this->db->exec("SELECT * FROM enc_matlog ORDER BY rid DESC");
		} else {
		$data[] = $this->db->exec("SELECT * FROM enc_matlog WHERE $fld = ? ORDER BY rid DESC",$val);
		}
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','owr');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navmaterial.htm');
	        $this->f3->set('customer','yes');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/fields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
    public function sort() {
		$fld = $this->f3->get('PARAMS.field');
		$val = $this->f3->get('PARAMS.value');
		if ($fld == '') {
		$data[] = $this->db->exec("SELECT * FROM enc_matlog ORDER BY rid DESC");
		} else {
		$data[] = $this->db->exec("SELECT * FROM enc_matlog WHERE $fld = ? ORDER BY rid DESC",$val);
		}
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','owr');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navsort.htm');
	        $this->f3->set('customer','yes');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/sortfields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
    public function listbuyers() {
		$fld = $this->f3->get('PARAMS.field');
		$val = $this->f3->get('PARAMS.value');
		if ($fld == '') {
		$data[] = $this->db->exec("SELECT * FROM enc_matlog ORDER BY rid DESC");
		} else {
		$data[] = $this->db->exec("SELECT * FROM enc_matlog WHERE $fld = ? ORDER BY rid DESC",$val);
		}
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','owr');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navbuyers.htm');
	        $this->f3->set('customer','yes');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/buyersfields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
    public function listleaders() {
		$fld = $this->f3->get('PARAMS.field');
		$val = $this->f3->get('PARAMS.value');
		if ($fld == '') {
		$data[] = $this->db->exec("SELECT * FROM enc_matlog ORDER BY rid DESC");
		} else {
		$data[] = $this->db->exec("SELECT * FROM enc_matlog WHERE $fld = ? ORDER BY rid DESC",$val);
		}
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','owr');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navmaterial.htm');
	        $this->f3->set('customer','yes');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/leadersfields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
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
