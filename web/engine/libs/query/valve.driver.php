<?php
class valveQuery extends QueryBase {
	public function connect($ip, $port) {
		$this->ip = $ip;
		$this->port = $port;
		$this->socket = fsockopen('udp://' . $this->ip, $this->port, $sockError, $sockErrorNum, 2);
		socket_set_timeout($this->socket, 1);
	}
	
	public function disconnect() {
		fclose($this->socket);
	}
	
	private function sendPacket() {
		$packet = "\xFF\xFF\xFF\xFFTSource Engine Query\x00";
		
		$this->write($packet);
	}
	
	public function getInfo() {
		$this->sendPacket();
		
		if($this->read(4) != "\xFF\xFF\xFF\xFF") return false;
		
		// Тип сервера. (0x6D - старые версии серверов)
		$type = (int)$this->readInt8();
		
		if($type == 0x6D)	$this->readString();
		else				$this->read(1);
		
		$data['hostname'] = (string)$this->readString();
		$data['mapname'] = (string)$this->readString();
		$this->readString();
		$data['gamemode'] = (string)$this->readString();
		
		if($type != 0x6D) $this->read(2);
		
		$data['players'] = (int)$this->readInt8();
		$data['maxplayers'] = (int)$this->readInt8();
		$this->read(3);
		$data['password'] = (bool)$this->readInt8();
		
		return $data;
	}
}
?>