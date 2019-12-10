<?php
class mtasaQuery extends QueryBase {
	public function connect($ip, $port) {
		$this->ip = $ip;
		$this->port = $port;
		$this->socket = fsockopen('udp://' . $this->ip, $this->port + 123, $sockError, $sockErrorNum, 2);
		socket_set_timeout($this->socket, 1);
	}
	
	public function disconnect() {
		fclose($this->socket);
	}
	
	private function sendPacket() {
		$packet = "s";
		
		$this->write($packet);
	}
	
	public function getInfo() {
		$this->sendPacket();
		
		if($this->read(4) != "EYE1") return false;
		
		$this->readStringLen();
		$this->readStringLen();
		$data['hostname'] = (string)$this->readStringLen();
		$data['gamemode'] = (string)$this->readStringLen();
		$data['mapname'] = (string)$this->readStringLen();
		$this->readStringLen();
		$data['password'] = (bool)$this->readStringLen();
		$data['players'] = (int)$this->readStringLen();
		$data['maxplayers'] = (int)$this->readStringLen();
		
		return $data;
	}
	
	function readStringLen() {
		$len = $this->readInt8();
		return $this->read($len-1);
	}
}
?>