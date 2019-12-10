<?php
class User {
	private $registry;

	private $user_id;
	private $email;
	private $firstname;
	private $lastname;
	private $balance;
	private $access_level;
	private $news;

  	public function __construct($registry) {
		$this->registry = $registry;
		if (isset($this->registry->session->data['user_id'])) {
			$query = $this->registry->db->query("SELECT * FROM users WHERE user_id = '" . (int)$this->registry->session->data['user_id'] . "' AND user_status = '1'");
			
			if ($query->num_rows) {
				$this->user_id = $query->row['user_id'];
				$this->email = $query->row['user_email'];
				$this->firstname = $query->row['user_firstname'];
				$this->lastname = $query->row['user_lastname'];
				$this->balance = $query->row['user_balance'];
				$this->access_level = $query->row['user_access_level'];
				$this->news = $query->row['user_news'];
			} else {
				$this->logout();
			}
		}
  	}
		
  	public function login($email, $password) {
		$query = $this->registry->db->query("SELECT * FROM users WHERE user_email = '" . $this->registry->db->escape($email) . "' AND user_status = '1'");

		if($query->num_rows) {
			if(!password_verify($password, $query->row['user_password'])) return false;

			$this->registry->session->data['user_id'] = $query->row['user_id'];
			
			$this->user_id = $query->row['user_id'];
			$this->email = $query->row['user_email'];
			$this->firstname = $query->row['user_firstname'];
			$this->lastname = $query->row['user_lastname'];
			$this->balance = $query->row['user_balance'];
			$this->access_level = $query->row['user_access_level'];
			$this->news = $query->row['user_news'];
	  		return true;
		} else {
	  		return false;
		}
  	}

  	public function logout() {
		unset($this->registry->session->data['user_id']);
	
		$this->user_id = null;
		$this->email = null;
		$this->firstname = null;
		$this->lastname = null;
		$this->balance = null;
		$this->access_level = 0;
		$this->news = 0;
  	}
  
  	public function isLogged() {
		return $this->user_id;
  	}
  
  	public function getId() {
		return $this->user_id;
  	}
	
  	public function getEmail() {
		return $this->email;
  	}
	
  	public function getFirstname() {
		return $this->firstname;
  	}
	
  	public function getLastname() {
		return $this->lastname;
  	}
	
  	public function getBalance() {
		return $this->balance;
  	}
	
  	public function getAccessLevel() {
		return $this->access_level;
  	}
	
  	public function getNews() {
		return $this->news;
  	}
  	public function clearNews() {
		$this->news = 0;
		return true;
  	}
}
?>
