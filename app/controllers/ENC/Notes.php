<?php

namespace ENC;

class Notes extends \Controller {

    public function edit() {
                $this->f3->set('breadcrumbs','nts');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$record = $this->db->exec('SELECT * FROM notes WHERE enc_log = ?',$this->f3->get('PARAMS.id'));
	        $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','upd');
		$this->f3->set('record',$record);
		$this->f3->set('layout','admin.htm');
                $this->f3->set('content','notes/form.htm');
    }
    public function upd() {
                $this->f3->set('breadcrumbs','nts');
		$data = $this->f3->get('POST');
		$epoch = time();
		$datetime = date('Y-m-d h:i',time());
		$note = $data['notes'];
		$rel = $data['relation'];
		$record = $this->db->exec('INSERT INTO notes (epoch, datetime, enc_log, notes) values (?,?,?,?)', array($epoch, $datetime, $rel, $note) );
	        $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','upd');
		$this->f3->reroute('/sfadm');		
    }
    public function delete() {
		$record = $this->db->exec('DELETE FROM notes WHERE Epoch = ?',$this->f3->get('PARAMS.id'));
		$this->f3->reroute('/sfadm');
    }

}
?>
