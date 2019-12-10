<?php
class editController extends Controller {
	public function index($userid = null) {
		$this->load->checkLicense();
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('users');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('users');
		
		$error = $this->validate($userid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/users/index');
		}
		
		$user = $this->usersModel->getUserById($userid);
		
		$this->data['user'] = $user;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/users/edit', $this->data);
	}
	
	public function ajax($userid = null) {
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
		
		$this->load->model('users');
		
		$error = $this->validate($userid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$lastname = @$this->request->post['lastname'];
				$firstname = @$this->request->post['firstname'];
				$status = @$this->request->post['status'];
				$balance = @$this->request->post['balance'];
				$accesslevel = @$this->request->post['accesslevel'];
				$editpassword = @$this->request->post['editpassword'];
				$password = @$this->request->post['password'];
				
				$userData = array(
					'user_firstname'	=> $firstname,
					'user_lastname'		=> $lastname,
					'user_status'		=> (int)$status,
					'user_balance'		=> (float)$balance,
					'user_access_level'	=> (int)$accesslevel
				);
				
				if($editpassword) {
					$userData['user_password'] = md5($password);
				}
				
				$this->usersModel->updateUser($userid, $userData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно отредактировали информацию о клиенте!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	private function validate($userid) {
		$this->load->checkLicense();
		$result = null;
		
		if(!$this->usersModel->getTotalUsers(array('user_id' => (int)$userid))) {
			$result = "Запрашиваемый пользователь не существует!";
		}
		return $result;
	}
	
	private function validatePOST() {
		$this->load->checkLicense();
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$lastname = @$this->request->post['lastname'];
		$firstname = @$this->request->post['firstname'];
		$status = @$this->request->post['status'];
		$balance = @$this->request->post['balance'];
		$accesslevel = @$this->request->post['accesslevel'];
		$editpassword = @$this->request->post['editpassword'];
		$password = @$this->request->post['password'];
		$password2 = @$this->request->post['password2'];
		
		if(!$validateLib->lastname($lastname)) {
			$result = "Укажите реальную фамилию!";
		}
		elseif(!$validateLib->firstname($firstname)) {
			$result = "Укажите реальное имя!";
		}
		elseif(!$validateLib->userstatus($status)) {
			$result = "Укажите допустимый статус!";
		}
		elseif(!$validateLib->money($balance)) {
			$result = "Укажите допустимый баланс!";
		}
		elseif(!$validateLib->accesslevel($accesslevel)) {
			$result = "Укажите допустимый уровень доступа!";
		}
		elseif($editpassword) {
			if(!$validateLib->password($password)) {
				$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
			}
			elseif($password != $password2) {
				$result = "Введенные вами пароли не совпадают!";
			}
		}
		return $result;
	}
}
?>
