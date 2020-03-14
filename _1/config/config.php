<?php
  /*
    Идея в том, чтобы в одном месте собрать основные настройки и удобно с ними работать.
    Можно использовать как дефолтные значения ->set('follow'),
    так и переопределять их ->set('follow', 0);
  */
  $cfg = array();

  $cfg['ssl']        = array(CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYHOST => 0);
  $cfg['header']     = array(CURLOPT_HEADER => 1);
  $cfg['follow']     = array(CURLOPT_FOLLOWLOCATION => 1);
  $cfg['headers']    = array(CURLOPT_HTTPHEADER => false);
  $cfg['referer']    = array(CURLOPT_REFERER => 'http://www.google.com');
  $cfg['user_agent'] = array(CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0');

  return $cfg;