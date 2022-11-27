<?php

// Loading libraries and share components
if (file_exists('vendor/autoload.php')) {
	// load via composer
	require_once('vendor/autoload.php');
	// loaf Google connection for Google Sheets
        require '/home/antoniodiazduran/vendor/autoload.php';
	$f3 = \Base::instance();
} elseif (!file_exists('lib/base.php')) {
	die('fatfree-core not found. Run `git submodule init` and `git submodule update` or install via composer with `composer install`.');
} else {
	// load via submodule
	/** @var Base $f3 */
	$f3=require('lib/base.php');
}

// Verifing PHP version
$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<8.0)
	trigger_error('PCRE version is out of date');

// Load configuration
$f3->config('config.ini');

//WIP
$f3->route('POST /wip/create','Wip->insertRow'); 
$f3->route('GET /rdp','Wip->form'); 
$f3->route('GET /wip','Wip->form'); 
$f3->route('GET /wiplist','Wip->all'); 
$f3->route('GET /wlog','Wip->wlog');
$f3->route('GET /area/@area','Wip->alist'); 
$f3->route('GET /traveler/@traveler','Wip->tlist'); 
$f3->route('GET /customers/@customer','Wip->clist'); 
$f3->route('GET /partnumber/@partnumber','Wip->plist'); 

//Cageout
$f3->route('POST /cageout/create','Cage->insertRow'); 
$f3->route('GET /cagelist','Cage->form'); 
$f3->route('GET /cageout','Cage->form'); 
$f3->route('GET /cageoutlist','Cage->all'); 

//Shortage
$f3->route('POST /shortage/create','Shortage->insertRow'); 
$f3->route('GET /shortage','Shortage->form'); 
$f3->route('GET /shortagelist','Shortage->all'); 

// ENC engineering shop floor support
$f3->route('POST /esfs/create','Esfs->insertRow'); 
$f3->route('GET /esfs','Esfs->form'); 
$f3->route('GET /esfslist','Esfs->all'); 
$f3->route('GET /rdgd','Esfs->form'); 
$f3->route('GET /rdgdaody','Esfs->all'); 

// Welcome page
$f3->route('GET /',
	function($f3) {
		$f3->set('content','welcome.htm');
		echo \Template::instance()->render('layout.htm');
	}
);


$f3->run();
