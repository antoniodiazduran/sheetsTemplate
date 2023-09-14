<?php

namespace AEV;

class Owner extends \Controller {

    public function name() {
                $this->f3->set('breadcrumbs','aev/owr');
                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $sqlstr = 'SELECT Owner FROM eng_log WHERE Epoch = ?';
                $this->f3->set('actual', $this->aev->exec($sqlstr ,$this->f3->get('PARAMS.id') ));
                $owners = $this->aev->exec('SELECT names FROM owners ORDER BY names');
                $this->f3->set('navs','no');
                $this->f3->set('navmenu','aev/esfs/nav.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('owners',$owners);
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','aev/esfs/owner.htm');
    }
    public function chg() {
                // Getting POST variables, epoch and datetime for logs
                $ownr = $this->f3->get('POST');
                $id = $ownr['epoch'];
                $name =  $ownr['owner'];
                $epoch = time();
                $datet = date('y-m-d h:i',time());
                // Getting old and new owners emails from the owners table
                $oldowner = $this->aev->exec('SELECT email FROM owners WHERE names = (SELECT owner from eng_log WHERE Epoch = ?)', $id);
                $newowner = $this->aev->exec('SELECT email FROM owners WHERE names = ?', $name);
                $description = $this->aev->exec('SELECT Description FROM eng_log WHERE Epoch = ?',$id);
                //$to =  'antonio.diazduranborja@revgroup.com,'.$oldowner[0]['email'].','.$newowner[0]['email'];
                $to =  'antonio.diazduranborja@revgroup.com,brian.billings@aev.com,';
                // Adding notes to the notification email to owner of the record
                $msg  = ',<p>You are the new owner of <a href="http://info.diaz.works/aev/esfs/edit/'.$id.'"><b>'.$description[0]['Description'].'</b></a>, Lucky you!! </br>';
                $msg .= 'This is just for your information, but you can update the record as needed. <br/> ';
                $msg .= 'Sorry, you cannot delete the task!<p>';
                $msg .= 'Please add or remove comments following the link above<p>Joey<br/>The administrator';
                // Sending the email using parent function in Controller
                parent::sendMail($to,$name.$msg);
                // Logging changes
                $this->aev->exec('INSERT INTO owner_log (timestamp,datetime,eng_log,names)VALUES(?,?,?,?)',array($epoch,$datet,$id,$name));
                // Changing the eng_log for engineering support
                $this->aev->exec('UPDATE eng_log SET owner = ? WHERE Epoch = ?', array($name,$id));
                $this->f3->reroute('/aev/sf/admin');
    }
    public function list() {
                $this->f3->set('breadcrumbs','aev/owr');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$owners = $this->aev->exec('SELECT epoch,names,area,email FROM owners ORDER BY names');
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','aev/esfs/nav.htm');
		$this->f3->set('mode','create');
		$this->f3->set('owners',$owners);
		$this->f3->set('layout','admin.htm');
                $this->f3->set('content','aev/esfs/owner/edit.htm');
    }
    public function del() {
		$id = $this->f3->get('PARAMS.id');
		$del = $this->aev->exec('DELETE FROM owners WHERE epoch = ?',$id);
		$this->f3->reroute('/aev/owr/list');

    }
    public function add() {
		$data = $this->f3->get('POST');
		$epoch = time();
		$name = $data['names'];
		$area = $data['area'];
		$email = $data['email'];
		$this->aev->exec('INSERT INTO owners (epoch, names, area, email) VALUES (?,?,?,?)',array($epoch,$name,$area,$email));
		$this->f3->reroute('/aev/owr/list');
    }
    public function sf() {
		$data[] = $this->aev->exec('SELECT * FROM enc_log ORDER BY epoch desc');
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
