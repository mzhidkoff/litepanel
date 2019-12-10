<?php
class serversStatsModel extends Model {
	public function createServerStats($data) {
		$sql = "INSERT INTO `servers_stats` SET ";
		$sql .= "server_id = '" . (int)$data['server_id'] . "', ";
		$sql .= "server_stats_date = NOW(), ";
		$sql .= "server_stats_players = '" . (int)$data['server_stats_players'] . "'";
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deleteServerStats($serverid) {
		$sql = "DELETE FROM `servers_stats` WHERE server_id = '" . (int)$serverid . "'";
		$this->db->query($sql);
	}
	
	public function getServerStats($serverid, $start, $end) {
		$sql = "SELECT * FROM `servers_stats` WHERE server_id = '" . (int)$serverid . "' AND server_stats_date BETWEEN " . $start . " AND " . $end . " ORDER BY server_stats_date";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getTotalServerStats($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `servers_stats`";
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
	
	public function clearServersStats() {
		$sql = "DELETE FROM `servers_stats` WHERE server_stats_date < NOW() - INTERVAL 3 DAY";
		$this->db->query($sql);
	}
}
?>
