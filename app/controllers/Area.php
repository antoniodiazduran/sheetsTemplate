<?php

class Area extends Controller {

    public function list() {
		$data[] = $this->GSheetsRead('pw.operations');
                $this->f3->set('breadcrumbs','All');
                $this->f3->set('area',$this->f3->get('PARAMS.area'));
                $this->f3->set('details',$data);
		$this->f3->set('field','area');
                $this->f3->set('layout','layout.htm');
		$this->f3->set('headers','wipheaders.htm');
                $this->f3->set('fields','wipfields.htm');
                $this->f3->set('content','list.htm');
    }
    public function all()
    {
		$data[] = $this->GSheetsRead('pw.operations');
                $this->f3->set('breadcrumbs','All');
                $this->f3->set('details',$data);
		$this->f3->set('field','all');
                $this->f3->set('layout','layout.htm');
		$this->f3->set('headers','wipheaders.htm');
                $this->f3->set('fields','wipfields.htm');
                $this->f3->set('content','list.htm');

    }
}
?>

