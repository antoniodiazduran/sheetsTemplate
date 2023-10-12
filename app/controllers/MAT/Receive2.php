<?php

namespace MAT;

class Receive2 extends \Controller {

   public function edit() {
                $this->f3->set('breadcrumbs','mat/rec');
                $rids = $this->f3->get('PARAMS.id');
                if (strpos($rids,",")>0) {
                        $rids = rtrim($rids,",");
                        $sqlstrm = 'SELECT rid,Epoch,PartNumber,Description,DueDate,paretoReason as reason FROM enc_matlog WHERE rid in ('.$rids.')';
                        $record  = $this->db->exec($sqlstrm);
                } else {
                        $record  = $this->db->exec('SELECT rid,Epoch,PartNumber,Description,DueDate,paretoReason as reason FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                }
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
                $this->f3->set('record',$record);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/receive2.htm');
    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                date_default_timezone_set('America/Los_Angeles');
                $datet = date('Y-m-d H:i:s',time());
                $rowv = array(
                        $reqs['arriveddate'],
			'n',
                        );
                //$sql_update .= "WHERE rid = ?";
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET ArrivedDate=?, Display=? ";
                $sql_update .= "WHERE rid in (".$reqs['epoch'].")";
                $this->db->exec($sql_update,$rowv);

                // Setting up variables for the display
                $this->f3->set('nav_menu','navbuyers.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }
    public function list() {
                $fld = $this->f3->get('PARAMS.field');
                $val = $this->f3->get('PARAMS.value');
                if ($fld == '') {
                 $sqlstr  = "SELECT rid,DateTime, Line, UnitID,Description, PartNumber, Qty,Buyer, DueDate, arrivedDate, supplier, ";
                 $sqlstr .= "(SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship  ";
                 $sqlstr .= "FROM enc_matlog m ";
                 $sqlstr .= "WHERE DueDate >= date('now','-7 hours') and DueDate <= date('now','+1 day','-7 hours') ";
//               $sqlstr .= "AND arrivedDate IS NULL ";
                 $sqlstr .= "GROUP BY partnumber ORDER BY supplier desc";
                 $data[] = $this->db->exec($sqlstr);
                }
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('field','all');
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navleaders.htm');
                $this->f3->set('customer','yes');
                $this->f3->set('bgcolor','orange');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/receivingfields.htm');
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
}

?>

