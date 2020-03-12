<?php
  include_once 'lib/curl.php';

  $headers = array(
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
    // 'Accept-Encoding: gzip, deflate',
    'Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3'
  );

  $post = array(
    '_qf__login_form' => '',
    'qf:token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpcCI6IjE3OC43NC4yMzcuNDciLCJleHAiOjE1ODM4NDcyOTB9.Ahzz5Rm_CFzXOvqTm8BY6VMVpjC_Dj1RtcGE7HQ4-SU',
    'login' => 'isaenkodmitry',
    'password' => 'pa3KU6@wFbO9'
  );
  

  $c = curl::app('https://freelancehunt.com')
          ->set('ssl')
          ->set('header')  
          // ->set('headers', $headers)
          // ->set('follow')      
          ->post($post)
          ->cookie();
          // ->random_agent();

  // $c->config_load('main');
  // $c->config_save('main');
  $c->request('profile/login');
  $html = $c->post(false)->request('');
  print_r($html);