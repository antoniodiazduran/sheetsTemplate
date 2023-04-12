<?php

namespace ENC;

class Request extends \Controller {

    public function edit() {
                $this->f3->set('breadcrumbs','req');
		$this->f3->set('epoch',$this->f3->get('PARAMS.id'));
		$record = $this->db->exec('SELECT * FROM enc_log WHERE Epoch = ?',$this->f3->get('PARAMS.id'));
	        $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','upd');
		$this->f3->set('record',$record[0]);
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','request/form.htm');
    }
    public function upd() {
		// Getting POST variables, epoch and datetime for logs
		$reqs = $this->f3->get('POST');
		if ($reqs['urgency'] == 'Line Stopper') { $reqs['sort'] = 1; }
		if ($reqs['urgency'] == 'See due date') { $reqs['sort'] = 2; }
		if ($reqs['urgency'] == 'As available') { $reqs['sort'] = 3; }
		$rowv = array(
                        $reqs['line'],
                        $reqs['description'],
                        $reqs['customer'],
                        $reqs['unitnumber'],
                        $reqs['duedate'],
                        $reqs['urgency'],
                        $reqs['ecn'],
                        $reqs['requestor'],
                        $reqs['notes'],
                        $reqs['display'],
                        $reqs['sort'],
			$reqs['epoch']
                        );
                // Inserting into sqlite database
                $sql_update  = "UPDATE enc_log ";
                $sql_update .= "SET Line=?, Description=?, Customer=?, UnitNumber=?,";
		$sql_update .= "DueDate=?, Urgency=?, ECN=?, Requestor=?, Notes=?, Display=?, Sort=? ";
                $sql_update .= "WHERE Epoch = ?";
                $this->db->exec($sql_update,$rowv);
		$this->f3->reroute('/sfadm');
    }

}
?>
