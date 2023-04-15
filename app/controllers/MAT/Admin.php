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
		$str = "INSERT INTO enc_mlog (Epoch, DateTime, Description, Line, PartNumber, Buyer, DueDate) VALUES (?,?,?,?,?,?,?)";
	echo $str;
		$this->aev->exec($str,array($e,$t,$d,$l,$p,$b,$u) );
	exit;
    }
    public function mat_remove() {
                $this->db->exec('DELETE FROM enc_mlog WHERE Epoch = ?',$this->f3->get('PARAMS.id'));
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->reroute('/mat/admin');

    }
    public function status_edit() {
                $this->f3->set('breadcrumbs','mat/sta');
                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $record = $this->db->exec('SELECT Epoch,DueDate,ArrivedDate,Display FROM enc_mlog WHERE Epoch = ?',$this->f3->get('PARAMS.id'));
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('record',$record[0]);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }
    public function status_upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                $rowv = array(
                        $reqs['arriveddate'],
                        $reqs['display'],
                        $reqs['epoch']
                        );
                // Inserting into sqlite database
                $sql_update  = "UPDATE enc_mlog ";
                $sql_update .= "SET ArrivedDate=?, Display=? ";
                $sql_update .= "WHERE Epoch = ?";
                $this->db->exec($sql_update,$rowv);
                $this->f3->reroute('/mat/admin');
    }
    public function mat_upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                $rowv = array(
                        $reqs['line'],
                        $reqs['description'],
                        $reqs['partnumber'],
                        $reqs['duedate'],
                        $reqs['buyer'],
                        $reqs['notes'],
                        $reqs['epoch']
                        );
                // Inserting into sqlite database
                $sql_update  = "UPDATE enc_mlog ";
                $sql_update .= "SET Line=?, Description=?, PartNumber=?,";
                $sql_update .= "DueDate=?, Buyer=?, Notes=? ";
                $sql_update .= "WHERE Epoch = ?";
                $this->db->exec($sql_update,$rowv);
                $this->f3->reroute('/mat/admin');
    }
    public function mat_edit() {
                $this->f3->set('breadcrumbs','mat');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$record = $this->db->exec('SELECT Epoch,DateTime,Line,Description,PartNumber,DueDate,Buyer,Notes FROM enc_mlog WHERE Epoch = ?',$this->f3->get('PARAMS.id'));
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
		$desc =  $record['description'];
		$part =  $record['partnumber'];
		$dued =  $record['duedate'];
		$buye =  $record['buyer'];
		$note =  $record['notes'];
		$disp =  "y";
		$epoch = time();
		$datet = date('y-m-d h:i',time());
		// Logging changes
		$this->db->exec('INSERT INTO enc_mlog (Epoch,DateTime,Line,Description,PartNumber,DueDate,Buyer,Notes,Display) VALUES(?,?,?,?,?,?,?,?,?)',array($epoch,$datet,$line,$desc,$part,$dued,$buye,$note,$disp));
		$this->f3->reroute('/mat/admin');
    }
    public function list() {
		$data[] = $this->db->exec("SELECT * FROM enc_mlog ORDER BY Epoch DESC");
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
    public function apidbs() {
		$data[] = $this->db->exec('SELECT Line, Description, PartNumber, Buyer, DueDate FROM enc_mlog ORDER BY epoch desc');
		$json_data = json_encode($data);
		echo $json_data;
		exit;
    }

}
?>
