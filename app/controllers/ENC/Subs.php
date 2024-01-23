<?php
namespace ENC;

class Subs extends \Controller {

    public function all() {
 	$data = $this->db->exec("SELECT * FROM subs_log ORDER BY Epoch desc");
        $this->f3->set('details',$data);
        $this->f3->set('breadcrumbs','subs');
      	$this->f3->set('navs','yes');
	$this->f3->set('columns','[1,2,3,4,5,6,7,8,9,10,11,12]');
        $this->f3->set('layout','layout.htm');
        $this->f3->set('headers','subs/headers.htm');
        $this->f3->set('fields','subs/fields.htm');
        $this->f3->set('content','subs/list.htm');
    }
    public function edit_record() {
        $this->f3->set('breadcrumbs','subs/rec');
        $this->f3->set('mode','upd');
        $rids = $this->f3->get('PARAMS.id');
        $record = $this->db->exec('SELECT Epoch, UnitID, DateTime, Current, Substitution, ApprovedBy, Notes, Logged FROM subs_log WHERE Epoch = ?',$rids);
        
        $this->f3->set('navs','yes');
        $this->f3->set('nav_menu','nav_subs.htm');
        $this->f3->set('isMobile',parent::isMobile()); 
        $this->f3->set('epoch',$rids);
        $this->f3->set('POST',$record[0]);
        $this->f3->set('layout','admin.htm');
        $this->f3->set('content','subs/edit.htm');
    }
    public function upd_record() {
        // Getting POST variables, epoch and datetime for logs
        $reqs = $this->f3->get('POST');
        date_default_timezone_set('America/Los_Angeles');
        $id = $reqs['POST.Epoch'];

        // Setting up variables for the display
        $this->f3->set('nav_menu','nav_subs.htm');
        $this->f3->set('isMobile',parent::isMobile());
        $this->f3->set('result','Record Updated !');
        $this->f3->set('layout','admin.htm');
        $this->f3->set('content','subs/status.htm');
    }
    public function edit() {
                $this->f3->set('breadcrumbs','subs/log');
                $rids = $this->f3->get('PARAMS.id');
                if (strpos($rids,",")>0) {
                        $rids = rtrim($rids,",");
                        $sqlstrm = 'SELECT Epoch, DateTime, Current, Substitution, ApprovedBy, Notes, Logged FROM subs_log WHERE Epoch in ('.$rids.')';
                        $record  = $this->db->exec($sqlstrm);
                } else {
                        $record  = $this->db->exec('SELECT Epoch, DateTime, Current, Substitution, ApprovedBy, Notes, Logged FROM subs_log WHERE Epoch = ?',$this->f3->get('POST.rids'));
                }
                $this->f3->set('navs','yes');
		$this->f3->set('columns','[1,2,3,4,5,6,7,8,9,10,11,12]');
                $this->f3->set('nav_menu','nav_subs.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('mode','upd');
                $this->f3->set('epoch',$rids);
                $this->f3->set('record',$record);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','subs/logged.htm');
    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $reqs = $this->f3->get('POST');
                date_default_timezone_set('America/Los_Angeles');
                $datet = date('Y-m-d H:i:s',time());
                $rowv = array(
                        $reqs['logged'],
                        );
                //$sql_update .= "WHERE rid = ?";
                $sql_update  = "UPDATE subs_log ";
                $sql_update .= "SET Logged=?  ";
                $sql_update .= "WHERE Epoch in (".$reqs['epoch'].")";
                $this->db->exec($sql_update,$rowv);

                // Setting up variables for the display
                $this->f3->set('nav_menu','nav_subs.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','subs/status.htm');
    }
    public function form() {
        $this->f3->set('breadcrumbs','subs');
	$this->f3->set('navs','yes');
        $this->f3->set('layout','layout.htm');
	$this->f3->set('mode','create');
        $this->f3->set('content','subs/form.htm');
    }

    public function insertRow() {
		// Gathering data from form
		date_default_timezone_set("America/Los_Angeles");
		$fields[] = $this->f3->get('POST');
		$rowv = array(
			    time(),
                date('m/d/Y H:i:s'),
                $fields[0]['unitnumber'],
                $fields[0]['current'],
                $fields[0]['substitution'],
                $fields[0]['approvedby'],
                $fields[0]['notes'],
        );
		// Inserting data into Google Sheets
		// $data[] = $this->GSheetsInsert('enc.esfs',$row,'1QrOuTaG8r_1ZjIdujTVzXbiiydjk-2rk8XxZpprOQD0');
		// Inserting into sqlite database
            $sql_insert  = "insert into subs_log ";
            $sql_insert .= "(Epoch, DateTime, UnitID, Current, Substitution, ApprovedBy, Notes) ";
            $sql_insert .= "VALUES (?,?,?,?,?,?,?)";
		$this->db->exec($sql_insert,$rowv);
		// Displaying new data
		$this->f3->reroute('/subs');
    }
}
?>

