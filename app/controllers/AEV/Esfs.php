<?php

namespace AEV;

class Esfs extends \Controller {
    public function form() {
                $this->f3->set('breadcrumbs','aev/esfs');
                $this->f3->set('navs','yes');
                $this->f3->set('layout','layout.htm');
                $this->f3->set('mode','create');
                $this->f3->set('content','aev/esfs/form.htm');
    }
    public function insertRow() {
		// Gathering data from form
		date_default_timezone_set("America/New_York");
		$fields[] = $this->f3->get('POST');
		$sorts = "";
		if($fields[0]['urgency'] == 'Line Stopper') { $sorts = 1; }
		if($fields[0]['urgency'] == 'See due date') { $sorts = 2; }
		if($fields[0]['urgency'] == 'As available') { $sorts = 3; }
		// Creating array to insert into Sqlite
		$rowv = array(
			time(),
                        date('m/d/Y H:i:s'),
                        $fields[0]['line'],
                        $fields[0]['description'],
                        $fields[0]['customer'],
                        $fields[0]['unitnumber'],
                        $fields[0]['duedate'],
                        $fields[0]['urgency'],
                        $fields[0]['requestor'],
                        $fields[0]['owner'],
                        $fields[0]['notes'],
			'y',
			$sorts,
                	);
		// Create a record
		$sql_insert  = "insert into eng_log ";
                $sql_insert .= "(Epoch,DateTime, Line, Description, Customer, UnitNumber, DueDate, Urgency, Requestor, Owner, Notes, Display, Sort) ";
                $sql_insert .= "VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$this->aev->exec($sql_insert,$rowv);
		// Displaying new data
		$this->f3->reroute('/aev/esfs');
    }
}
?>

