<?php

namespace BP;

class StatementController extends \Controller {

	protected $f3;
	protected $db;


	public function show() 
	{
		$id = $this->f3->get('PARAMS.id'); 
		$statement = new \Statements($this->bpllc);
		$apartment = new \Apartments($this->bpllc);
		$this->f3->set('apartment',$apartment->getName($id));
		$this->f3->set('statement',$statement->all($id));
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('nav_menu','navtenant.htm');
		$this->f3->set('layout','tenant.htm');
		$this->f3->set('content','statements/list.htm');
	}
}
