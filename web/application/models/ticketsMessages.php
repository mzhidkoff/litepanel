<?php
class ticketsMessagesModel extends Model {
	public function createTicketMessage($data) {
		$sql = "INSERT INTO `tickets_messages` SET ";
		$sql .= "ticket_id = '" . (int)$data['ticket_id'] . "', ";
		$sql .= "user_id = '" . (int)$data['user_id'] . "', ";
		$sql .= "ticket_message = '" . $this->db->escape($data['ticket_message']) . "', ";
		$sql .= "ticket_message_date_add = NOW()";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteTicketMessage($messageid) {
		$sql = "DELETE FROM `tickets_messages` WHERE ticket_message_id = '" . (int)$messageid . "'";
		$this->db->query($sql);
	}
	
	public function updateTicketMessage($messageid, $data = array()) {
		$sql = "UPDATE `tickets_messages`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `ticket_message_id` = '" . (int)$messageid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	
	public function getTicketsMessages($data = array(), $joins = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `tickets_messages`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON tickets_messages.user_id=users.user_id";
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
			if($options['start'] < 0) {
				$options['start'] = 0;
			}
			if($options['limit'] < 1) {
				$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$options['start'] . "," . (int)$options['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTicketsMessageById($messageid, $joins = array()) {
		$sql = "SELECT * FROM `tickets_messages`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON tickets_messages.user_id=users.user_id";
					break;
			}
		}
		$sql .=  " WHERE `ticket_message_id` = '" . (int)$messageid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalTicketsMessages($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `tickets_messages`";
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
