<?php
final class mysqlDriver {
	private $link;
	private $count = 0;
	public function __construct($hostname, $username, $password, $database) {
		if (!$this->link = @mysqli_connect($hostname, $username, $password, $database)) {
	  		exit('Ошибка: Не удалось соединиться с сервером базы данных!');
		}

		mysqli_set_charset($this->link, "utf8");
  	}

  	public function query($sql) {
		$resource = mysqli_query($this->link, $sql);

		$this->count++;

		if ($resource) {
			if ($resource instanceof mysqli_result) {
				$i = 0;
				$data = array();

				while($result = mysqli_fetch_assoc($resource)) {
					$data[$i] = $result;
					$i++;
				}

				mysqli_free_result($resource);

				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;

				unset($data);
				return $query;
			}
			else return true;
		}
		else exit('Ошибка: Не удалось выполнить запрос к базе данных (#' . mysqli_connect_errno() . ')');
  	}

	public function escape($value) {
		return mysqli_real_escape_string($this->link, $value);
	}

  	public function countAffected() {
		return mysqli_affected_rows($this->link);
  	}

  	public function getLastId() {
		return mysqli_insert_id($this->link);
  	}

  	public function getCount() {
		return $this->count;
  	}

	public function __destruct() {
		mysqli_close($this->link);
	}
}
?>
