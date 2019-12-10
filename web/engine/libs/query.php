<?php
class queryLibrary {
	private $driver;
	
	public function __construct($driver) {
		$class = $driver . 'Query';
		if(is_readable(ENGINE_DIR . 'libs/query/base.php')) {
			require_once(ENGINE_DIR . 'libs/query/base.php');
		} else {
			exit('Ошибка: Не удалось загрузить базовый query-драйвер!');
		}
		if(is_readable(ENGINE_DIR . 'libs/query/' . $driver . '.driver.php')) {
			require_once(ENGINE_DIR . 'libs/query/' . $driver . '.driver.php');
		} else {
			exit('Ошибка: Не удалось загрузить query-драйвер ' . $driver . '!');
		}
		$this->driver = new $class();
	}
		
  	public function connect($ip, $port) {
		return $this->driver->connect($ip, $port);
  	}
	
  	public function disconnect() {
		return $this->driver->disconnect();
  	}
	
  	public function getInfo() {
		return $this->driver->getInfo();
  	}

  	public function getError() {
		return $this->driver->getError();
  	}
}
?>
