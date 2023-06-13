<?php

class Options extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'salesconfig');
    }

    public function all($model,$category) {
        // Selecting data
        $sql  = "SELECT * FROM salesconfig WHERE models = ? AND CategoryNumber = ?";
	    $result = $this->db->exec($sql,array($model,$category));
        return $result;
    }

    public function models () {
	    $sql = "SELECT DISTINCT(models) FROM salesconfig WHERE models <> '' AND instr(models,'-') = 0";
	    $result = $this->db->exec($sql);
	    return $result;
    }

    public function category () {
	    $sql = "SELECT DISTINCT(CategoryNumber) as CategoryNumber FROM salesconfig";
	    $result = $this->db->exec($sql);
	    return $result;
    }

    public function summary () {
	    $sql = "SELECT DISTINCT(categorynumber) as CategoryNumber FROM salesconfig WHERE categoryNumber <> ''";
	    $result = $this->db->exec($sql);
	    return $result;
    }

    public function apidb() {
        $sql = "SELECT * FROM enc_log ORDER BY sort ASC, DueDate ASC";
        $result = $this->db->exec($sql);
	    return $result;
    }
}
