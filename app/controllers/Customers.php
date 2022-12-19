<?php

class Customers extends Controller {

    public function list() 
    {
		$data[] = $this->GSheetsRead('pw.operations');
                $this->f3->set('breadcrumbs','all');
                $this->f3->set('customer',$this->f3->get('PARAMS.customer'));
		$this->f3->set('layout','layout.htm');
		$this->f3->set('headers','wipheaders.htm');
                $this->f3->set('fields','wipfields.htm');
		$this->f3->set('field','customer');
                $this->f3->set('details',$data);
                $this->f3->set('content','list.htm');
    }
    public function all()
    {
		$data[] = $this->GSheetsRead('pw.operations');
                $this->f3->set('breadcrumbs','all');
                $this->f3->set('headers','wipheaders.htm');
                $this->f3->set('layout','layout.htm');
		$this->f3->set('fields','wipfields.htm');
		$this->f3->set('field','all');
		$this->f3->set('details',$data);
                $this->f3->set('content','list.htm');

    }
}
?>

