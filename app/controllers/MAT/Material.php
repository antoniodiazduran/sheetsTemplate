<?php

namespace MAT;

class Material extends \Controller {


    public function remove() {
                $this->db->exec('DELETE FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                $this->f3->set('navs','yes');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->reroute('/mat/admin');

    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                $rowv = array(
                        $reqs['line'],
                        $reqs['unitid'],
                        $reqs['description'],
                        $reqs['partnumber'],
                        $reqs['qty'],
                        $reqs['qtyunit'],
                        $reqs['duedate'],
                        $reqs['buyer'],
                        $reqs['notes'],
                        $reqs['lowinventory'],
                        $reqs['requestorname'],
                        $reqs['epoch']
                        );
                // Updating the material shortage log
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET Line=?, UnitID=?, Description=?, PartNumber=?, Qty=?, QtyUnit=?, ";
                $sql_update .= "DueDate=?, Buyer=?, Notes=?, lowInventory=?, requestorName=? ";
                $sql_update .= "WHERE rid = ?";
                $this->db->exec($sql_update,$rowv);
                $this->f3->reroute('/mat/admin');
    }
    public function edit() {
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $record = $this->db->exec('SELECT Epoch,DateTime,Line,UnitID,Description,PartNumber,Qty,QtyUnit,DueDate,Buyer,Notes,lowInventory,requestorName FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                $this->f3->set('record', $record[0]);
                $this->f3->set('navs','no');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/edit.htm');
    }
    public function form() {
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('navs','no');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('mode','create');
                $this->f3->set('layout','responsive.htm');
                $this->f3->set('content','materials/form.htm');
    }
    public function add() {
                // Getting POST variables, epoch and datetime for logs
                $record = $this->f3->get('POST');
                $line =  $record['line'];
                $unit =  $record['unitid'];
                $desc =  $record['description'];
                $part =  $record['partnumber'];
                $qty  =  $record['qty'];
                $qtyu  =  $record['qtyunit'];
                $low  =  $record['lowinventory'];
		$reqn =  $record['requestorname'];
		$urg  = "";
                $dued =  "";
                $buye =  "";
                $note =  $record['notes'];
                $disp =  "y";
                date_default_timezone_set('America/Los_Angeles');
                $epoch = time();
                $datet = date('Y-m-d',time());
                // Logging changes
                $sqlstr = 'INSERT INTO enc_matlog (Epoch,DateTime,Line,UnitID,Description,PartNumber,Qty,QtyUnit,DueDate,Buyer,Notes,Display,lowInventory,requestorName,Urgency) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';
                $this->db->exec($sqlstr,array($epoch,$datet,$line,$unit,$desc,$part,$qty,$qtyu,$dued,$buye,$note,$disp,$low,$reqn,$urg));
/*
                $this->f3->set('res','Information sent...');
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('navs','no');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('mode','create');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
*/

		// Setting up variables for the display
                $this->f3->set('nav_menu','navbuyers.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');

    }
}

?>
