<?php

namespace MAT;

class Supplier extends \Controller {

   public function edit() {
                $this->f3->set('breadcrumbs','mat/supplier');
                $rids = $this->f3->get('PARAMS.id');
                if (strpos($rids,",")>0) {
                        $rids = rtrim($rids,",");
                        $sqlstrm = 'SELECT rid,Epoch,PartNumber,Description,DueDate,supplier FROM enc_matlog WHERE rid in ('.$rids.')';
                        $record  = $this->db->exec($sqlstrm);
                } else {
                        $record  = $this->db->exec('SELECT rid,Epoch,PartNumber,Description,DueDate,supplier FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                }
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
                $this->f3->set('record',$record);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/supplier.htm');
    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                date_default_timezone_set('America/Los_Angeles');
                $datet = date('Y-m-d H:i:s',time());
                $rowv = array(
                        $reqs['supplier'],
                        );
                // Updating duedate material shortage
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET supplier=? ";
                $sql_update .= "WHERE rid in (".$reqs['epoch'].")";
                $this->db->exec($sql_update,$rowv);

                // Setting up variables for the display
                $this->f3->set('nav_menu','navbuyers.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }

}


?>
