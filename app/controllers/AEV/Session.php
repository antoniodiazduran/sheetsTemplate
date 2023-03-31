<?php

namespace AEV;

class Session extends \Controller {
    public function form() {
                $this->f3->set('breadcrumbs','aev/session');
                $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','aev/nav_aev.htm');
		$this->f3->set('username',$this->f3->get('SESSION.username'));
		$this->f3->set('weekdate',$this->f3->get('SESSION.weekdate'));
		$this->f3->set('epoch',$this->f3->get('SESSION.epoch'));
                $this->f3->set('layout','layout.htm');
                $this->f3->set('mode','create');
                $this->f3->set('content','aev/session/form.htm');
    }
    public function insertRow() {
		// Gathering data from form
		date_default_timezone_set("America/New_York");
		$fields[] = $this->f3->get('POST');
		// Creating array to insert into Sqlite
		$rowv = array(
                        $fields[0]['username'],
                        $fields[0]['weekdate'],
                	);
		// Update session variables
		$this->f3->set('SESSION.username',$fields[0]['username']);
		$this->f3->set('SESSION.weekdate',$fields[0]['weekdate']);
		$this->f3->set('SESSION.epoch',time());

		// Displaying new data
		$this->f3->reroute('/aev/session');
    }
}
?>

