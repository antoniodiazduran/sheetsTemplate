<?php
namespace MAT;

class Shipdate extends \Controller {

    public function edit() {
                $this->f3->set('breadcrumbs','mat/ship');
                $rids = $this->f3->get('PARAMS.id');
                if (strpos($rids,",")>0) {
                        $rids = rtrim($rids,",");
                        $sqlstr = 'SELECT rid,Epoch,PartNumber,Description,shipDate, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer FROM enc_matlog m WHERE rid in ('.$rids.')';
                        $record  = $this->db->exec($sqlstr);
                        //$duedate[] = $this->db->exec('SELECT rid, relation, DueDate, timeStamp FROM enc_duedatelog WHERE relation = ?',$this->f3->get('PARAMS.id'));
                } else {
                        $record  = $this->db->exec('SELECT rid,Epoch,PartNumber,Description,shipDate,Buyer FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                        $shipdate[] = $this->db->exec('SELECT rid, relation, shipDate, timeStamp FROM enc_shipdatelog WHERE relation = ?',$this->f3->get('PARAMS.id'));
                }
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
//                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $this->f3->set('record',$record);
                $this->f3->set('shipdate',$shipdate[0]);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/ship.htm');
    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                date_default_timezone_set('America/Los_Angeles');
                $datet = date('Y-m-d H:i:s',time());
                $rowv = array(
                        $reqs['shipdate'],
                        );
                $logv = array(
                        //$reqs['epoch'],
                        $reqs['shipdate'],
                        $datet
                        );
                // Updating duedate material shortage
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET shipDate=? ";
                $sql_update .= "WHERE rid in (".$reqs['epoch'].")";
                $this->db->exec($sql_update,$rowv);

                // Inserting into duedate log
                $rows = explode(",",$reqs['epoch']);
                foreach ($rows as $rw) {
                        $sql_log  = "INSERT INTO enc_shipdatelog ";
                        $sql_log .= "( relation, shipDate, timeStamp ) VALUES (".$rw.",?,?)";
                        $this->db->exec($sql_log, $logv);
                }
                // Setting up variables for the display
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }

}

?>
