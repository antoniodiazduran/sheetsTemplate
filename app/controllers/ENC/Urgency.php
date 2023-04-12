<?php

namespace ENC;

class Urgency extends \Controller {

    public function urgelist() {
                $this->f3->set('breadcrumbs','urg');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$urgency = $this->db->exec('SELECT epoch,names FROM urgency ORDER BY names');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','create');
		$this->f3->set('urgency',$urgency);
		$this->f3->set('layout','admin.htm');
                $this->f3->set('content','urgency/edit.htm');
    }
    public function urgedel() {
		$id = $this->f3->get('PARAMS.id');
		$del = $this->db->exec('DELETE FROM urgency WHERE epoch = ?',$id);
		$this->f3->reroute('urg/list');

    }
    public function urgeadd() {
		$data = $this->f3->get('POST');
		$epoch = time();
		$name = $data['names'];
		$this->db->exec('INSERT INTO urgency (epoch, names) VALUES (?,?)',array($epoch,$name));
		$this->f3->reroute('urg/list');
    }
    public function urgeupd() {
		// Getting POST variables, epoch and datetime for logs
		$record = $this->f3->get('POST');
		$id = $record['epoch'];
		$name =  $record['urgency'];
		$epoch = time();
		$datet = date('y-m-d h:i',time());
		// Logging changes 
		$this->db->exec('INSERT INTO urgency_log (timestamp,datetime,enc_log,names)VALUES(?,?,?,?)',array($epoch,$datet,$id,$name));
		// Changing the enc_log for engineering support
		$this->db->exec('UPDATE enc_log SET urgency = ? WHERE Epoch = ?', array($name,$id));
		$this->f3->reroute('/urg/main');
    }
    public function sf() {
		$data[] = $this->db->exec('SELECT * FROM enc_log ORDER BY epoch desc');
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','admin');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
		$this->f3->set('t1',count($data[0])-50);
	        $this->f3->set('navs','yes');
	        $this->f3->set('customer','yes');
                $this->f3->set('headers','admin/headers.htm');
                $this->f3->set('fields','admin/fields.htm');
		$this->f3->set('layout','admin.htm');
                $this->f3->set('content','admin/list.htm');
    }
}
?>
