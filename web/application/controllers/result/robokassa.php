<?php
class robokassaController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->load->model('users');
		$this->load->model('invoices');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$ammount = $this->request->post['OutSum'];
				$invid = $this->request->post['InvId'];
				$signature = $this->request->post['SignatureValue'];
				
				$invoice = $this->invoicesModel->getInvoiceById($invid);
				$userid = $invoice['user_id'];
				
				$this->usersModel->upUserBalance($userid, $ammount);
				$this->invoicesModel->updateInvoice($invid, array('invoice_status' => 1));
				return "OK$invid\n";
			} else {
				return "Error: $errorPOST";
			}
		} else {
			return "Error: Invalid request!";
		}
	}
	
	private function validatePOST() {
		$this->load->checkLicense();
		$result = null;
		
		$ammount = $this->request->post['OutSum'];
		$invid = $this->request->post['InvId'];
		$signature = $this->request->post['SignatureValue'];
		
		$password2 = $this->config->rk_password2;
		
		if(!$this->invoicesModel->getTotalInvoices(array('invoice_id' => (int)$invid))) {
			$result = "Invalid invoice!";
		}
		elseif($signature != strtoupper(md5("$ammount:$invid:$password2"))) {
			$result = "Invalid signature!";
		}
		return $result;
	}
}
?>
