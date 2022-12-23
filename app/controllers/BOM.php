<?php

class BOM extends Controller {
    public function bomTable() {
                $this->f3->set('breadcrumbs','bom');
                $this->f3->set('field','all');
                $this->f3->set('navs','no');
                $this->f3->set('customer','no');
                $this->f3->set('layout','kiosk.htm');
                $this->f3->set('content','bom/ajax.htm');
    }
    public function readData() {
	$rows[] = $this->db->exec('SELECT jsondata FROM eng_bom ORDER BY timestamp DESC LIMIT 1');
	$jsond = $rows[0][0]['jsondata'];
	echo $jsond;
	exit;
    }
    public function insertTable() {
	// Gathering data from POST from python
	date_default_timezone_set("America/Los_Angeles");
	$fields[] = $this->f3->get('POST');
	$this->db->exec('insert into eng_bom (timestamp, jsondata) values (?,?)', array($fields[0]['id'],$fields[0]['data']));
	echo "Saved...";
	exit;
    }
}
?>

