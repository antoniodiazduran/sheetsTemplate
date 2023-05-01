<?php

class Errors extends Controller {

  public function msgs() {
     //$template=new Template;
     $this->f3->set('msg','Page not found...'); 
     //echo $template->render('error/msg.htm')
     $this->f3->set('layout','kiosk.htm');
     $this->f3->set('content','error/msg.htm');
  }


}
?>


