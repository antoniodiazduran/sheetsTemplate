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

    public function fileUpload() {
	$target_dir = "uploads/";
	//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
	$target_file = $target_dir . "exp_". uniqid().".".$imageFileType;

	// Check if image file is a actual image or fake image
	if(isset($_POST["submit"])) {
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			echo "File is an image - " . $check["mime"] . ".";
			$uploadOk = 1;
		} else {
			echo "File is not an image.";
			$uploadOk = 0;
		}
	}

	// Check if file already exists
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}

	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 5000000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}

	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" && $imageFileType != "pdf" ) {
		echo "Sorry, only PDF, JPG, JPEG, PNG & GIF files are allowed.";
		$uploadOk = 0;
	}

	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
		echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
	} else {
		if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
			echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
		} else {
			echo "Sorry, there was an error uploading your file.";
		}
	}

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
			$expense_added=$expense->add($this->f3->get('POST'));
	                $apt = $this->f3->get('POST.Apartment');
                        $this->fileUpload();
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
			$this->f3->set('isMobile',parent::isMobile());
			$this->f3->set('nav_menu','navtenant.htm');
	                $this->f3->set('layout','tenant.htm');
	                $this->f3->set('content','expenses/form.htm');
		}
	}

    public function delete_expenses() {
		$id = $this->f3->get('PARAMS.id');
	        $apt = $this->f3->get('PARAMS.apt');
		$expense = new \Expenses($this->bpllc);
		$expense->delete($id);
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
        if($this->f3->exists('POST.edit')) {
            $expense->edit($id, $this->f3->get('POST'));
        }
        $this->f3->set('POST.edit',"edit");
        $this->f3->set('apartment',$id);
	$this->f3->set('expenses',$expense->getById($id));
	$this->f3->set('isMobile',parent::isMobile());
	$this->f3->set('nav_menu','navtenant.htm');
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
