<?php

namespace ENC;

class Owner extends \Controller {

    public function list() {
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
    public function edit() {
                $this->f3->set('breadcrumbs','owr');
                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $this->f3->set('actual', $this->db->exec('SELECT Owner FROM enc_log WHERE Epoch = ?',$this->f3->get('PARAMS.id') ));
                $owners = $this->db->exec('SELECT names FROM owners ORDER BY names');
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navadmin.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('owners',$owners);
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','admin/owner.htm');
    }
    public function del() {
		$id = $this->f3->get('PARAMS.id');
		$del = $this->db->exec('DELETE FROM owners WHERE epoch = ?',$id);
		$this->f3->reroute('owr/list');

    }
    public function add() {
		$data = $this->f3->get('POST');
		$epoch = time();
		$name = $data['names'];
		$area = $data['area'];
		$email = $data['email'];
		$this->db->exec('INSERT INTO owners (epoch, names, area, email) VALUES (?,?,?,?)',array($epoch,$name,$area,$email));
		$this->f3->reroute('owr/list');
    }
    public function upd() {
                // Getting POST variables, epoch and datetime for logs
                $ownr = $this->f3->get('POST');
                $id = $ownr['epoch'];
                $name =  $ownr['owner'];
                $epoch = time();
                $datet = date('y-m-d h:i',time());
                // Getting old and new owners emails from the owners table
                $oldowner = $this->db->exec('SELECT email FROM owners WHERE names = (SELECT owner from enc_log WHERE Epoch = ?)', $id);
                $newowner = $this->db->exec('SELECT email FROM owners WHERE names = ?', $name);
                $description = $this->db->exec('SELECT Description FROM enc_log WHERE Epoch = ?',$id);
                $to =  'casey.kackman@eldorado-ca.com,'.$oldowner[0]['email'].','.$newowner[0]['email'];
                // Adding notes to the notification email to owner of the record
                $msg  = ',<p>You are the new owner of <a href="http://35.225.79.133/req/edit/'.$id.'"><b>'.$description[0]['Description'].'</b></a>, Lucky you!!, click on the link to see the details.<br/> ';
                $msg .= 'This is just for your information, but you can update the record as needed. <br/> ';
                $msg .= 'Sorry, you cannot delete the task!<p>';
                $msg .= 'If you wish to add notes to the record, you can click <a href="http://35.225.79.133/nts/edit/'.$id.'">here</a><p>Joey<br/>The admin<p>';
                // Sending the email using parent function in Controller
                parent::sendMail($to,$name.$msg);
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
	        $this->f3->set('navs','yes');
	        $this->f3->set('customer','yes');
                $this->f3->set('headers','admin/headers.htm');
                $this->f3->set('fields','admin/fields.htm');
		$this->f3->set('layout','admin.htm');
                $this->f3->set('content','admin/list.htm');
    }
}
?>
