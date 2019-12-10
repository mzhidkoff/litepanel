<?php
class createController extends Controller {
	public function index($ticketid = null) {
		$this->load->checkLicense();
		$this->document->setActiveSection('tickets');
		$this->document->setActiveItem('create');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('tickets/create', $this->data);
	}
	
	public function ajax($ticketid = null) {
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
		
		$this->load->model('tickets');
		$this->load->model('ticketsMessages');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$name = @$this->request->post['name'];
				$text = @$this->request->post['text'];
				
				$userid = $this->user->getId();
				
				$ticketData = array(
					'user_id'			=> $userid,
					'ticket_name'		=> $name,
					'ticket_status'		=> 1
				);
				$ticketid = $this->ticketsModel->createTicket($ticketData);
				
				$messageData = array(
					'ticket_id'			=> $ticketid,
					'user_id'			=> $userid,
					'ticket_message'	=> $text
				);
				$this->ticketsMessagesModel->createTicketMessage($messageData);
				
				$this->data['status'] = "success";
				$this->data['success'] = "Вы успешно создали запрос!";
				$this->data['id'] = $ticketid;
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
			$result = "Название тикета должно содержать от 6 до 32 символов!";
		}
		elseif(mb_strlen($text) < 10 || mb_strlen($text) > 350) {
			$result = "Текст тикета должен содержать от 10 до 350 символов!";
		}
		return $result;
	}
}
