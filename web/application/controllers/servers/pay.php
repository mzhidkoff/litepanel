<?php
class payController extends Controller {
	public function index($serverid = null) {
		$this->load->checkLicense();
		$this->document->setActiveSection('servers');
		$this->document->setActiveItem('index');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('servers');
		
		$error = $this->validate($serverid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'servers/index');
		}
		
		$server = $this->serversModel->getServerById($serverid, array('games'));
		$this->data['server'] = $server;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('servers/pay', $this->data);
	}
	
	public function ajax($serverid = null) {
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
		$this->load->model('servers');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$months = $this->request->post['months'];
			
			$userid = $this->user->getId();
			$balance = $this->user->getBalance();
			
			$server = $this->serversModel->getServerById($serverid, array('games'));
			$price = $server['server_slots'] * $server['game_price'];
			
			switch($months) {
				case "3":
					// Скидка 5%
					$months = 3;
					$price = $price * 0.95;
					break;
				case "6":
					// Скидка 10%
					$months = 6;
					$price = $price * 0.90;
					break;
				case "12":
					// Скидка 15%
					$months = 12;
					$price = $price * 0.85;
					break;
				default:
					$months = 1;
			}
			
			$price = $price * $months;
			
			if($balance >= $price) {
				if($server['server_status'] == 0) {
					$this->serversModel->updateServer($serverid, array('server_status' => 1));
					$this->serversModel->extendServer($serverid, $months, true);
				} else {
					$this->serversModel->extendServer($serverid, $months, false);
				}
				$this->usersModel->downUserBalance($userid, $price);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно оплатили сервер!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = "На Вашем счету недостаточно средств!";
			}
		}

		return json_encode($this->data);
	}
	
	private function validate($serverid) {
		$this->load->checkLicense();
		$result = null;
		
		$userid = $this->user->getId();
		
		if(!$this->serversModel->getTotalServers(array('server_id' => (int)$serverid, 'user_id' => (int)$userid))) {
			$result = "Запрашиваемый сервер не существует!";
		}
		return $result;
	}
}
