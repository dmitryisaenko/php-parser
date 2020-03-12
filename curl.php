<?php

class curl{
    private $ch;
    private $host;

    public static function app($host){
        return new self($host);
    }

    private function __construct($host){
        $this->ch = curl_init();
        $this->host = $host;
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true); //чтобы данные НЕ возвращались на экран
    }

    public function __destruct(){
        curl_close($this->ch);
    }

    public function set($name, $value){
        curl_setopt($this->ch, $name, $value);
        return $this; //Возвращаем текущий объект, чтобы вызывать эту функцию можно было "цепочкой"
    }

    public function request($url){
        curl_setopt($this->ch, CURLOPT_URL, $this->makeUrl($url));
        $data = curl_exec($this->ch);
        return $this->process_result($data);
    }

    public function ssl($actStatus){
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $actStatus);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $actStatus);
        return $this;
    }

    // Добавляем слеш, если его нет в конце хоста (но перед URI):
    private function makeUrl($url){
        if ($url[0] != '/')
            $url = '/' . $url;
        return $this->host . $url;
    }

    // Определяем символ пустой строки - \n\n или \r\n\r\n:
    private function process_result($data){
        $p_n = "\n";
        $p_rn = "\r\n";

        // Определяем позицию того или иного символа пустой строки(int). И ориентируемся на меньшее число.
        // Через false (если оба) проверять нельзя, т.к. пустая строка может быть и в html-документе
        $h_end_n = strpos($data, $p_n . $p_n);
        $h_end_rn = strpos($data, $p_rn . $p_rn);

        $start = $h_end_n;
        $p = $p_n;

        // Если разделителя "\n\n" нет или разделитель "\r\n\r\n" встречается раньше:
        if ($h_end_n === false || $h_end_rn < $h_end_n){
            $start = $h_end_rn;
            $p = $p_rn;
        }

        $header_part = substr($data, 0, $start);
        $body_part = substr($data, $start + strlen($p) * 2);


        $lines = explode($p, $header_part);
        $headers = array();

        $headers['start'] = $lines[0];

        for ($i = 1; $i < count($lines); $i++){
            $dellim_pos = strpos($lines[$i], ':');
            $name = substr($lines[$i], 0, $dellim_pos);
            $value = substr($lines[$i], $dellim_pos +2);
            $headers[$name] = $value;
        }


        return array(
            'headers' => $headers,
            'html' => $body_part
        ); 
    }
}

?>