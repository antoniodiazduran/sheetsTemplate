<?php

namespace BP;

class MortgageController extends \Controller {

	protected $f3;
	protected $db;

	public function mortgage()
	{
        $mortgage = new \Mortgage($this->bpllc);
		$this->f3->set('mortgage',$mortgage->all());
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('layout','layout.htm');
		$this->f3->set('content','mortgage/list.htm');
	}
    
	public function modify_mortgage() {
		if($this->f3->exists('POST.new')) {
			$mortgage = new \Mortgage($this->bpllc);
			$mortgage_added=$mortgage->add($this->f3->get('POST'));
			//$this->f3->set('message','Added');
		} else {
			$this->f3->set('POST.new',"new");
			$this->f3->set('POST.id',"_");
			$this->f3->set('POST.Bank',"");
			$this->f3->set('POST.Amount',"");
			$this->f3->set('POST.Notes',"");
		}
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('layout','layout.htm');
		$this->f3->set('content','mortgage/form.htm');
	}

	public function delete_mortgage() {
		$id = $this->f3->get('PARAMS.id');
		$mortgage = new \Mortgage($this->bpllc);
		$mortgage->delete($id);
		$this->f3->set('mortgage',$mortgage->all());
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('layout','layout.htm');
		$this->f3->set('content','mortgage/list.htm');
	}

	public function show_mortgage() 
	{
		$id = $this->f3->get('PARAMS.id'); 
		if($this->f3->exists('POST.edit'))
        {
			$mortgage = new \Mortgage($this->bpllc);
			$mortgage->edit($id, $this->f3->get('POST'));
		}
		else
		{
			$mortgage = new \Mortgage($this->bpllc);
			$mortgage->getById($id);

			if($mortgage->dry()) { //throw a 404, order does not exist
				$this->f3->error(404);
			}
		}
		$this->f3->set('mortgage',$mortgage->all());
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('layout','layout.htm');
		$this->f3->set('content','mortgage/form.htm');
	}
}
