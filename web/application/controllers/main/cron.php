<?php
class cronController extends Controller {
	public function index() {
		$this->load->checkLicense();
		$this->load->library('mail');
		$this->load->model('servers');
		$this->load->model('serversStats');

		$token = @$this->request->get['token'];
		if($this->config->token != $token) {
			return "Access Denied";
		}
		
		$mailLib = new mailLibrary();
		
		$mailLib->setFrom($this->config->mail_from);
		$mailLib->setSender($this->config->mail_sender);
		
		$servers = $this->serversModel->getServers(array(), array('users'));
		
		$datenow = date_create('now');
		
		foreach($servers as $item) {
			$serverid = $item['server_id'];
			$dateend = date_create($item['server_date_end']);
			$diff = date_diff($datenow, $dateend);
			
			if($diff->invert) {
				if($diff->days >= 3) {
					// Удаление
					$this->serversModel->execServerAction($serverid, 'delete');
					$this->serversModel->deleteServer($serverid);
					$this->serversStatsModel->deleteServerStats($serverid);
					
					echo "gs$item[server_id] - удален.\n";
					
					// Отправка уведомления
					$mailLib->setTo($item['user_email']);
					$mailLib->setSubject("Удаление сервера #$serverid");
					
					$mailData = array();
					$mailData['firstname'] = $item['user_firstname'];
					$mailData['lastname'] = $item['user_lastname'];
					$mailData['serverid'] = $serverid;
					
					$text = $this->load->view('mail/servers/deleted', $mailData);
					
					$mailLib->setText($text);
					$mailLib->send();
				} else {
					// Блокировка
					$this->serversModel->execServerAction($serverid, 'stop');
					$this->serversModel->updateServer($serverid, array('server_status' => 0));
					//$mailLib->execServerAction($serverid, 'stop');
					//$mailLib->updateServer($serverid, array('server_status' => 0));
					echo "gs$item[server_id] - заблокирован.\n";
					
					// Отправка уведомления
					$mailLib->setTo($item['user_email']);
					$mailLib->setSubject("Блокировка сервера #$serverid");
			
					$mailData = array();
					$mailData['firstname'] = $item['user_firstname'];
					$mailData['lastname'] = $item['user_lastname'];
					$mailData['serverid'] = $serverid;
			
					$text = $this->load->view('mail/servers/lock', $mailData);
			
					$mailLib->setText($text);
					$mailLib->send();
				}
			} else {
				if($diff->days < 3) {
					echo "gs$item[server_id] - отправлено уведомление.\n";
					
					// Отправка уведомления
					$mailLib->setTo($item['user_email']);
					$mailLib->setSubject("Завершение оплаченного периода сервера #$serverid");
					
					$mailData = array();
					$mailData['firstname'] = $item['user_firstname'];
					$mailData['lastname'] = $item['user_lastname'];
					$mailData['serverid'] = $serverid;
					$mailData['days'] = $diff->days;
			
					$text = $this->load->view('mail/servers/needPay', $mailData);
			
					$mailLib->setText($text);
					$mailLib->send();
				}
			}
		}
		return null;
	}
	
	public function updateSystemLoad() {
		$this->load->checkLicense();
		$this->load->model('servers');
		
		$token = @$this->request->get['token'];
		if($this->config->token != $token) {
			return "Access Denied";
		}
		
		$servers = $this->serversModel->getServers(array());
		
		foreach($servers as $item) {
			$serverid = $item['server_id'];
			
			if($item['server_status'] == 2) {
				$sysload = $this->serversModel->getServerSystemLoad($serverid);
				$this->serversModel->updateServer($serverid, array(
					'server_cpu_load'	=>	$sysload['cpu'],
					'server_ram_load'	=>	$sysload['ram']
				));
			}
		}
		return null;
	}
	
	public function updateStats() {
		$this->load->checkLicense();
		$this->load->library('query');
		$this->load->model('servers');
		$this->load->model('serversStats');
		
		$token = @$this->request->get['token'];
		if($this->config->token != $token) {
			return "Access Denied";
		}
		
		$servers = $this->serversModel->getServers(array(), array('games', 'locations'));
		
		// Удаление устаревшей статистики
		$this->serversStatsModel->clearServersStats();
		
		foreach($servers as $item) {
			$serverid = $item['server_id'];
			
			if($item['server_status'] == 2) {
				$queryLib = new queryLibrary($item['game_query']);
				$queryLib->connect($item['location_ip'], $item['server_port']);
				$query = $queryLib->getInfo();
				$queryLib->disconnect();
				
				$this->serversStatsModel->createServerStats(array(
					'server_id'				=> $serverid,
					'server_stats_players'	=> $query['players']
				));
			} else {
				$this->serversStatsModel->createServerStats(array(
					'server_id'				=> $serverid,
					'server_stats_players'	=> 0
				));
			}
		}
		return null;
	}
}
?>
