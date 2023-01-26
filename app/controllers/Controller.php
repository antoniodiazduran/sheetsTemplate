<?php

class Controller {

    protected $f3;
    protected $db;
    protected $rev;

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

    function __construct() {
        $f3=Base::instance();

	// Enabling saving data to sqlite
	$db = new DB\SQL('sqlite:data/enc.sqlite');
	$rev = new DB\SQL('sqlite:data/rev.sqlite');

//        $db=new DB\SQL(
//            $f3->get('db_dns') . $f3->get('db_name'),
//            $f3->get('db_user'),
//            $f3->get('db_pass')
//        );

	$this->f3=$f3;
	$this->db=$db;
	$this->rev=$rev;

    }
}

?>
