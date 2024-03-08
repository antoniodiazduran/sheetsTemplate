<?php

namespace BP;

class TenantController extends \Controller {

	protected $f3;
	protected $db;

	public function main()
	{
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('nav_menu','navtenant.htm');
		$this->f3->set('layout','tenant.htm');
		$this->f3->set('content','blank.htm');
	}
}
