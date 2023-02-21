<?php

class Revdata extends Controller {

    public function tableData() {
		$units[] = "";
		$sql = $this->f3->get('PARAMS.id');
		$units = $this->rev->exec('select * from '. $sql);
		$columns[] = "";
		  foreach($units[0] as $key => $value) {
			array_push($columns, $key);
		  }
		$this->f3->set('columns',$columns);
		$this->f3->set('uhbs',$units);
	        $this->f3->set('navs','no');
	        $this->f3->set('customer','no');
		$this->f3->set('layout','flat.htm');
                $this->f3->set('content','revdata/table.htm');
    }
    public function myip() {
	echo "ip:".$_SERVER['REMOTE_ADDR'];
	exit;
    }
    public function ipaddr() {
	$ipaddr = $this->f3->get('PARAMS.addr');
        $tstamp = time();
        $this->db->exec('insert into ipaddress (timestamp,ipaddress) values (?,?)',array($tstamp,$ipaddr));
	exit;
    }
}
?>
