<?php

namespace MAT;

class Notes extends \Controller {

 public function edit() {
                $this->f3->set('breadcrumbs','mat/notes');
                $rids = $this->f3->get('PARAMS.id');
                if (strpos($rids,",")>0) {
                        $rids = rtrim($rids,",");
                        $sqlstrh = 'SELECT rid,PartNumber,Description,UnitID,Line FROM enc_matlog WHERE rid in ('.$rids.')';
                        $record  = $this->db->exec($sqlstrh);
                        $sqlstrn = 'SELECT rid,relation,datetime,notes FROM enc_matnotes WHERE relation in ('.$rids.')';
                        $notes[] = $this->db->exec($sqlstrn);
                } else {
                        $record = $this->db->exec('SELECT rid,PartNumber, Description, UnitID, Line FROM enc_matlog WHERE rid = ?', $this->f3->get('PARAMS.id'));
                        $notes[] = $this->db->exec('SELECT rid,relation,datetime,notes FROM enc_matnotes WHERE relation = ?',$this->f3->get('PARAMS.id'));
                }
                $this->f3->set('record', $record);
                $this->f3->set('notes',$notes);
                $this->f3->set('navs','no');
                $this->f3->set('nav_menu','navbuyers.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/notes.htm');
    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                date_default_timezone_set('America/Los_Angeles');
                $datet = date('Y-m-d H:i:s',time());
                $logv = array(
                        //$reqs['epoch'],
                        $reqs['notes'],
                        $datet
                        );
                // Inserting into duedate log
                $rows = explode(",",$reqs['epoch']);
                foreach ($rows as $rw) {
                        $sql_log  = "INSERT INTO enc_matnotes ";
                        $sql_log .= "( relation, notes, datetime ) VALUES (".$rw.",?,?)";
                        $this->db->exec($sql_log, $logv);
                }
                // ***********************************************************
                // Inserting into duedate log - Old single update mat notes
                //$sql_log  = "INSERT INTO enc_matnotes ";
                //$sql_log .= "( relation, notes, datetime ) VALUES (?,?,?)";
                //$this->db->exec($sql_log, $logv);
                // ***********************************************************
                // Setting up variables for the display
                $this->f3->set('nav_menu','navbuyers.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }

}

?>
