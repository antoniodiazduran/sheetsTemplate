<?php

// Loading libraries and share components
if (file_exists('vendor/autoload.php')) {
	// load via composer
	require_once('vendor/autoload.php');
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
// Catching errors
//$f3->set('ONERROR','Errors->msgs');

// ENC admin page for engineering support
$f3->route('GET  /sfdbs','Admin->apidbs');
$f3->route('GET  /sfadm','Admin->sf');
$f3->route('POST /owr/upd','Admin->ownrupd');
$f3->route('GET  /owr/edit/@id','Admin->ownr');
$f3->route('POST /urg/upd','Admin->urgeupd');
$f3->route('GET  /urg/edit/@id','Admin->urge');
$f3->route('GET  /owr/main','Admin->sf');
$f3->route('GET  /owr/list','Owner->ownrlist');
$f3->route('POST /owr/create','Owner->ownradd');
$f3->route('GET  /owr/del/@id','Owner->ownrdel');
$f3->route('GET  /urg/main','Admin->sf');
$f3->route('GET  /urg/list','Urgency->urgelist');
$f3->route('POST /urg/create','Urgency->urgeadd');
$f3->route('GET  /urg/del/@id','Urgency->urgedel');
$f3->route('POST /req/upd','Request->upd');
$f3->route('GET  /req/edit/@id','Request->edit');
$f3->route('GET  /nts/edit/@id','Notes->edit');
$f3->route('POST /nts/upd','Notes->upd');
$f3->route('GET  /nts/del/@id','Notes->delete');
$f3->route('GET  /sta/edit/@id','Status->edit');
$f3->route('POST /sta/upd','Status->upd');

// ENC engineering shop floor support
$f3->route('POST /esfs/create','Esfs->insertRow'); 
$f3->route('GET  /esfs','Esfs->form'); 
$f3->route('GET  /esfslist','Shopfloor->sf'); 
//$f3->route('GET /esfslist','Esfs->all'); 

// ENC shopfloor screen using AJAX
$f3->route('GET /sf','Shopfloor->sf'); 
$f3->route('GET /sfapi','Shopfloor->apiall'); 
$f3->route('GET /sfapidb','Shopfloor->apidbs'); 

// WIP
$f3->route('POST /wip/create','Wip->insertRow'); 
$f3->route('GET /rdp','Wip->form'); 
$f3->route('GET /wip','Wip->form'); 
$f3->route('GET /wiplist','Wip->all'); 
$f3->route('GET /wlog','Wip->wlog');
$f3->route('GET /area/@area','Wip->alist'); 
$f3->route('GET /traveler/@traveler','Wip->tlist'); 
$f3->route('GET /customers/@customer','Wip->clist'); 
$f3->route('GET /partnumber/@partnumber','Wip->plist'); 

// Cageout
$f3->route('POST /cageout/create','Cage->insertRow'); 
$f3->route('GET /cagelist','Cage->form'); 
$f3->route('GET /cageout','Cage->form'); 
$f3->route('GET /cageoutlist','Cage->all'); 

//Shortage
$f3->route('POST /shortage/create','Shortage->insertRow'); 
$f3->route('GET /shortage','Shortage->form'); 
$f3->route('GET /shortagelist','Shortage->all'); 

// Not in use
$f3->route('GET /rdgd','Esfs->form'); 
$f3->route('GET /rdgdaody','Esfs->all'); 

// ENC maintenance support
$f3->route('POST /mrf/create','Maintenance->insertRow'); 
$f3->route('GET /mrf','Maintenance->form'); 
$f3->route('GET /mrflist','Maintenance->maint'); 
$f3->route('GET /maint','Maintenance->maint'); 
$f3->route('GET /maintapi','Maintenance->apiall'); 


// ENC BOM screen
$f3->route('POST /bom/create','BOM->insertTable');
$f3->route('GET /bomapi','BOM->readData');
$f3->route('GET /bom','BOM->bomTable');

// REV data
$f3->route('GET /unithoursbystation','Revdata->unithoursbystation');
$f3->route('GET /unithoursbyline','Revdata->unithoursbyline');
$f3->route('GET /data/@id','Revdata->tableData');

$f3->route('GET /myip','Revdata->myip');
$f3->route('GET /ip/@addr','Revdata->ipaddr');

// Welcome page
$f3->route('GET /',
	function($f3) {
		$f3->set('content','welcome.htm');
		echo \Template::instance()->render('layout.htm');
	}
);


$f3->run();
