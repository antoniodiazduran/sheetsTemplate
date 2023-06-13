<?php

namespace ENC;

class Configur extends \Controller {

    public function index() {
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','welcome.htm');
    }

    public function questionaire() {
                $this->f3->set('breadcrumbs','configur');
		$dbaccess = new \Options($this->sales);
		$mod = $this->f3->get('PARAMS.mod');
		$cat = $this->f3->get('PARAMS.cat');
		$this->f3->set('buses', $dbaccess->models());
		$this->f3->set('category', $dbaccess->category());
		$this->f3->set('details', $dbaccess->all($mod,$cat));
	        $this->f3->set('navs','no');
		$this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','create');
		$this->f3->set('fields','configur/radios.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','configur/questions.htm');
    }

    public function summaries() {
                $this->f3->set('breadcrumbs','configur');
		$dbaccess = new \Options($this->sales);
		$this->f3->set('buses', $dbaccess->models());
		$this->f3->set('summary', $dbaccess->summary());
	        $this->f3->set('navs','no');
		$this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','create');
		$this->f3->set('layout','layout.htm');
		$this->f3->set('fields','configur/summaries.htm');
                $this->f3->set('content','configur/summary.htm');
    }

    public function apidbs() {
		$dbaccess = new Options($this->sales);
		$data[] = $dbaccess->apidb;
		$json_data = json_encode($data);
		//var_dump($json_data);
		exit;
    }

}
?>
