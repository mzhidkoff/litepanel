<?php
class invoicesModel extends Model {
	public function createInvoice($data) {
		$sql = "INSERT INTO `invoices` SET ";
		$sql .= "user_id = '" . (int)$data['user_id'] . "', ";
		$sql .= "invoice_ammount = '" . (float)$data['invoice_ammount'] . "', ";
		$sql .= "invoice_status = '" . (int)$data['invoice_status'] . "', ";
		$sql .= "invoice_date_add = NOW()";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteInvoice($invoiceid) {
		$sql = "DELETE FROM `invoices` WHERE invoice_id = '" . (int)$invoiceid . "'";
		$this->db->query($sql);
	}
	
	public function updateInvoice($invoiceid, $data = array()) {
		$sql = "UPDATE `invoices`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `invoice_id` = '" . (int)$invoiceid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	
	public function getInvoices($data = array(), $joins = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `invoices`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON invoices.user_id=users.user_id";
					break;
			}
		}
		
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		
		if(!empty($sort)) {
			$count = count($sort);
			$sql .= " ORDER BY";
			foreach($sort as $key => $value) {
				$sql .= " $key " . $value;
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		
		if(!empty($options)) {
			if ($options['start'] < 0) {
				$options['start'] = 0;
			}
			if ($options['limit'] < 1) {
				$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$options['start'] . "," . (int)$options['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getInvoiceById($invoiceid, $joins = array()) {
		$sql = "SELECT * FROM `invoices`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON invoices.user_id=users.user_id";
					break;
			}
		}
		$sql .=  " WHERE `invoice_id` = '" . (int)$invoiceid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalInvoices($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `invoices`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['count'];
	}
}
?>
