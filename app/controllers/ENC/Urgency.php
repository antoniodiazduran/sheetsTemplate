<?php

namespace ENC;

class Urgency extends \Controller {

    public function list() {
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
    public function edit() {
                $this->f3->set('breadcrumbs','urg');
                $this->f3->set('epoch', $this->f3->get('PARAMS.id'));
                $this->f3->set('actual', $this->db->exec('SELECT Urgency FROM enc_log WHERE Epoch = ?',$this->f3->get('PARAMS.id') ));
                $urgency = $this->db->exec('SELECT names FROM urgency ORDER BY names');
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navadmin.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('urgency',$urgency);
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','admin/urgency.htm');
    }
    public function del() {
		$id = $this->f3->get('PARAMS.id');
		$del = $this->db->exec('DELETE FROM urgency WHERE epoch = ?',$id);
		$this->f3->reroute('urg/list');

    }
    public function add() {
		$data = $this->f3->get('POST');
		$epoch = time();
		$name = $data['names'];
		$this->db->exec('INSERT INTO urgency (epoch, names) VALUES (?,?)',array($epoch,$name));
		$this->f3->reroute('urg/list');
    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $record = $this->f3->get('POST');
                $id = $record['epoch'];
                $name =  $record['urgency'];
                $epoch = time();
                $datet = date('y-m-d h:i',time());
                // Logging changes
                $this->db->exec('INSERT INTO urgency_log (timestamp,datetime,enc_log,names)VALUES(?,?,?,?)',array($epoch,$datet,$id,$name));
                // Setting the sort criteria
                $sort = 1;
                if ($name == 'As available') { $sort = 3; }
                if ($name == 'See due date') { $sort = 2; }
                // Changing the enc_log for engineering support
                $this->db->exec('UPDATE enc_log SET urgency = ?, sort = ? WHERE Epoch = ?', array($name,$sort,$id));
                // Sending a notification email to owner of the record
                $owner = $this->db->exec('SELECT Owner from enc_log WHERE Epoch = ?', $id);
                $email = $this->db->exec('SELECT email FROM owners WHERE names = (SELECT Owner from enc_log WHERE Epoch = ?)', $id);
                $msg  = $owner[0]['Owner'].'<p>Your prioirty has change, click on the <a href="http://35.225.79.133/req/edit/'.$id.'">link</a> to see the changes.<br/> ';
                $msg .= 'This is just for your information, but you can update the record as needed.<p>Joey<br/>The admin<p>';
                // using parent function in Controller
                parent::sendMail('casey.kackman@eldorado-ca.com,'.$email[0]['email'],$msg);
                $this->f3->reroute('/sfadm');
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
