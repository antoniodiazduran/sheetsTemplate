<?php

namespace ENC;

class Status extends \Controller {

    public function edit() {
                $this->f3->set('breadcrumbs','sta');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$record = $this->db->exec('SELECT * FROM enc_log WHERE Epoch = ?',$this->f3->get('PARAMS.id'));
	        $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','upd');
		$this->f3->set('record',$record);
		$this->f3->set('layout','admin.htm');
                $this->f3->set('content','status/form.htm');
    }
    public function upd() {
                $this->f3->set('breadcrumbs','nts');
		$data = $this->f3->get('POST');
		$epoch = time();
		$relation = $data['epoch'];
		$status = $data['display'];
		$openclose = 'closed';
		if ($status == 'y') { $openclose = 'opened'; }
		$completedate = $data['completedate'];
		$record = $this->db->exec('INSERT INTO complete_log (epoch, enc_log, completedate, status) VALUES (?,?,?,?)',array($epoch,$relation,$completedate,$openclose) );
		$record = $this->db->exec('UPDATE enc_log SET completedate = ?, Display = ?  WHERE epoch = ?',array($completedate, $status, $relation) );
	        $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','upd');
		$this->f3->reroute('/sfadm');
    }

}
?>
