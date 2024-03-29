<?php

class Pareto extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'enc_matlog');
    }

    public function paretocustomer() {
	// Transactions by customer
	$sqlstx  = "SELECT (select substr(s.customer,1,3) FROM enc_so s WHERE s.ax = m.unitid) AS customer, ifnull(paretoreason,'x') AS reason, count(m.rid) as total ";
	$sqlstx .= "FROM enc_matlog m ";
	$sqlstx .= "WHERE customer is not null ";
	$sqlstx .= "GROUP BY customer, paretoreason ";
	$sqlstx .= "ORDER BY customer, total DESC ";
        $result = $this->db->exec($sqlstx);
	return $result;
    }
    public function paretoline() {
	// Transactions by line
	$sqlstx  = "SELECT replace(line,' ','_') AS line, ifnull(paretoreason,'x') AS reason, count(m.rid) as total ";
	$sqlstx .= "FROM enc_matlog m ";
	$sqlstx .= "WHERE line is not null ";
	$sqlstx .= "GROUP BY line, paretoreason ";
	$sqlstx .= "ORDER BY line, total DESC ";
        $result = $this->db->exec($sqlstx);
	return $result;
    }
    public function paretoreason() {
	// Transaction by reason
	$sqlstx  = "SELECT ";
	$sqlstx .= "(select substr(customer,1,3) from enc_so s where s.ax = enc_matlog.unitid) as customer,  ";
	$sqlstx .= "REPLACE(IFNULL(paretoreason,'x'),' ','_') AS reason, line AS line, count(rid) AS total ";
	$sqlstx .= "FROM enc_matlog ";
	$sqlstx .= "WHERE  (length(paretoreason) > 0 and paretoreason is not null) and customer = ? ";
	$sqlstx .= "GROUP BY customer, reason, line ";
	$sqlstx .= "ORDER BY customer, reason, total DESC ";
	$result = $this->db->exec($sqlstx,'%');
	return $result;
    }
}
?>
