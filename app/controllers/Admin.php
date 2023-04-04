<?php

class Admin extends Controller {

    public function index() {
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','welcome.htm');  
    }
    public function ownr() {
                $this->f3->set('breadcrumbs','owr');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$owners = $this->db->exec('SELECT names FROM owners ORDER BY names');
	        $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','upd');
		$this->f3->set('owners',$owners);
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','admin/owner.htm');
    }
    public function urge() {
                $this->f3->set('breadcrumbs','urg');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$urgency = $this->db->exec('SELECT names FROM urgency ORDER BY names');
	        $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','upd');
		$this->f3->set('urgency',$urgency);
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','admin/urgency.htm');
    }
    public function ownrupd() {
		// Creating variable to send emails from controller class
		$main = New Controller;
		// Getting POST variables, epoch and datetime for logs
		$ownr = $this->f3->get('POST');
		$id = $ownr['epoch'];
		$name =  $ownr['owner'];
		$epoch = time();
		$datet = date('y-m-d h:i',time());
		// Logging changes
		$oldowner = $this->db->exec('SELECT email FROM owners WHERE names = (SELECT owner from enc_log WHERE Epoch = ?)', $id);
		$newowner = $this->db->exec('SELECT email FROM owners WHERE names = ?', $name);
		$to =  'casey.kackman@eldorado-ca.com,'.$oldowner[0]['email'].','.$newowner[0]['email'];
		$this->db->exec('INSERT INTO owner_log (timestamp,datetime,enc_log,names)VALUES(?,?,?,?)',array($epoch,$datet,$id,$name));
		// Sending a notification email to owner of the record
		$main->sendMail($to,$name.',<br/>You are the new owner of this task, Lucky you!!, click on the link to see the details.<br/> This is just for your information, but you can update the record as needed. <br/>Sorry, you cannot delete the task! :) <p>http://35.225.79.133/req/edit/'.$id);
		// Changing the enc_log for engineering support
		$this->db->exec('UPDATE enc_log SET owner = ? WHERE Epoch = ?', array($name,$id));
		$this->f3->reroute('/sfadm');
    }
    public function urgeupd() {
		// Creating variable to send emails from controller class
		$main = New Controller;
		// Getting POST variables, epoch and datetime for logs
		$record = $this->f3->get('POST');
		$id = $record['epoch'];
		$name =  $record['urgency'];
		$epoch = time();
		$datet = date('y-m-d h:i',time());
		// Logging changes
		$this->db->exec('INSERT INTO urgency_log (timestamp,datetime,enc_log,names)VALUES(?,?,?,?)',array($epoch,$datet,$id,$name));
		// Changing the enc_log for engineering support
		$sort = 1;
		if ($name == 'As available') { $sort = 3; }
		if ($name == 'See due date') { $sort = 2; }
		$this->db->exec('UPDATE enc_log SET urgency = ?, sort = ? WHERE Epoch = ?', array($name,$sort,$id));
		// Sending a notification email to owner of the record
		$main->sendMail('casey.kackman@eldorado-ca.com','Your prioirty has change, click on the link to see the changes.<br/> This is just for your information, but you can update the record as needed.<p>http://35.225.79.133/req/edit/'.$id);
		$this->f3->reroute('/sfadm');
    }
    public function sf() {
		$data[] = $this->db->exec("SELECT * FROM enc_log ORDER BY display DESC, datetime ASC");
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','owr');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
		$this->f3->set('t1',count($data[0])-250);
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navadmin.htm');
	        $this->f3->set('customer','yes');
                $this->f3->set('headers','admin/headers.htm');
                $this->f3->set('fields','admin/fields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','admin/list.htm');
    }
    public function apidbs() {
		$data[] = $this->db->exec('SELECT * FROM enc_log ORDER BY epoch desc');
		$json_data = json_encode($data);
		//var_dump($json_data);
		exit;
    }

}
?>
