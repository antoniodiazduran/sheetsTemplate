<?php
namespace ENC;

class Subs extends \Controller {

    public function all() {
	 	$data = $this->db->exec("SELECT * FROM subs_log ORDER BY Epoch desc");
        
        $this->f3->set('details',$data);
        $this->f3->set('breadcrumbs','subs');
      	$this->f3->set('navs','yes');
        $this->f3->set('layout','layout.htm');
        $this->f3->set('headers','subs/headers.htm');
        $this->f3->set('fields','subs/fields.htm');
        $this->f3->set('content','subs/list.htm');
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

