<?php

class Esfs extends Controller {

    public function all() {
	 	$data[] = $this->GSheetsRead('enc.esfs');
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','esfs');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
	        $this->f3->set('tdate','no');
	        $this->f3->set('customer','no');
		$this->f3->set('t1',count($data[0])-150);
                $this->f3->set('headers','esfs/headers.htm');
                $this->f3->set('fields','esfs/fields.htm');
                $this->f3->set('content','esfs/list.htm');
    }
    public function form() {
                $this->f3->set('breadcrumbs','esfs');
                $this->f3->set('mode','create');
                $this->f3->set('content','esfs/form.htm');
    }
    public function insertRow() {
		// Gathering data from form
		$fields[] = $this->f3->get('POST');
		// Creating array to insert into Google Sheets
		date_default_timezone_set("America/Los_Angeles");
		$row = array(
                        'DateTime'=>date('m/d/Y H:i:s'),
                        'Line'=>$fields[0]['line'],
                        'Description'=>$fields[0]['description'],
                        'Customer'=>$fields[0]['customer'],
                        'UnitNumber'=>$fields[0]['unitnumber'],
                        'DueDate'=>$fields[0]['duedate'],
                        'Urgency'=>$fields[0]['urgency'],
                        'ECN'=>$fields[0]['ecn'],
                        'Owner'=>$fields[0]['owner'],
                        'Notes'=>$fields[0]['notes']
                	);
		// Inserting data into Google Sheets
		$data[] = $this->GSheetsInsert('enc.esfs',$row);
		// Displaying new data
		$this->f3->reroute('/esfs');
    }
}
?>

