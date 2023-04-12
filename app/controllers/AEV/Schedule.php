<?php

namespace AEV;

class Schedule extends \Controller {

    public function list() {
                $this->f3->set('breadcrumbs','aev/schedule');
	        $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','aev/nav_aev.htm');
		$details[] = $this->aev->exec('SELECT Jobnumber, Startdate, Line, Dealername, Customername, Vin FROM sch_board ORDER BY Startdate, Line');
		$this->f3->set('details',$details);
		$this->f3->set('headers','aev/schedule/headers.htm');
		$this->f3->set('fields','aev/schedule/fields.htm');
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','aev/schedule/list.htm');
    }

    public function adding() {
		// Gathering data from form
		date_default_timezone_set("America/New_York");
		// Creating variables from session and GET
		$this->f3->set('epoch',$this->f3->get('SESSION.epoch'));
		$this->f3->set('username',$this->f3->get('SESSION.username'));
		$this->f3->set('weekdate',$this->f3->get('SESSION.weekdate'));
		$this->f3->set('unitnumber',$this->f3->get('PARAMS.unit'));

		$rowv = array(
			time(),
			$this->f3->get('SESSION.epoch'),
			$this->f3->get('SESSION.username'),
			$this->f3->get('SESSION.weekdate'),
			$this->f3->get('PARAMS.unit'),
		);
		// Saving on databse
		$this->aev->exec('INSERT INTO sch_log (Epoch, SessionID, Username, Weekdate, Unitnumber) VALUES (?,?,?,?,?)',$rowv);

	        $this->f3->set('navs','no');
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','aev/schedule/response.htm');
    }
}
?>

