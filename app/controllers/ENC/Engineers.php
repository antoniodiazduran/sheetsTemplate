<?php

namespace ENC;

class Engineers extends \Controller {

    public function index() {
		$this->f3->set('isMobile',$this->isMobile());
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','welcome.htm');  
    }
    public function isMobile()  {
         return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
    public function sf() {
		$data[] = $this->db->exec("SELECT * FROM enc_log WHERE display = 'y' ORDER BY display DESC, sort ASC, DueDate ASC");
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','owr');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
		$this->f3->set('t1',count($data[0])-250);
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','naveng.htm');
	        $this->f3->set('customer','yes');
		$this->f3->set('isMobile',$this->isMobile());
                $this->f3->set('headers','admin/engheaders.htm');
                $this->f3->set('fields','admin/engfields.htm');
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','admin/list.htm');
    }
    public function apidbs() {
		$data[] = $this->db->exec('SELECT * FROM enc_log ORDER BY sort ASC, DueDate ASC');
		$json_data = json_encode($data);
		//var_dump($json_data);
		exit;
    }

}
?>
