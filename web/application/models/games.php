<?php
class gamesModel extends Model {
	public function createGame($data) {
		$sql = "INSERT INTO `games` SET ";
		$sql .= "game_name = '" . $this->db->escape($data['game_name']) . "', ";
		$sql .= "game_code = '" . $this->db->escape($data['game_code']) . "', ";
		$sql .= "game_query = '" . $this->db->escape($data['game_query']) . "', ";
		$sql .= "game_min_slots = '" . (int)$data['game_min_slots'] . "', ";
		$sql .= "game_max_slots = '" . (int)$data['game_max_slots'] . "', ";
		$sql .= "game_min_port = '" . (int)$data['game_min_port'] . "', ";
		$sql .= "game_max_port = '" . (int)$data['game_max_port'] . "', ";
		$sql .= "game_price = '" . (float)$data['game_price'] . "', ";
		$sql .= "game_status = '" . (int)$data['game_status'] . "'";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteGame($gameid) {
		$sql = "DELETE FROM `games` WHERE game_id = '" . (int)$gameid . "'";
		$this->db->query($sql);
	}
	
	public function updateGame($gameid, $data = array()) {
		$sql = "UPDATE `games`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `game_id` = '" . (int)$gameid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	
	public function getGames($data = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `games`";
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
	
	public function getGameById($gameid) {
		$sql = "SELECT * FROM `games` WHERE `game_id` = '" . (int)$gameid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalGames($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `games`";
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
	
	public function validateSlots($gameid, $slots) {
	  	$sql = "SELECT COUNT(*) AS total FROM `games` WHERE game_id = '" . (int)$gameid . "' AND " . (int)$slots . " BETWEEN game_min_slots AND game_max_slots";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
}
?>
