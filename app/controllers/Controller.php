<?php

class Controller {

    protected $usr;
    protected $f3;
    protected $db;
    protected $rev;
    protected $aev;
    protected $sales;
    protected $dmz;
    protected $bpllc;

//  *****************************
//  Secure Before and After route
//  *****************************

     function beforeroute() {
		if($this->f3->get('SESSION.logged_in'))
		{
			if(time() - $this->f3->get('SESSION.timestamp') > $this->f3->get('auto_logout'))
			{
				$this->f3->clear('SESSION');
				$this->f3->reroute('/login');
			}
			else {
				$this->f3->set('SESSION.timestamp', time());
			}
		}
		$csrf_page = $this->f3->get('PARAMS.0'); //URL route !with preceding slash!

/*
		if( NULL === $this->f3->get('POST.session_csrf') )
		{
			$this->f3->CSRF = $this->f3->session->csrf();
			$this->f3->copy('CSRF','SESSION.'.$csrf_page.'.csrf');
		}
		if ($this->f3->VERB==='POST')
		{
			if(  $this->f3->get('POST.session_csrf') ==  $this->f3->get('SESSION.'.$csrf_page.'.csrf') )
			{	// Things check out! No CSRF attack was detected.
				$this->f3->set('CSRF', $this->f3->session->csrf()); // Reset csrf token for next post request
				$this->f3->copy('CSRF','SESSION.'.$csrf_page.'.csrf');  // copy the token to the variable
			}
			else
			{	// DANGER: CSRF attack!
				$this->f3->error(403);
			}
		}

*/
		// Access routes 
		$access=Access::instance();
		$access->policy('allow'); // allow access to all routes by default
		$access->deny('/admin*');

		// admin routes
		$access->allow('/admin*','100'); //100 = admin ; 10 = superuser ; 1 = user
		$access->deny('/user*');
		$access->allow('/mat*',['100','10','1']);

		// user login routes
		$access->allow('/user*',['100','10','1']);
		$access->authorize($this->f3->exists('SESSION.user_type') ? $this->f3->get('SESSION.user_type') : 0 );
    }

    function o_beforeroute() {
	// no security
    }
    function afterroute() {
	//echo \Template::instance()->render('layout.htm');
	echo Template::instance()->render($this->f3->get('layout'));
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

    public function isMobile()  {
         return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    public function sendMail($to,$msg) {
      // In case any of our lines are larger than 70 characters, we should use wordwrap()
      // Always set content-type when sending HTML email
      $headers[] = "MIME-Version: 1.0";
      $headers[] = "Content-type:text/html;charset=iso-8859-1";
      $headers[] = "Reply-To:joeycamanei@gmail.com";
      $headers[] = "From: joeycamanei@gmail.com";
      $to = 'antonio.diazduranborja@revgroup.com,'.$to;
      $msg = wordwrap($msg, 300, "<br/>\n");
      // Send - to, subject, message
      $bool = mail($to,'Engineering owner/priority change', $msg, implode("\r\n",$headers));
    }

    function __construct() {
        $f3=Base::instance();

	// Enabling saving data to sqlite
	$usr = new DB\SQL('sqlite:data/users.sqlite');
	$db = new DB\SQL('sqlite:data/enc.sqlite');
//	$rev = new DB\SQL('sqlite:data/rev.sqlite');
	$aev = new DB\SQL('sqlite:data/aev.sqlite');
	$sales = new DB\SQL('sqlite:data/sales.sqlite');
	$bpllc = new DB\SQL('sqlite:data/bpllc.sqlite');

        // De-Militirizaed Zone for public pages
	$dmz = array('/mat/screen','/mat/receiving','/sf');

	$this->usr=$usr;
	$this->dmz=$dmz;
	$this->f3=$f3;
	$this->db=$db;
//	$this->rev=$rev;
	$this->aev=$aev;
	$this->sales=$sales;
	$this->bpllc=$bpllc;

    }
}

?>
