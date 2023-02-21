<?php

class Admin extends Controller {

    public function ownr() {
                $this->f3->set('breadcrumbs','admin');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$owners = $this->db->exec('SELECT names FROM owners ORDER BY names');
	        $this->f3->set('navs','no');
		$this->f3->set('mode','sfownrupd');
		$this->f3->set('owners',$owners);
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','admin/owner.htm');
    }
    public function ownrupd() {
		// Getting POST variables, epoch and datetime for logs
		$ownr = $this->f3->get('POST');
		$id = $ownr['epoch'];
		$name =  $ownr['owner'];
		$epoch = time();
		$datet = date('y-m-d h:i',time());
		// Logging changes 
		$this->db->exec('INSERT INTO owner_log (timestamp,datetime,enc_log,names)VALUES(?,?,?,?)',array($epoch,$datet,$id,$name));
		// Changing the enc_log for engineering support
		$this->db->exec('UPDATE enc_log SET owner = ? WHERE Epoch = ?', array($name,$id));
		$this->f3->reroute('/sfadm');
    }
    public function sf() {
		$data[] = $this->db->exec('SELECT * FROM enc_log ORDER BY epoch desc');
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','admin');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
		$this->f3->set('t1',count($data[0])-50);
	        $this->f3->set('navs','no');
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
