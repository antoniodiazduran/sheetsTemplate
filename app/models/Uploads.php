<?php

class Uploads extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
		"expenseId",
		"originalFile",
        	"uploadFile",
        	"fileType",
	);

	private function sanitizeInput(array $data, array $fieldNames) 
	{ //sanitize input - with thanks to richgoldmd
	   return array_intersect_key($data, array_flip($fieldNames));
	}

	private function getCurrentdate()
	{
		return date("Y-m-d H:i:s");
	}

	public function __construct(DB\SQL $db) 
	{
		parent::__construct($db,'uploads');
	}

	public function all() 
	{ //get all records
		//$this->aptName="SELECT originalFile, uploadFile FROM uploads WHERE uploads.id = expenses.Apartment";
		//$this->load(array(),array('order'=>'TransactionDate ASC'));
		//return $this->query;
	}

	public function add( $unsanitizeddata )
	{
		$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		//check if name already exists in db
		/*$this->load(array('Name=?',$data['Name']));
		if(!$this->dry())
		{
			return 10;
		}*/
		$data['created_at']=$this->getCurrentdate();
		$data['updated_at']=$this->getCurrentdate();
		$this->copyFrom($data);
		$this->save();
		return $this->id;
	}

	public function getByUploads($id)
	{
		$this->load(array('expenseId=?', $id),array('order'=>'created_at ASC'));
	        return $this->query;
	}

	public function getById($id) 
	{
		$this->load(array('id=?',$id));
		$this->copyTo('POST');
	}

	public function edit($id, $unsanitizeddata)
	{
		$data=$this->sanitizeInput($unsanitizeddata, $this->allowed_fields);
		$data['updated_at']=$this->getCurrentdate();
		$this->load(array('id=?',$id));
		$this->copyFrom($data);
		$this->update();
	}
	public function editupd($id)
	{
		$data['updated_at']=$this->getCurrentdate();
		$this->load(array('id=?',$id));
		$this->copyFrom($data);
		$this->update();
	}

	public function delete_solo($id) 
	{
		$this->load(array('id=?',$id));
		$this->erase();
	}

	public function delete($id) 
	{
		$this->load(array('expenseId=?',$id));
		$this->erase();
	}
}
