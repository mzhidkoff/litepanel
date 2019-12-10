<?php
class orderController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('order');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('games');
		$this->load->model('locations');
		
		$games = $this->gamesModel->getGames(array('game_status' => 1));
		$locations = $this->locationsModel->getLocations(array('location_status' => 1));
		
		$this->data['games'] = $games;
		$this->data['locations'] = $locations;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/order', $this->data);
	}
	
	public function ajax() {
		$this->load->checkLicense();
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 1) {
	  		$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('users');
		$this->load->model('games');
		$this->load->model('locations');
		$this->load->model('servers');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$gameid = $this->request->post['gameid'];
				$locationid = $this->request->post['locationid'];
				$slots = $this->request->post['slots'];
				$months = $this->request->post['months'];
				$password = $this->request->post['password'];
				$mysql = $this->request->post['mysql'];
				
				$userid = $this->user->getId();
				$balance = $this->user->getBalance();
				
				$game = $this->gamesModel->getGameById($gameid);
				$port = $this->serversModel->getServerNewPort($locationid, $game['game_min_port'], $game['game_max_port']);
				if($port) {
					$price = $slots * $game['game_price'];
				
					switch($months) {
						case "3":
							$months = 3;
							$price = $price * 0.95;
							break;
						case "6":
							$months = 6;
							$price = $price * 0.90;
							break;
						case "12":
							$months = 12;
							$price = $price * 0.85;
							break;
						default:
							$months = 1;
					}
				
					$price = $price * $months;
				
					if($balance >= $price) {
						$serverData = array(
							'user_id'			=> $userid,
							'game_id'			=> $gameid,
							'location_id'		=> $locationid,
							'server_database'	=> $mysql,
							'server_slots'		=> $slots,
							'server_port'		=> $port,
							'server_password'	=> $password,
							'server_status'		=> 1,
							'server_months'		=> $months
						);
					
						$serverid = $this->serversModel->createServer($serverData);
						$this->serversModel->execServerAction($serverid, "install");
						$this->usersModel->downUserBalance($userid, $price);
					
						$this->data['status'] = "success";
						$this->data['success'] = "Сервер будет установлен в течении 10 минут!";
						$this->data['id'] = $serverid;
					} else {
						$this->data['status'] = "error";
						$this->data['error'] = "На Вашем счету недостаточно средств!";
					}
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "На выбранной Вами локации нет свободных портов для данной игры!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	private function validatePOST() {
		$this->load->checkLicense();
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$gameid = @$this->request->post['gameid'];
		$locationid = @$this->request->post['locationid'];
		$slots = @$this->request->post['slots'];
		$months = @$this->request->post['months'];
		$password = @$this->request->post['password'];
		$password2 = @$this->request->post['password2'];
		
		if(!$this->gamesModel->getTotalGames(array('game_id' => (int)$gameid, 'game_status' => 1))) {
			$result = "Вы указали несуществующую игру!";
		}
		elseif(!$this->locationsModel->getTotalLocations(array('location_id' => (int)$locationid, 'location_status' => 1))) {
			$result = "Вы указали несуществующую локацию!";
		}
		elseif(!$this->gamesModel->validateSlots($gameid, $slots)) {
			$result = "Вы указали недопустимое количество слотов!";
		}
		elseif(!$validateLib->password($password)) {
			$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
		}
		elseif($password != $password2) {
			$result = "Введенные вами пароли не совпадают!";
		}
		return $result;
	}
}
?>
