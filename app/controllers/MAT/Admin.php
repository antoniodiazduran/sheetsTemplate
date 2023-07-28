<?php

namespace MAT;

class Admin extends \Controller {

    public function index() {
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','welcome.htm');  
    }

    public function isMobile()  {
	 return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
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
                $record = $this->db->exec('SELECT rid,Epoch,PartNumber,Description,DueDate,ArrivedDate,Display FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('record',$record[0]);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/receive.htm');
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
		$this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }
    public function shipdate_edit() {
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
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
//                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $this->f3->set('record',$record);
                $this->f3->set('shipdate',$shipdate[0]);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/ship.htm');
    }
    public function shipdate_upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                date_default_timezone_set('America/Los_Angeles');
                $datet = date('Y-m-d H:i:s',time());
                $rowv = array(
                        $reqs['shipdate'],
//                        $reqs['buyer']
//                        $reqs['epoch']
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
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }
    public function buyer_edit() {
                $this->f3->set('breadcrumbs','mat/buyer');
		$rids = $this->f3->get('PARAMS.id');
		if (strpos($rids,",")>0) {
			$rids = rtrim($rids,",");
                	$sqlstr = 'SELECT rid,Epoch,PartNumber,Description,DueDate,Buyer FROM enc_matlog WHERE rid in ('.$rids.')';
                	$record  = $this->db->exec($sqlstr);
			//$duedate[] = $this->db->exec('SELECT rid, relation, DueDate, timeStamp FROM enc_duedatelog WHERE relation = ?',$this->f3->get('PARAMS.id'));
		} else {
                	$record  = $this->db->exec('SELECT rid,Epoch,PartNumber,Description,DueDate,Buyer FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
			$duedate[] = $this->db->exec('SELECT rid, relation, DueDate, timeStamp FROM enc_duedatelog WHERE relation = ?',$this->f3->get('PARAMS.id'));
		}
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navmaterial.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
//                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $this->f3->set('record',$record);
                $this->f3->set('duedate',$duedate[0]);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/buyer.htm');
    }
    public function buyer_upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
		date_default_timezone_set('America/Los_Angeles');
		$datet = date('Y-m-d H:i:s',time());
                $rowv = array(
                        $reqs['duedate'],
                        $reqs['buyer']
//                        $reqs['epoch']
                        );
		$logv = array(
			//$reqs['epoch'],
			$reqs['duedate'],
			$datet
			);
                // Updating duedate material shortage
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET DueDate=?, Buyer=? ";
                $sql_update .= "WHERE rid in (".$reqs['epoch'].")";
                $this->db->exec($sql_update,$rowv);

		// Inserting into duedate log
		$rows = explode(",",$reqs['epoch']);
		foreach ($rows as $rw) {
			$sql_log  = "INSERT INTO enc_duedatelog ";
			$sql_log .= "( relation, DueDate, timeStamp ) VALUES (".$rw.",?,?)";
			$this->db->exec($sql_log, $logv);
		}
		// Setting up variables for the display
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
			$reqs['lowinventory'],
                        $reqs['epoch']
                        );
                // Updating the material shortage log
                $sql_update  = "UPDATE enc_matlog ";
                $sql_update .= "SET Line=?, UnitID=?, Description=?, PartNumber=?, Qty=?,";
                $sql_update .= "DueDate=?, Buyer=?, Notes=?, lowInventory=? ";
                $sql_update .= "WHERE rid = ?";
                $this->db->exec($sql_update,$rowv);
                $this->f3->reroute('/mat/admin');
    }
    public function mat_edit() {
                $this->f3->set('breadcrumbs','mat');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$record = $this->db->exec('SELECT Epoch,DateTime,Line,UnitID,Description,PartNumber,Qty,DueDate,Buyer,Notes,lowInventory FROM enc_matlog WHERE rid = ?',$this->f3->get('PARAMS.id'));
		$this->f3->set('record', $record[0]);
	        $this->f3->set('navs','no');
		$this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('mode','upd');
		$this->f3->set('isMobile',$this->isMobile());
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/edit.htm');
    }
    public function notes_edit() {
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
		$this->f3->set('isMobile',$this->isMobile());
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/notes.htm');
    }
    public function notes_upd() {
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
		$this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }
    public function material() {
                $this->f3->set('breadcrumbs','mat');
	        $this->f3->set('navs','no');
		$this->f3->set('isMobile',$this->isMobile());
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
		$low  =  $record['lowinventory'];
		$dued =  "";
		$buye =  "";
		$note =  $record['notes'];
		$disp =  "y";
		date_default_timezone_set('America/Los_Angeles');
		$epoch = time();
		$datet = date('Y-m-d',time());
		// Logging changes
		$this->db->exec('INSERT INTO enc_matlog (Epoch,DateTime,Line,UnitID,Description,PartNumber,Qty,DueDate,Buyer,Notes,Display,lowInventory) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)',array($epoch,$datet,$line,$unit,$desc,$part,$qty,$dued,$buye,$note,$disp,$low));
		// Returning to empty form
		$this->f3->set('res','Information sent...');
                $this->f3->set('breadcrumbs','mat');
	        $this->f3->set('navs','no');
		$this->f3->set('nav_menu','navmaterial.htm');
		$this->f3->set('mode','create');
		$this->f3->set('isMobile',$this->isMobile());
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/form.htm');
    }
    public function list() {
		$fld = $this->f3->get('PARAMS.field');
		$val = $this->f3->get('PARAMS.value');
		if ($fld == '') {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m ORDER BY rid DESC");
		} else {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE $fld = ? ORDER BY rid DESC",$val);
		}
		$this->f3->set('ourip',$_SERVER['REMOTE_ADDR']);
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navmaterial.htm');
	        $this->f3->set('customer','yes');
		$this->f3->set('bgcolor','navy');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/fields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
    public function rank() {
                $fld = $this->f3->get('PARAMS.field');
                $val = $this->f3->get('PARAMS.value');
                if ($fld == '') {
                $data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE (arriveddate = '' or arriveddate is null) ORDER BY rid DESC");
                } else {
                $data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE $fld = ? AND (arriveddate = '' or arriveddate is null) ORDER BY rid DESC",$val);
                }
                $this->f3->set('details',$data);
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
    public function sort() {
		$fld = $this->f3->get('PARAMS.field');
		$val = $this->f3->get('PARAMS.value');
		if ($fld == '') {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m ORDER BY rid DESC");
		} else {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE $fld = ? ORDER BY rid DESC",$val);
		}
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navleaders.htm');
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
    public function listbuyers() {
		$fld = $this->f3->get('PARAMS.field');
		$val = $this->f3->get('PARAMS.value');
		if ($fld == '') {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE arriveddate is null ORDER BY rid DESC");
		} else {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE $fld = ? AND arriveddate is null ORDER BY rid DESC",$val);
		}
		$this->f3->set('ourip',$_SERVER['REMOTE_ADDR']);
                $this->f3->set('details',$data);
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
    public function listleaders() {
		$fld = $this->f3->get('PARAMS.field');
		$val = $this->f3->get('PARAMS.value');
		if ($fld == '') {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m ORDER BY rid DESC");
		} else {
		$data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE $fld = ? ORDER BY rid DESC",$val);
		}
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navleaders.htm');
	        $this->f3->set('customer','yes');
		$this->f3->set('bgcolor','green');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/leadersfields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }
    public function apimatuni() {
		$sqlstr  = "SELECT PartNumber, COUNT(rid) as rowid, SUM(qty) as qty ";
		$sqlstr .= "FROM enc_matlog ";
		$sqlstr .= "WHERE duedate = '' ";
		$sqlstr .= "GROUP BY PartNumber ";
                $data[] = $this->db->exec($sqlstr);
		$json_data = json_encode($data[0]);
		echo $json_data;
		exit;
//                $this->f3->set('details',$data);
//		  $this->f3->set('layout','plain.htm');
//                $this->f3->set('content','materials/api.htm');
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
    public function buyers_api() {
		$sqlstr  = "SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship ";
		$sqlstr .= "FROM enc_matlog m ";
//		$sqlstr .= "WHERE arriveddate is null or arriveddate = '' ";
		$sqlstr .= "ORDER BY DateTime ";
		$data[] = $this->db->exec($sqlstr);
//		$json_data = json_encode($data);
//		echo $json_data;
                $this->f3->set('details',$data);
		$this->f3->set('layout','plain.htm');
                $this->f3->set('content','materials/api.htm');
//		exit;
    }

}
?>
