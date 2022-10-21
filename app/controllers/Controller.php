<?php

class Controller {

    protected $f3;
    protected $db;

    function beforeRoute() {
    }

    function afterRoute() {
	 echo \Template::instance()->render('layout.htm');
    }

    function GSheetsInsert($sheetid,$row) {
                //require '/home/antoniodiazduran/vendor/autoload.php';
                $client = Google_Spreadsheet::getClient('/home/antoniodiazduran/data/credentials.json');
                // Get the sheet instance by sheets_id and sheet name (antoniodiazduran)
                $sheet = $client->file('1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w')->sheet($sheetid);
		// Inserting data into Google Sheet
		$sheet->insert($row);
                // Fetch data from remote (or cache)
                //$sheet->fetch();
                // Return all rows in the sheet
                return $sheet->fetch(true)->items;
    }
    function GSheetsRead($sheetid) {
                //require '/home/antoniodiazduran/vendor/autoload.php';
                $client = Google_Spreadsheet::getClient('/home/antoniodiazduran/data/credentials.json');
                // Get the sheet instance by sheets_id and sheet name (antoniodiazduran)
                $sheet = $client->file('1UtOYZWXSB53MdP_0Nyil2sCbuDrJvh7TJ7z4duMLp_w')->sheet($sheetid);
                // Fetch data from remote (or cache)
                $sheet->fetch();
                // Return all rows in the sheet
                return $sheet->items;
    }

    function __construct() {
        $f3=Base::instance();

//        $db=new DB\SQL(
//            $f3->get('db_dns') . $f3->get('db_name'),
//            $f3->get('db_user'),
//            $f3->get('db_pass')
//        );

	$this->f3=$f3;
//	$this->db=$db;

    }
}

?>
