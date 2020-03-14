<?php
  include_once 'lib/curl.php';

  

  $c = curl::app('https://en.wikipedia.org')
          ->config_load('wiki.cfg');
          // ->set('ssl')
          // ->set('header')  
          // ->set('headers', $headers)
          // ->set('follow')      
          // ->post($post)
          // ->cookie();
          // ->random_agent();

  // $c->config_load('main');
  // $c->config_save('main');

  $data = $c->request('wiki/S%C3%A3o_Lu%C3%ADs');

  var_dump($data);