<?php
class createhostController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->load->model('users');
		$this->load->model('invoices');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$sum = @$this->request->post['sum'];
				$invid = @$this->request->post['invoice'];
				
				$invoice = $this->invoicesModel->getInvoiceById($invid);

				$this->usersModel->upUserBalance($invoice['user_id'], $sum);
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
		
		$sum = @$this->request->post['sum'];
		$invid = @$this->request->post['invoice'];
		$sign = @$this->request->post['sign'];
		
		if(!$this->invoicesModel->getTotalInvoices(array('invoice_id' => (int)$invid, 'invoice_status' => 0))) {
			$result = "Invalid invoice!";
		}
		elseif($sign != hash('sha256', $this->config->m_account.':'.$invid.':'.$sum.':'.$this->config->m_secret)) {
			$result = "Invalid signature!";
		}
		return $result;
	}
}
?>
