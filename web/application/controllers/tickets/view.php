<?php
class viewController extends Controller {
	public function index($ticketid = null) {
		$this->load->checkLicense();
		$this->document->setActiveSection('tickets');
		$this->document->setActiveItem('index');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		if($this->user->getAccessLevel() < 0) {
			$this->session->data['error'] = "У вас нет доступа к данному разделу!";
			$this->response->redirect($this->config->url);
		}
		
		$this->load->model('tickets');
		$this->load->model('ticketsMessages');
		
		$error = $this->validate($ticketid);
		if($error) {
			$this->session->data['error'] = $error;
			$this->response->redirect($this->config->url . 'tickets/index');
		}
		
		$ticket = $this->ticketsModel->getTicketById($ticketid, array('users'));
		$messages = $this->ticketsMessagesModel->getTicketsMessages(array('ticket_id' => $ticketid), array('users'));
		$this->data['ticket'] = $ticket;
		$this->data['messages'] = $messages;
		
		$this->getChild(array('common/header', 'common/footer'));
		return $this->load->view('tickets/view', $this->data);
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
		
		$error = $this->validate($ticketid);
		if($error) {
	  		$this->data['status'] = "error";
			$this->data['error'] = $error;
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST($ticketid);
			if(!$errorPOST) {
				$text = @$this->request->post['text'];
				$closeticket = @$this->request->post['closeticket'];
				
				$userid = $this->user->getId();
				
				if($closeticket) {
					$this->ticketsModel->updateTicket($ticketid, array('ticket_status' => 0));
					
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно закрыли тикет!";
				} else {
					$messageData = array(
						'ticket_id'			=> $ticketid,
						'user_id'			=> $userid,
						'ticket_message'	=> $text
					);
					$this->ticketsModel->updateTicket($ticketid, array('ticket_status' => 1));
					$this->ticketsMessagesModel->createTicketMessage($messageData);
					
					$this->data['status'] = "success";
					$this->data['success'] = "Вы успешно отправили сообщение!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	private function validate($ticketid) {
		$this->load->checkLicense();
		$result = null;
		
		$userid = $this->user->getId();
		
		if(!$this->ticketsModel->getTotalTickets(array('ticket_id' => (int)$ticketid, 'user_id' => (int)$userid))) {
			$result = "Запрашиваемый тикет не существует!";
		}
		return $result;
	}
	
	private function validatePOST($ticketid) {
		$this->load->checkLicense();
		$result = null;
		
		$text = @$this->request->post['text'];
		$closeticket = @$this->request->post['closeticket'];
		
		if(!$closeticket) {
			if(mb_strlen($text) < 10 || mb_strlen($text) > 350) {
				$result = "Текст сообщения должен содержать от 10 до 350 символов.";
			}
		}
		return $result;
	}
}
