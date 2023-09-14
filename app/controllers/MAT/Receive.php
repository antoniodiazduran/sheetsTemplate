<?php

namespace MAT;

class Receive extends \Controller {

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
//              } else {
//               $sqlstr  = "SELECT rid,DateTime, Line, UnitID,Description, PartNumber, Qty,Buyer, DueDate, count(rid) as arrivedDate, ";
//               $sqlstr  = "(SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship  ";
//               $sqlstr .= "FROM enc_matlog m ";
//               $sqlstr .= "WHERE DueDate >= date('now','-7 hours') and DueDate <= date('now','+1 day','-7 hours') and $fld = ? ";
//               $sqlstr .= "GROUP BY partnumber ORDER BY DueDate";
//               $data[] = $this->db->exec($sqlstr,$val);
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
    public function edit() {
                $this->f3->set('breadcrumbs','mat/rec');
                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $record = $this->db->exec('SELECT rid,Epoch,PartNumber,Description,DueDate,ArrivedDate,Display FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('mode','upd');
                $this->f3->set('record',$record[0]);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/receive.htm');
    }
    public function upd() {
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
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }
}

?>
