<?php

namespace AEV;

class Admin extends \Controller {

    public function list() {
		$sqlstr  = 'SELECT Epoch, DateTime, Line, Description, substr(UnitNumber,1,15) UnitNumber, Customer, DueDate, Urgency, Requestor, Owner, Designer, Solution, Cause, Origin ';
		$sqlstr .= 'FROM eng_log ';
		$sqlstr .= 'WHERE display = "y" ';
		$sqlstr .= 'ORDER BY sort ASC, DueDate ASC ';
		$data[] = $this->aev->exec($sqlstr);
                $this->f3->set('breadcrumbs','aev/sf/admin');
                $this->f3->set('field','all');
	        $this->f3->set('navs','yes');
	        $this->f3->set('navmenu','aev/esfs/nav.htm');
		$this->f3->set('tdate','yes');
	        $this->f3->set('customer','yes');
                $this->f3->set('headers','aev/esfs/headers.htm');
                $this->f3->set('fields','aev/esfs/fields.htm');
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('layout','layout.htm');
		$this->f3->set('details',$data);
                $this->f3->set('content','aev/esfs/list.htm');
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
                        $reqs['requestor'],
                        $reqs['notes'],
                        $reqs['owner'],
                        $reqs['designer'],
                        $reqs['solution'],
                        $reqs['cause'],
			$reqs['sort'],
                        $reqs['epoch']
                        );
                // Inserting into sqlite database
                $sql_update  = "UPDATE eng_log ";
                $sql_update .= "SET Line=?, Description=?, Customer=?, UnitNumber=?,";
                $sql_update .= "DueDate=?, Urgency=?, Requestor=?, Notes=?, Owner=?, Designer=?,  Solution=?, Cause=?, Sort=? ";
                $sql_update .= "WHERE Epoch = ?";
                $this->aev->exec($sql_update,$rowv);
		// Setting up variables for the display
                $this->f3->set('nav_menu','navbuyers.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('result','Record Updated !');
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','materials/status.htm');
    }
    public function edit() {
		$epoch = $this->f3->get('PARAMS.id');
		$sqlstr = 'SELECT Epoch, DateTime, Line, Description, UnitNumber, Customer, DueDate, Urgency, Requestor, Owner, Designer, Solution, Cause, Origin, Notes FROM eng_log WHERE Epoch = ?';
		$data = $this->aev->exec($sqlstr, $epoch);
                $this->f3->set('breadcrumbs','aev/esfs');
		$this->f3->set('mode','update');
	        $this->f3->set('navs','no');
	        $this->f3->set('navmenu','aev/esfs/nav.htm');
                $this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('layout','layout.htm');
		$this->f3->set('record',$data[0]);
                $this->f3->set('content','aev/esfs/edit.htm');
    }
    public function apidbs() {
		$data[] = $this->aev->exec('SELECT Line, Description, substr(UnitNumber,1,15) UnitNumber, substr(Customer,1,10), DueDate, Urgency, Requestor, Owner FROM eng_log WHERE display = "y" ORDER BY sort ASC, DueDate ASC');
		$json_data = json_encode($data);
		echo $json_data;
		exit;
    }

}
?>
