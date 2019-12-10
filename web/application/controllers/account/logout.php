<?php
class logoutController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('logout');
		
		if(!$this->user->isLogged()) {
			$this->session->data['error'] = "Вы не авторизированы!";
			$this->response->redirect($this->config->url . 'account/login');
		}
		
		$this->user->logout();
		
		$this->session->data['success'] = "Вы успешно вышли из своего аккаунта!";
		$this->response->redirect($this->config->url);
		
		return null;
	}
}
?>
