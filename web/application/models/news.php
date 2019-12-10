<?php
class newsModel extends Model {
	public function createNews($data) {
		$sql = "INSERT INTO `news` SET ";
		$sql .= "user_id = '" . (int)$data['user_id'] . "', ";
		$sql .= "news_title = '" . $data['news_title'] . "', ";
		$sql .= "news_text = '" . $data['news_text'] . "', ";
		$sql .= "news_date_add = NOW()";
		$this->db->query($sql);
		$lastid = $this->db->getLastId();
		$this->db->query("UPDATE `users` SET user_news = user_news+1 WHERE 1");
		return $lastid;
	}
/*	
	public function deleteTicket($ticketid) {
		$sql = "DELETE FROM `tickets` WHERE ticket_id = '" . (int)$ticketid . "'";
		$this->db->query($sql);
	}
	
	public function updateTicket($ticketid, $data = array()) {
		$sql = "UPDATE `tickets`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `ticket_id` = '" . (int)$ticketid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	*/
	public function getNews($data = array(), $joins = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `news`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON news.user_id=users.user_id";
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
	/*
	public function getTicketById($ticketid, $joins = array()) {
		$sql = "SELECT * FROM `tickets`";
		foreach($joins as $join) {
			$sql .= " LEFT JOIN $join";
			switch($join) {
				case "users":
					$sql .= " ON tickets.user_id=users.user_id";
					break;
			}
		}
		$sql .=  " WHERE `ticket_id` = '" . (int)$ticketid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}*/
	
	public function getTotalNews($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `news`";
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
