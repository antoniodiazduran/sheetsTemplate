<?php

class Esfs extends Controller {

    public function all() {
	 	$data[] = $this->GSheetsRead('enc.sort','1QrOuTaG8r_1ZjIdujTVzXbiiydjk-2rk8XxZpprOQD0');
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','esfs');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
	        $this->f3->set('tdate','no');
	        $this->f3->set('navs','no');
	        $this->f3->set('customer','yes');
		$this->f3->set('t1',count($data[0])-50);
                $this->f3->set('headers','esfs/headers.htm');
                $this->f3->set('fields','esfs/fields.htm');
                $this->f3->set('content','esfs/list.htm');
    }
    public function form() {
	 	$eng[] = $this->GSheetsRead('enc.eng','1QrOuTaG8r_1ZjIdujTVzXbiiydjk-2rk8XxZpprOQD0');
		$this->f3->set('engineers',$eng[0]);
                $this->f3->set('breadcrumbs','esfs');
	        $this->f3->set('navs','yes');
                $this->f3->set('mode','create');
                $this->f3->set('content','esfs/form.htm');
    }
    public function insertRow() {
		// Gathering data from form
		date_default_timezone_set("America/Los_Angeles");
		$fields[] = $this->f3->get('POST');
		$sorts = "";
		if($fields[0]['urgency'] == 'Line Stopper') { $sorts = 1; }
		if($fields[0]['urgency'] == 'See due date') { $sorts = 2; }
		if($fields[0]['urgency'] == 'As availble') { $sorts = 3; }
		// Creating array to insert into Google Sheets
		$row = array(
                        'DateTime'=>date('m/d/Y H:i:s'),
                        'Line'=>$fields[0]['line'],
                        'Description'=>$fields[0]['description'],
                        'Customer'=>$fields[0]['customer'],
                        'UnitNumber'=>$fields[0]['unitnumber'],
                        'DueDate'=>$fields[0]['duedate'],
                        'Urgency'=>$fields[0]['urgency'],
                        'ECN'=>$fields[0]['ecn'],
                        'Requestor'=>$fields[0]['requestor'],
                        'Owner'=>'TBD',
                        'Notes'=>$fields[0]['notes'],
                        'Display'=>'y',
			'Sort'=>$sorts
                	);
		// Inserting data into Google Sheets
		$data[] = $this->GSheetsInsert('enc.esfs',$row,'1QrOuTaG8r_1ZjIdujTVzXbiiydjk-2rk8XxZpprOQD0');
		// Displaying new data
		$this->f3->reroute('/esfs');
    }
}
?>

