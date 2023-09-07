<?php

namespace ENC;

class Admin extends \Controller {

    public function index() {
		$this->f3->set('isMobile',$this->isMobile());
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','welcome.htm');  
    }
    public function isMobile()  {
         return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }
    public function sch() {
		$e = $this->f3->get('PARAMS.e');
		$j = $this->f3->get('PARAMS.j');
		$s = $this->f3->get('PARAMS.s');
		$l = $this->f3->get('PARAMS.l');
		$d = $this->f3->get('PARAMS.d');
		$c = $this->f3->get('PARAMS.c');
		$v = $this->f3->get('PARAMS.v');
		$str = "INSERT INTO sch_board (Epoch, Jobnumber, Startdate, Line, Dealername, Customername, Vin) VALUES (?,?,?,?,?,?,?)";
	echo $str;
		$this->aev->exec($str,array($e,$j,$s,$l,$d,$c,$v) );
	exit;
    }
    public function xurge() {
                $this->f3->set('breadcrumbs','urg');
		$this->f3->set('epoch', $this->f3->get('PARAMS.id'));
		$this->f3->set('actual', $this->db->exec('SELECT Urgency FROM enc_log WHERE Epoch = ?',$this->f3->get('PARAMS.id') ));
		$urgency = $this->db->exec('SELECT names FROM urgency ORDER BY names');
	        $this->f3->set('navs','yes');
		$this->f3->set('nav_menu','navadmin.htm');
		$this->f3->set('mode','upd');
		$this->f3->set('urgency',$urgency);
		$this->f3->set('layout','layout.htm');
                $this->f3->set('content','admin/urgency.htm');
    }
    public function sf() {
		$data[] = $this->db->exec("SELECT * FROM enc_log ORDER BY display DESC, sort ASC, DueDate ASC");
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','owr');
                $this->f3->set('field','all');
		$this->f3->set('c1',0);
		$this->f3->set('t1',count($data[0])-250);
	        $this->f3->set('navs','yes');
	        $this->f3->set('nav_menu','navadmin.htm');
	        $this->f3->set('customer','yes');
                $this->f3->set('headers','admin/headers.htm');
                $this->f3->set('fields','admin/fields.htm');
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
