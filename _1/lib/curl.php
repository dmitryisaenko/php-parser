<?php

class curl{
	private $ch;	 // экземляр курла
	private $host; // хост - базовая часть урла без слеша на конце
	private $cfg;  // config.php
	public $opt = array(); // запоминаем опции курла, если не нужна извне - лучше сделать приватной

	//
	// Инициализация класса для конкретного домена
	//
	public static function app($host){
		return new self($host);
	}

	private function __construct($host){
		$this->ch = curl_init();
		$this->host = $host;
		$this->cfg = include_once('config/config.php');
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	}

	public function __destruct(){
		curl_close($this->ch);
	}

	// $name - может принимать как alias настроек - ssl, header, user_agent
	// так и любую, не определенную в config.php, константу - CURLOPT_PROXY
	public function set($name, $act = null){
		$param = isset($this->cfg[$name]) ? $this->cfg[$name] : array($name => $act);

		foreach($param as $key => $val) {
			$act = ($act) ? $act : $val; //Суть строки - перезаписать настройки, если пользователь указал их как второй параметр
			$this->opt[$key] = is_bool($act) ? (int)$act : $act; // Запоминаем настройки
			$param[$key] = $act; // Задаем значения
		}

		curl_setopt_array($this->ch, $param); // Устанавливаем параметры скопом
		return $this;
	}

	public function request($url){
		$this->set(CURLOPT_URL, $this->make_url($url));
		$data = curl_exec($this->ch);
		return $this->process_result($data);
	}

	public function config_load($file){
		$data = file_get_contents("config/curl/{$file}");
		$data = unserialize($data);

		curl_setopt_array($this->ch, $data);

		foreach($data as $key => $val) {
			$this->opt[$key] = $val;
		}
		return $this;
	}

	public function config_save($file){
		$data = serialize($this->opt);
		file_put_contents("config/curl/{$file}", $data);
		return $this;
	}

	private function make_url($url){
		if($url[0] != '/')
			$url = '/' . $url;

		return $this->host . $url;
	}

	private function process_result($data){
		/* Если HEADER отключен */
		if(!isset($this->opt[CURLOPT_HEADER]) || !$this->opt[CURLOPT_HEADER]) {
			return array(
				'headers' => array(),
				'html' => $data
			);
		}

		/* Разделяем ответ на headers и body */
		$info = curl_getinfo($this->ch);
		
		$headers_part = trim(substr($data, 0, $info['header_size'])); // trim - чтобы обрезать перенос строки в конце
		$body_part = substr($data, $info['header_size']);

		/* Определяем символ переноса строки */
		$headers_part = str_replace("\r\n", "\n", $headers_part); // винда в никсовую
		$headers = str_replace("\r", "\n", $headers_part); // мак в никсовую

		/* Берем последний headers */
		$headers = explode("\n\n", $headers);
		$headers_part = end($headers);

		/* Парсим headers */
		$lines = explode("\n", $headers_part);
		$headers = array();

		$headers['start'] = $lines[0];

		for($i = 1; $i < count($lines); $i++){
			$del_pos = strpos($lines[$i], ':');
			$name = substr($lines[$i], 0, $del_pos);
			$value = substr($lines[$i], $del_pos + 2);
			$headers[$name] = $value;
		}

		return array(
			'headers' => $headers,
			'html' => $body_part
		);
	}

	/*
		Вспомагательные методы
	*/
	public function post($act){
		if ($act === false) {
            $this->set(CURLOPT_POST, false);
            return $this;
		}
		
		$this->set(CURLOPT_POST, true);
		$this->set(CURLOPT_POSTFIELDS, http_build_query($act));
		return $this;
	}

	public function cookie(){
		$this->set(CURLOPT_COOKIEJAR,  $_SERVER['DOCUMENT_ROOT'] . '/config/cookie.txt');
		$this->set(CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . '/config/cookie.txt');
		return $this;
	}

	public function random_agent(){
		// https://udger.com/resources/ua-list - больше агентов тут
		$data = file("config/agents.txt");
		$this->set('user_agent', trim($data[rand(0, count($data) - 1)]));
		return $this;
	}

	public function gett($name){
		echo curl_getinfo($this->ch, CURLINFO_PRETRANSFER_TIME);
	}
}