<?php
class editController extends Controller {
	public function index($gameid = null) {
		$this->load->checkLicense();
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('games');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('games');
		
		$error = $this->validate($gameid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/games/index');
		}
		
		$game = $this->gamesModel->getGameById($gameid);
		
		$this->data['game'] = $game;
		
		$this->data['queryDrivers'] = $this->getQueryDrivers();
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/games/edit', $this->data);
	}
	
	public function ajax($gameid = null) {
		$this->load->checkLicense();
		if(!$this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Вы не авторизированы!";
			return json_encode($this->data);
		}
		if($this->user->getAccessLevel() < 3) {
			$this->data['status'] = "error";
			$this->data['error'] = "У вас нет доступа к данному разделу!";
			return json_encode($this->data);
		}
		
		$this->load->model('games');
		
		$error = $this->validate($gameid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$code = @$this->request->post['code'];
				$query = @$this->request->post['query'];
				$minslots = @$this->request->post['minslots'];
				$maxslots = @$this->request->post['maxslots'];
				$minport = @$this->request->post['minport'];
				$maxport = @$this->request->post['maxport'];
				$price = @$this->request->post['price'];
				$status = @$this->request->post['status'];
				
				$gameData = array(
					'game_name'			=> $name,
					'game_code'			=> $code,
					'game_query'		=> $query,
					'game_min_slots'	=> (int)$minslots,
					'game_max_slots'	=> (int)$maxslots,
					'game_min_port'		=> (int)$minport,
					'game_max_port'		=> (int)$maxport,
					'game_price'		=> (float)$price,
					'game_status'		=> (int)$status
				);
				
				$this->gamesModel->updateGame($gameid, $gameData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно отредактировали информацию об игре!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	public function delete($gameid = null) {
		$this->load->checkLicense();
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('games');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('games');
		
		$error = $this->validate($gameid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/games/index');
		}
		
		$this->gamesModel->deleteGame($gameid);
		
		$this->session->data['success'] = "Вы успешно удалили игру!";
		$this->response->redirect($this->config->url . 'admin/games/index');
		return null;
	}
	
	private function validate($gameid) {
		$this->load->checkLicense();
		$result = null;
		
		if(!$this->gamesModel->getTotalGames(array('game_id' => (int)$gameid))) {
			$result = "Запрашиваемая игра не существует!";
		}
		return $result;
	}
	
	private function validatePOST() {
		$this->load->checkLicense();
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$name = @$this->request->post['name'];
		$code = @$this->request->post['code'];
		$query = @$this->request->post['query'];
		$minslots = @$this->request->post['minslots'];
		$maxslots = @$this->request->post['maxslots'];
		$minport = @$this->request->post['minport'];
		$maxport = @$this->request->post['maxport'];
		$price = @$this->request->post['price'];
		$status = @$this->request->post['status'];
		
		if(mb_strlen($name) < 2 || mb_strlen($name) > 32) {
			$result = "Название игры должно содержать от 2 до 32 символов!";
		}
		elseif(mb_strlen($code) < 2 || mb_strlen($code) > 8) {
			$result = "Код игры должен содержать от 2 до 8 символов!";
		}
		elseif(!in_array($query, $this->getQueryDrivers())) {
			$result = "Укажите допустимый Query-драйвер.";
		}
		elseif($maxslots < $minslots)
		{
			$result = "Укажите допустимый интервал слотов.";
		}
		elseif($maxport < $minport)
		{
			$result = "Укажите допустимый интервал портов.";
		}
		elseif(!$validateLib->money($price)) {
			$result = "Укажите допустимую стоимость!";
		}
		elseif($status < 0 || $status > 1) {
			$result = "Укажите допустимый статус!";
		}
		return $result;
	}
	
	function getQueryDrivers() {
		$result = array();
		$drivers = glob(ENGINE_DIR . 'libs/query/*.driver.php');
		foreach ($drivers as $filename) {
			$result[] = basename($filename, ".driver.php");
		}
		return $result;
	}
}
?>
