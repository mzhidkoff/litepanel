<?php
class mineQuery extends QueryBase {
	public function connect($ip, $port) {
		$this->ip = $ip;
		$this->port = $port;
	}
	
	public function disconnect() {
		return true;
	}
	
	public function getInfo() {
		$status = json_decode(file_get_contents('https://api.mcsrvstat.us/2/'.$this->ip.':'.$this->port));

		if($status->online == true) {
			$data['players'] = (int)$status->players->online;
			$data['maxplayers'] = (int)$status->players->max;
			$data['hostname'] = (string)$status->motd->clean[0];
			$data['gamemode'] = "version " . $status->version;
			$data['mapname'] = "world";
		}

		return $data;

	}
}
?>