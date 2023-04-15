<?php
namespace MAT;

class Screen extends \Controller {

    public function all() {
	 	$data[] = $this->GSheetsRead('enc.sort','1QrOuTaG8r_1ZjIdujTVzXbiiydjk-2rk8XxZpprOQD0');
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','shopfloor');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
	        $this->f3->set('tdate','no');
	        $this->f3->set('navs','no');
	        $this->f3->set('customer','no');
		$this->f3->set('t1',count($data[0])-50);
                $this->f3->set('headers','shopfloor/headers.htm');
                $this->f3->set('fields','shopfloor/fields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','shopfloor/list.htm');
    }
    public function sf() {
                $this->f3->set('breadcrumbs','shopfloor');
                $this->f3->set('field','all');
	        $this->f3->set('navs','no');
	        $this->f3->set('customer','no');
                $this->f3->set('headers','shopfloor/headers.htm');
                $this->f3->set('fields','shopfloor/fields.htm');
		$this->f3->set('layout','kiosk.htm');
                $this->f3->set('content','shopfloor/ajax.htm');
    }
    public function apidbs() {
		$data[] = $this->db->exec('SELECT Line, Description, substr(UnitNumber,1,15) UnitNumber, substr(Customer,1,10), DueDate, Urgency, Requestor, Owner FROM enc_log WHERE display = "y" ORDER BY sort ASC, DueDate ASC');
		$json_data = json_encode($data);
		echo $json_data;
		exit;
    }
    public function apiall() {
		$data[] = $this->GSheetsRead('enc.sort','1QrOuTaG8r_1ZjIdujTVzXbiiydjk-2rk8XxZpprOQD0');
		$json_data = json_encode($data);
		$epoch = time();
		$this->db->exec('insert into eng_support (timestamp, jsondata) values (?,?)', array($epoch,$json_data));
		echo $json_data;
		exit;

    }
}
?>
