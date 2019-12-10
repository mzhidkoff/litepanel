<?php
class editController extends Controller {
	public function index($locationid = null) {
		$this->load->checkLicense();
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('locations');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('locations');
		
		$error = $this->validate($locationid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/locations/index');
		}
		
		$location = $this->locationsModel->getLocationById($locationid);
		
		$this->data['location'] = $location;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('admin/locations/edit', $this->data);
	}
	
	public function ajax($locationid = null) {
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
		
		$this->load->model('locations');
		
		$error = $this->validate($locationid);
		if($error) {
			$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$ip = @$this->request->post['ip'];
				$ip2 = @$this->request->post['ip2'];
				$user = @$this->request->post['user'];
				$password = @$this->request->post['password'];
				$password2 = @$this->request->post['password2'];
				$editpassword = @$this->request->post['editpassword'];
				$status = @$this->request->post['status'];
				
				$locationData = array(
					'location_name'			=> $name,
					'location_ip'			=> $ip,
					'location_ip2'			=> $ip2,
					'location_user'			=> $user,
					'location_status'		=> (int)$status
				);
				
				if($editpassword) {
					$locationData['location_password'] = $password;
				}
				
				$this->locationsModel->updateLocation($locationid, $locationData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно отредактировали локацию!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	public function delete($locationid = null) {
		$this->load->checkLicense();
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('locations');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 3) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('locations');
		
		$error = $this->validate($locationid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'admin/locations/index');
		}
		
		$this->locationsModel->deleteLocation($locationid);
		
		$this->session->data['success'] = "Вы успешно удалили локацию!";
		$this->response->redirect($this->config->url . 'admin/locations/index');
		return null;
	}
	
	private function validate($locationid) {
		$result = null;
		
		if(!$this->locationsModel->getTotalLocations(array('location_id' => (int)$locationid))) {
			$result = "Запрашиваемая локация не существует!";
		}
		return $result;
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$name = @$this->request->post['name'];
		$ip = @$this->request->post['ip'];
		$ip2 = @$this->request->post['ip2'];
		$user = @$this->request->post['user'];
		$editpassword = @$this->request->post['editpassword'];
		$password = @$this->request->post['password'];
		$password2 = @$this->request->post['password2'];
		$status = @$this->request->post['status'];
		
		if(mb_strlen($name) < 2 || mb_strlen($name) > 32) {
			$result = "Название локации должно содержать от 2 до 32 символов!";
		}
		elseif(mb_strlen($ip) < 2 || mb_strlen($ip) > 32) {
			$result = "Допустимый IP должен содержать от 2 до 32 символов!";
		}
		elseif(mb_strlen($ip2) < 2 || mb_strlen($ip2) > 32) {
			$result = "Допустимый IP должен содержать от 2 до 32 символов!";
		}
		elseif(mb_strlen($user) < 2 || mb_strlen($user) > 32) {
			$result = "Имя пользователя должно содержать от 2 до 32 символов!";
		}
		elseif($editpassword) {
			if(!$validateLib->password($password)) {
				$result = "Пароль должен содержать от 6 до 32 латинских букв, цифр и знаков <i>,.!?_-</i>!";
			}
			elseif($password != $password2) {
				$result = "Введенные вами пароли не совпадают!";
			}
		}
		elseif($status < 0 || $status > 1) {
			$result = "Укажите допустимый статус!";
		}
		return $result;
	}
}
?>
