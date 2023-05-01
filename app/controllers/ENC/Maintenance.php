<?php

namespace ENC;

class Maintenance extends \Controller {

    public function maint() {
                $this->f3->set('breadcrumbs','maint');
                $this->f3->set('field','all');
	        $this->f3->set('navs','no');
	        $this->f3->set('customer','no');
                $this->f3->set('headers','maint/headers.htm');
                $this->f3->set('fields','maint/fields.htm');
		$this->f3->set('layout','kiosk.htm');
                $this->f3->set('content','maint/ajax.htm');
    }
    public function form() {
                $eng[] = $this->GSheetsRead('enc.form','15pDtT-vN8NSOPXNz0ZqWI73tHAxSf2S3KS5zRWuYpj0');
                $this->f3->set('breadcrumbs','enc/mrf');
                $this->f3->set('navs','no');
                $this->f3->set('layout','layout.htm');
                $this->f3->set('mode','create');
                $this->f3->set('content','maint/form.htm');
    }
    public function apiall() {
		$data[] = $this->GSheetsRead('enc.sort','15pDtT-vN8NSOPXNz0ZqWI73tHAxSf2S3KS5zRWuYpj0');
		$json_data = json_encode($data);
		$epoch = time();
		$this->db->exec('insert into maint_support (timestamp, jsondata) values (?,?)', array($epoch,$json_data));
		echo $json_data;
//		echo "<p>";
//		$array_data = json_decode($json_data);
//		var_dump($data);
//		echo "<p>";
//		var_dump($array_data);
		exit;

    }
    public function insertRow() {
                // Gathering data from form
                date_default_timezone_set("America/Los_Angeles");
                $fields[] = $this->f3->get('POST');
                $sorts = "";
                if($fields[0]['urgency'] == 'Line Stopper') { $sorts = 1; }
                if($fields[0]['urgency'] == 'See due date') { $sorts = 2; }
                if($fields[0]['urgency'] == 'As available') { $sorts = 3; }
                // Creating array to insert into Google Sheets
                $row = array(
                        'DateTime'=>date('m/d/Y H:i:s'),
                        'Line'=>$fields[0]['line'],
                        'Description'=>$fields[0]['description'],
                        'DueDate'=>$fields[0]['duedate'],
                        'Urgency'=>$fields[0]['urgency'],
                        'Requestor'=>$fields[0]['requestor'],
                        'Owner'=>'TBD',
                        'Notes'=>$fields[0]['notes'],
                        'Display'=>'y',
                        'Sort'=>$sorts
                        );
                // Inserting data into Google Sheets
                $data[] = $this->GSheetsInsert('enc.form',$row,'15pDtT-vN8NSOPXNz0ZqWI73tHAxSf2S3KS5zRWuYpj0');
                // Displaying new data
                $this->f3->reroute('/enc/mrf');
    }
}
?>
