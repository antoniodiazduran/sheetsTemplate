<?php

class PageController extends Controller {

	public function homepage()
	{
		$this->f3->set('view','page/homepage.htm');
	}
	public function index()
	{
		$this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('layout','admin.htm');
                $this->f3->set('content','welcome.htm');
	}
}
