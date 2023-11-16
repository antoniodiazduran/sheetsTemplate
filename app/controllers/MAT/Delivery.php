<?php

namespace MAT;

class Delivery extends \Controller {

   public function add() {
                $this->f3->set('breadcrumbs','mat/delivery');
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navdelivery.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('mode','create');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/deliverynew.htm');
   }
   public function create() {
		$record = $this->f3->get('POST');
                $po  =  $record['po'];
                $sap =  $record['sap'];
                $ax  =  $record['ax'];
                $cus =  $record['customer'];
                $shi =  $record['ship'];
                date_default_timezone_set('America/Los_Angeles');
                $epoch = time();
                $datet = date('Y-m-d',time());
                // Logging changes
                $sqlstr = 'INSERT INTO enc_so (po,sap,ax,customer,ship) VALUES (?,?,?,?,?) ';
                $this->db->exec($sqlstr,array($po,$sap,$ax,$cus,$shi));
                $this->f3->set('breadcrumbs','mat/delivery');
                $this->f3->set('navs','no');
                $this->f3->set('nav_menu','navdelivery.htm');
                $this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('result','Record added !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
   }
   public function list() {
                $fld = $this->f3->get('PARAMS.field');
                $val = $this->f3->get('PARAMS.value');
                if ($fld == '') {
                $data[] = $this->db->exec("SELECT rid,po,sap,ax,customer, ship FROM enc_so ORDER BY ax");
                } else {
                $data[] = $this->db->exec("SELECT rid,po,sap,ax,customer, ship FROM enc_so WHERE $fld = ? ORDER BY ax",$val);
                }
                $this->f3->set('details',$data);
                $this->f3->set('rowcount',count($data[0]));
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('field','all');
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navdelivery.htm');
                $this->f3->set('customer','yes');
                $this->f3->set('columns','[1,2,3,4,5]');
                $this->f3->set('bgcolor','red');
                $this->f3->set('headers','materials/deliveryheaders.htm');
                $this->f3->set('fields','materials/deliveryfields.htm');
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/delivery.htm');
   }
   public function edit() {
                $this->f3->set('breadcrumbs','mat/delivery');
                $rids = $this->f3->get('PARAMS.id');
                if (strpos($rids,",")>0) {
                        $rids = rtrim($rids,",");
                        $sqlstrm = 'SELECT rid,po,sap,ax,customer,ship FROM enc_so WHERE rid in ('.$rids.')';
                        $record  = $this->db->exec($sqlstrm);
                } else {
                        $record  = $this->db->exec('SELECT rid,po,sap,ax,customer,ship FROM enc_so WHERE rid = ?',$this->f3->get('PARAMS.id'));
                }
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navdelivery.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
                $this->f3->set('record',$record);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/ship2.htm');
    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                date_default_timezone_set('America/Los_Angeles');
                $datet = date('Y-m-d H:i:s',time());
                $rowv = array(
                        $reqs['ship'],
                        );
                // Updating duedate material shortage
                $sql_update  = "UPDATE enc_so ";
                $sql_update .= "SET ship=? ";
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
