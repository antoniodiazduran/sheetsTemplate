<?php

class Owner extends Controller {

    public function ownrlist() {
                $this->f3->set('breadcrumbs','owr');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$owners = $this->db->exec('SELECT epoch,names,area,email FROM owners ORDER BY names');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','create');
		$this->f3->set('owners',$owners);
		$this->f3->set('layout','admin.htm');
                $this->f3->set('content','owner/edit.htm');
    }
    public function ownrdel() {
		$id = $this->f3->get('PARAMS.id');
		$del = $this->db->exec('DELETE FROM owners WHERE epoch = ?',$id);
		$this->f3->reroute('owr/list');

    }
    public function ownradd() {
		$data = $this->f3->get('POST');
		$epoch = time();
		$name = $data['names'];
		$area = $data['area'];
		$email = $data['email'];
		$this->db->exec('INSERT INTO owners (epoch, names, area, email) VALUES (?,?,?,?)',array($epoch,$name,$area,$email));
		$this->f3->reroute('owr/list');
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
		$this->f3->reroute('/owr/main');
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
