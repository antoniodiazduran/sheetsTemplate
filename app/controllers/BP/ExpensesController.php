<?php

namespace BP;

class ExpensesController extends \Controller {

	protected $f3;
	protected $db;

    public function aptName($id) {
		$apartment = new \Apartments($this->bpllc);
        	$apartment->getRecord($id);
        	return $apartment->Name;
    }

    public function all()
	{
        $expense = new \Expenses($this->bpllc);
        $this->f3->set('apartment','::');
        $this->f3->set('apartmentName','');
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('expenses',$expense->all());
		$this->f3->set('nav_menu','navtenant.htm');
                $this->f3->set('layout','tenant.htm');
		$this->f3->set('content','expenses/list.htm');
	}

    public function add_expenses() {
        $apt = $this->f3->get('PARAMS.id');
		if($this->f3->exists('POST.new')) 
        	{
	        	$expense = new \Expenses($this->bpllc);

			// Extracting file information
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			$uniqfilename = "exp_".uniqid().".".$imageFileType;
			//$this->f3->set('POST.UpLoadFile',$uniqfilename);

			// Adding the expense record
			$last_expense_id = $expense->add($this->f3->get('POST'));

			// Adding the upload file record
			$fileData = array();
			$fileData["expenseId"] = $last_expense_id;
			$fileData["originalFile"] = $_FILES["fileToUpload"]["name"];
			$fileData["uploadFile"] = $uniqfilename;
			$fileData["fileType"] = $imageFileType;

			// Uploading the file
                        $upload = parent::fileUpload($uniqfilename,$last_expense_id);
			if($upload==1) {
				$upld = new \Uploads($this->bpllc);
				$upld->add($fileData);
			}

			// Refreshing the list
	                //$apt = $this->f3->get('POST.Apartment');
        		$this->f3->set('apartmentName',$this->aptName($apt));
            		$this->f3->set('apartment',$apt);
            		$this->f3->set('expenses',$expense->getByApartment($apt));
			$this->f3->set('isMobile',parent::isMobile());
			$this->f3->set('nav_menu','navtenant.htm');
                	$this->f3->set('layout','tenant.htm');
	            	$this->f3->set('content','expenses/list.htm');
		} else {
			$this->f3->set('POST.new',"new");
			$this->f3->set('POST.id',"_");
			$this->f3->set('POST.Apartment',$apt);
        		$this->f3->set('apartmentName',$this->aptName($apt));
			$this->f3->set('POST.TransactionDate',"");
			$this->f3->set('POST.Amount',"0.00");
			$this->f3->set('POST.Supplier',"");
	        	$this->f3->set('POST.Notes',"");
        	        $this->f3->set('apartment',$apt);
			$this->f3->set('uploaded','');
			$this->f3->set('isMobile',parent::isMobile());
			$this->f3->set('nav_menu','navtenant.htm');
	                $this->f3->set('layout','tenant.htm');
	                $this->f3->set('content','expenses/form.htm');
		}
	}

    public function delete_uploads() {
		$id = $this->f3->get('PARAMS.id');
	        $apt = $this->f3->get('PARAMS.apt');
		$expense = new \Expenses($this->bpllc);
		$uploaded = new \Uploads($this->bpllc);
		$uploaded->delete_solo($id);
        	$this->f3->set('apartment',$apt);
		$this->f3->set('expenses',$expense->getById($apt));
		$this->f3->set('apartmentName',$this->aptName($_POST['Apartment']));
		$this->f3->set('uploaded',$uploaded->getByUploads($apt)); 
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('POST.edit',"edit"); 
		$this->f3->set('nav_menu','navtenant.htm');
                $this->f3->set('layout','tenant.htm');
                $this->f3->set('content','expenses/form.htm');
    }

    public function delete_expenses() {
		$id = $this->f3->get('PARAMS.id');
	        $apt = $this->f3->get('PARAMS.apt');
		$expense = new \Expenses($this->bpllc);
		$uploaded = new \Uploads($this->bpllc);
		$expense->delete($id);
		$uploaded->delete($id);
        	$this->f3->set('apartment',$apt);
	        $this->f3->set('apartmentName',$this->aptName($apt));
		$this->f3->set('expenses',$expense->getByApartment($apt));
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('nav_menu','navtenant.htm');
                $this->f3->set('layout','tenant.htm');
		$this->f3->set('content','expenses/list.htm');
    }

    public function edit_expenses() {
        $id = $this->f3->get('PARAMS.id'); 
	$expense = new \Expenses($this->bpllc);
	$uploaded = new \Uploads($this->bpllc);

	// Updating record
        if($this->f3->exists('POST.edit')) {
            $expense->edit($id, $this->f3->get('POST'));
        }

	// Uploading a new file
	if (isset($_FILES['fileToUpload'])) {
	  if($_FILES["fileToUpload"]["name"] != '') {
			$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
			$uniqfilename = "exp_".uniqid().".".$imageFileType;
		// Adding the upload file record
		$fileData = array();
		$fileData["expenseId"] = $id;
		$fileData["originalFile"] = $_FILES["fileToUpload"]["name"];
		$fileData["uploadFile"] = $uniqfilename;
		$fileData["fileType"] = $imageFileType;

		// Uploading the file
                $upload = parent::fileUpload($uniqfilename,$id);
		if($upload==1) {
			$upld = new \Uploads($this->bpllc);
			$upld->add($fileData);
		}
 	  }
	}

	$this->f3->set('uploaded',$uploaded->getByUploads($id)); 
	$this->f3->set('POST.edit',"edit"); 
	$this->f3->set('apartment',$id); 
        $this->f3->set('expenses',$expense->getById($id)); 
	$this->f3->set('isMobile',parent::isMobile()); 
        $this->f3->set('nav_menu','navtenant.htm'); 
	$this->f3->set('apartmentName',$this->aptName($_POST['Apartment']));
	$this->f3->set('layout','tenant.htm'); 
        $this->f3->set('content','expenses/form.htm');
    }

 
   public function show_expenses() 
	{
		$id = $this->f3->get('PARAMS.id'); 
		$expense = new \Expenses($this->bpllc);
	        $this->f3->set('apartment',$id);
       		$this->f3->set('apartmentName',$this->aptName($id));
		$this->f3->set('expenses',$expense->getByApartment($id));
		$this->f3->set('isMobile',parent::isMobile());
		$this->f3->set('nav_menu','navtenant.htm');
                $this->f3->set('layout','tenant.htm');
		$this->f3->set('content','expenses/list.htm');
	}
}
