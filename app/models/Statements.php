<?php

class Statements extends DB\SQL\Mapper {

/* only these db fields are allowed to be changed */
	protected $allowed_fields = array(
		"Aparment",
		"TransactionDate",
		"Supplier",
		"Amount"
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
		parent::__construct($db,'statements');
	}

	public function all($apt) 
	{ //get all records
		$this->load(array('Apartment=?', $apt),array('order'=>'TransactionDate desc'));
		return $this->query;
	}


}
