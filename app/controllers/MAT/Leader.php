<?php

namespace MAT;

class Leader extends \Controller {

    public function list() {
                $fld = $this->f3->get('PARAMS.field');
                $val = $this->f3->get('PARAMS.value');
                if ($fld == '') {
                $data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m ORDER BY ArrivedDate DESC LIMIT 200");
                } else {
                $data[] = $this->db->exec("SELECT *, (SELECT substr(customer,1,3) FROM enc_so WHERE m.UnitID = AX) AS Customer,(SELECT ship FROM enc_so WHERE m.UnitID = AX) AS ship FROM enc_matlog m WHERE $fld = ? ORDER BY ArrivedDate DESC LIMIT 200",$val);
                }
                $this->f3->set('details',$data);
                $this->f3->set('breadcrumbs','mat');
                $this->f3->set('field','all');
                $this->f3->set('navs','yes');
                $this->f3->set('nav_menu','navleaders.htm');
                $this->f3->set('isMobile',parent::isMobile());
                $this->f3->set('customer','yes');
		$this->f3->set('columns','[1,2,3,4,5,6,7,8,9,10,11,12,13]');
                $this->f3->set('bgcolor','green');
                $this->f3->set('headers','materials/headers.htm');
                $this->f3->set('fields','materials/leadersfields.htm');
                $this->f3->set('layout','layout.htm');
                $this->f3->set('content','materials/list.htm');
    }

}

?>
