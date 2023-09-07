<?php

namespace AEV;

class Notes extends \Controller {

    public function edit() {
                $this->f3->set('breadcrumbs','aev/nts');
                $this->f3->set('epoch',$this->f3->get('PARAMS.id'));
                $record = $this->aev->exec('SELECT * FROM notes WHERE enc_log = ?',$this->f3->get('PARAMS.id'));
                $this->f3->set('navs','no');
                $this->f3->set('nav_menu','aev/esfs/nav.htm');
                $this->f3->set('mode','upd');
                $this->f3->set('record',$record);
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','aev/esfs/notes/form.htm');
    }
    public function upd() {
                $this->f3->set('breadcrumbs','/aev/nts');
                $data = $this->f3->get('POST');
                $epoch = time();
                $datetime = date('Y-m-d h:i',time());
                $note = $data['notes'];
                $rel = $data['relation'];
                $record = $this->aev->exec('INSERT INTO notes (epoch, datetime, enc_log, notes) values (?,?,?,?)', array($epoch, $datetime, $rel, $note) );
                //$this->f3->reroute('/aev/sf/admin');
		// Setting up variables for the display
                $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','navbuyers.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','aev/status.htm');
    }
    public function delete() {
                $record = $this->aev->exec('DELETE FROM notes WHERE Epoch = ?',$this->f3->get('PARAMS.id'));
		// Setting up variables for the display
                $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','navbuyers.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('result','Record Deleteed !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','aev/status.htm');
    }

}
?>
