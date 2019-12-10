<?php
class createController extends Controller {

	public function index($ticketid = null) {
		$this->load->checkLicense();
		$this->document->setActiveSection('admin');
		$this->document->setActiveItem('news');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 2) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('news/create', $this->data);
	}
	
	public function ajax($ticketid = null) {
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
		
		$this->load->model('news');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$text = @$this->request->post['text'];
				
				$userid = $this->user->getId();
				
				$newsData = array(
					'user_id'			=> $userid,
					'news_title'		=> $name,
					'news_text'			=> $text,
				);
				$newsid = $this->newsModel->createNews($newsData);

				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно создали новость!";
				$this->data['id'] = $newsid;
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	private function validatePOST() {
		$this->load->checkLicense();
		$result = null;
		
		$name = @$this->request->post['name'];
		$text = @$this->request->post['text'];
		
		if(mb_strlen($name) < 6 || mb_strlen($name) > 32) {
			$result = "Название новости должно содержать от 6 до 32 символов!";
		}
		elseif(mb_strlen($text) < 10 || mb_strlen($text) > 350) {
			$result = "Текст новости должен содержать от 10 до 350 символов!";
		}
		return $result;
	}
}
