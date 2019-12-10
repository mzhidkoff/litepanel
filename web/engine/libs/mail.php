<?php
class mailLibrary {
	protected $to;
	protected $from;
	protected $sender;
	protected $subject;
	protected $text;

	// Данные SMTP-сервера
	private $smtp_server = 'ssl://smtp.yandex.ru';
	private $smtp_port = 465;
	private $smtp_user = 'yourmail@yandex.ru';
	private $smtp_password = 'yourpassword';

	public function setTo($to) {
		$this->to = $to;
	}
	
	public function setFrom($from) {
		$this->from = $from;
	}
	
	public function setSender($sender) {
		$this->sender = $sender;
	}
	
	public function setSubject($subject) {
		$this->subject = $subject;
	}
	
	public function setText($text) {
		$this->text = $text;
	}
	
	public function send() {
		if (!$this->to) {
			exit("Error: E-Mail to required!");
		}
		
		if (!$this->from) {
			exit("Error: E-Mail from required!");
		}
		
		if (!$this->sender) {
			exit("Error: E-Mail sender required!");
		}
		
		if (!$this->subject) {
			exit("Error: E-Mail subject required!");
		}
		
		if (!$this->text) {
			exit("Error: E-Mail message required!");
		}
		
		if (is_array($this->to)) {
			$this->to = implode(',', $this->to);
		}
		
		// Headers
		$header = "";

		$header .= "MIME-Version: 1.0\n";

		$header .= "Subject: " . $this->subject . "\n";
		$header .= "To: " . $this->to . "\n";
		$header .= "From: " . $this->sender . " <" . $this->from . ">\n";
		$header .= "Reply-To: " . $this->sender . "\n";
		$header .= "X-Mailer: PHP Mailer\n";
		$header .= "Return-Path: " . $this->sender . "\n";
		$header .= "Content-Type: text/plain; charset=\"utf-8\"\n";

		return $this->smtp_mail($header);

		//return mail($this->to, $this->subject, $this->text, $header);
	}
	
	/*
	* Функция отправки SMTP письма
	*/
	private function smtp_mail($header) {
		if (!$sock = fsockopen($this->smtp_server, $this->smtp_port, $errno, $errstr, 10)) return false;

		$str = fgets($sock, 512);

		$this->smtp_msg($sock, "HELO " . $_SERVER['SERVER_NAME']); // $this->smtp_server
		$this->smtp_msg($sock, "AUTH LOGIN");
		$this->smtp_msg($sock, base64_encode($this->smtp_user));
		$this->smtp_msg($sock, base64_encode($this->smtp_password));
		$this->smtp_msg($sock, "MAIL FROM: <" . $this->from . ">");
		$this->smtp_msg($sock, "RCPT TO: <" . $this->to . ">");
		$this->smtp_msg($sock, "DATA");

		$headers = "";
		$headers .= $header;

		$data = $headers . "\r\n" . $this->text . "\r\n.";

		$this->smtp_msg($sock, $data);
		$this->smtp_msg($sock, "QUIT");

		return fclose($sock);
	}

	/*
	* Функция отправки SMTP-пакетов
	*/
	private function smtp_msg($sock, $msg) {
		fputs($sock, "$msg\r\n");

		$str = fgets($sock, 512);
		$e = explode(" ", $str);
		$code = array_shift($e);
		$str = implode(" ", $e);

		if ($code > 499) return false;

		return true;
	}
}
?>
