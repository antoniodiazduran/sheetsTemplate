<?php

namespace ENC;

class Admin extends \Controller {

    public function index() {
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','welcome.htm');  
    }

    public function sch() {
		$e = $this->f3->get('PARAMS.e');
		$j = $this->f3->get('PARAMS.j');
		$s = $this->f3->get('PARAMS.s');
		$l = $this->f3->get('PARAMS.l');
		$d = $this->f3->get('PARAMS.d');
		$c = $this->f3->get('PARAMS.c');
		$v = $this->f3->get('PARAMS.v');
		$str = "INSERT INTO sch_board (Epoch, Jobnumber, Startdate, Line, Dealername, Customername, Vin) VALUES (?,?,?,?,?,?,?)";
	echo $str;
		$this->aev->exec($str,array($e,$j,$s,$l,$d,$c,$v) );
	exit;
    }

    public function ownr() {
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
    public function urge() {
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
    public function ownrupd() {
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
    public function urgeupd() {
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
