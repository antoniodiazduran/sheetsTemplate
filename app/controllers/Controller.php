<?php

class Controller {

    protected $f3;
    protected $db;
    protected $rev;
    protected $aev;
    protected $sales;

    function beforeRoute() {
    }

    function afterRoute() {
	 //echo \Template::instance()->render('layout.htm');
	 echo \Template::instance()->render($this->f3->get('layout'));
    }

    function GSheetsInsert($sheetid,$row,$fileid) {
                //require '/home/antoniodiazduran/vendor/autoload.php';
                $client = Google_Spreadsheet::getClient('data/credentials.json');
                // Get the sheet instance by sheets_id and sheet name (antoniodiazduran)
                $sheet = $client->file($fileid)->sheet($sheetid);
		// Inserting data into Google Sheet
		$sheet->insert($row);
                // Fetch data from remote (or cache)
                //$sheet->fetch();
                // Return all rows in the sheet
                return $sheet->fetch(true)->items;
    }
    function GSheetsRead($sheetid,$fileid) {
                //require '/home/antoniodiazduran/vendor/autoload.php';
                $client = Google_Spreadsheet::getClient('data/credentials.json');
                // Get the sheet instance by sheets_id and sheet name (antoniodiazduran)
                $sheet = $client->file($fileid)->sheet($sheetid);
                // Fetch data from remote (or cache)
                $sheet->fetch();
                // Return all rows in the sheet
                return $sheet->items;
    }

    public function sendMail($to,$msg) {
      // In case any of our lines are larger than 70 characters, we should use wordwrap()
      // Always set content-type when sending HTML email
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $to = 'antonio.diazduranborja@revgroup.com,'.$to;
      $msg = wordwrap($msg, 300, "<br/>");
      // Send - to, subject, message
      $bool = mail($to,'Engineering owner/priority change', $msg, $headers);
    }

    function __construct() {
        $f3=Base::instance();

	// Enabling saving data to sqlite
	$db = new DB\SQL('sqlite:data/enc.sqlite');
	$rev = new DB\SQL('sqlite:data/rev.sqlite');
	$aev = new DB\SQL('sqlite:data/aev.sqlite');
	$sales = new DB\SQL('sqlite:data/sales.sqlite');

//        $db=new DB\SQL(
//            $f3->get('db_dns') . $f3->get('db_name'),
//            $f3->get('db_user'),
//            $f3->get('db_pass')
//        );

	$this->f3=$f3;
	$this->db=$db;
	$this->rev=$rev;
	$this->aev=$aev;
	$this->sales=$sales;

    }
}

?>
