<?php

namespace AEV;

class Shopfloor extends \Controller {

    public function sf() {
                $this->f3->set('breadcrumbs','aev/shopfloor');
                $this->f3->set('field','all');
	        $this->f3->set('navs','no');
	        $this->f3->set('customer','no');
//                $this->f3->set('headers','aev/shopfloor/headers.htm');
 //               $this->f3->set('fields','aev/shopfloor/fields.htm');
		$this->f3->set('layout','kiosk.htm');
                $this->f3->set('content','aev/shopfloor/ajax.htm');
    }
    public function apidbs() {
		$data[] = $this->aev->exec('SELECT Line, Description, substr(UnitNumber,1,15) UnitNumber, substr(Customer,1,10), DueDate, Urgency, Requestor, Owner FROM eng_log WHERE display = "y" ORDER BY sort ASC, DueDate ASC');
		$json_data = json_encode($data);
		echo $json_data;
		exit;
    }
}
?>
