<?php

class Customer extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'enc_matlog');
    }

    public function eventlist() {
	// Transactions by customer
        $sqlsty  = "SELECT * ";
        $sqlsty .= "FROM enc_veventweeks  ";
        $sqlsty .= "WHERE customer IS NOT NULL ";
        $sqlsty .= "GROUP BY customer, weekx ORDER BY customer, weekx";
        $result = $this->db->exec($sqlsty);
	return $result;
    }
    public function eventdaylist($cus) {
	// Transactions by customer
        $sqlsty  = "SELECT * ";
        $sqlsty .= "FROM enc_veventdays  ";
        $sqlsty .= "WHERE customer IS NOT NULL and customer = ?";
        $sqlsty .= "ORDER BY customer, daysx";
        $result = $this->db->exec($sqlsty,$cus);
	return $result;
    }
    public function customerlist() {
	// Customer name's array
        $sqlstr  = "SELECT SUBSTR(s.customer,1,3) AS custx ";
        $sqlstr .= "FROM enc_so s ";
        $sqlstr .= "WHERE s.ax IN (SELECT m.unitid FROM enc_matlog m) ";
        $sqlstr .= "GROUP BY custx";
        $result = $this->db->exec($sqlstr);
        return $result;
    }

}
?>
