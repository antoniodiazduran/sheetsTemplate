<?php

class Controller {

    protected $usr;
    protected $f3;
//    protected $db;
//    protected $rev;
//    protected $aev;
//    protected $sales;
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
                //var_dump($this->f3->get('SESSION'));
                if( NULL === $this->f3->get('POST.session_csrf') )
                {
                        $this->f3->CSRF = $this->f3->session->csrf();
                        //$this->f3->copy('CSRF','SESSION.'.$csrf_page.'.csrf');  // used for same Controller without mixing GET & POST
                        $this->f3->copy('CSRF','SESSION.session_csrf');
                }

                if ($this->f3->VERB==='POST')
                {
                        //if(  $this->f3->get('POST.session_csrf') ==  $this->f3->get('SESSION.'.$csrf_page.'.csrf') ) 
                        if(  $this->f3->get('POST.session_csrf') ==  $this->f3->get('SESSION.session_csrf') ) 
                        {       // Things check out! No CSRF attack was detected.
                                $this->f3->set('CSRF', $this->f3->session->csrf()); // Reset csrf token for next post request
                                //$this->f3->copy('CSRF','SESSION.'.$csrf_page.'.csrf');  // copy the token to the variable POST only
                                $this->f3->copy('CSRF','SESSION.session_csrf');  // Used for GET and POST mixed
                        }
                        else
                        {       // DANGER: CSRF attack!
                                $this->f3->error(403); 
                        }
                }


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

    public function fileUpload($uniqfilename,$last_exp_id) {
        $target_dir = "uploads/";
        //echo $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
        $target_file = $target_dir . $uniqfilename;
        //echo $target_file;
        // Check if image file is a actual image or fake image
        if(isset($_POST["submit"])) {
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                }
        }

        // Check if file already exists
        if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 8000000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" && $imageFileType != "pdf" ) {
                echo "Sorry, only PDF, JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
                // if everything is ok, try to upload file
        } else {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			//echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded";
			return 1;
                } else {
			echo "Sorry, there was an error uploading your file.";
			return 0;
                }
        }

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
         return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", isset($_SERVER["HTTP_USER_AGENT"]));
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
//	$db = new DB\SQL('sqlite:data/enc.sqlite');
//	$rev = new DB\SQL('sqlite:data/rev.sqlite');
//	$aev = new DB\SQL('sqlite:data/aev.sqlite');
//	$sales = new DB\SQL('sqlite:data/sales.sqlite');
	$bpllc = new DB\SQL('sqlite:data/bpllc.sqlite');

        // De-Militirizaed Zone for public pages
	$dmz = array('/mat/screen','/mat/receiving','/sf');

	$this->f3=$f3;
	$this->usr=$usr;
	$this->dmz=$dmz;
//	$this->db=$db;
//	$this->rev=$rev;
//	$this->aev=$aev;
//	$this->sales=$sales;
	$this->bpllc=$bpllc;

    }
}

?>
