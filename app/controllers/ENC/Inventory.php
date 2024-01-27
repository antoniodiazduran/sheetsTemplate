<?php

namespace ENC;

class Inventory extends \Controller {

    public function sankey() {
                $this->f3->set('breadcrumbs','inv');
	        $this->f3->set('navs','no');
		$this->f3->set('layout','admin.htm');
                $this->f3->set('content','inv/sankey.htm');
    }

}
?>
