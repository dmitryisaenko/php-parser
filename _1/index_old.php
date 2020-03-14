<?php

    include_once 'curl.php';

    // $c = curl::app('https://ntschool.ru')
    //                 ->set(CURLOPT_HEADER, 1);
                    // ->set(CURLOPT_FOLLOWLOCATION, 1);

    $post = array(
        '_qf__login_form' => '',
        'qf:token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpcCI6IjE3OC43NC4yMzcuNDciLCJleHAiOjE1ODM3NDg5MTN9.JzrT7BboE1MFPksknCXzACIq4s27X04_FJvu8hI6jBM',
        'login' => 'isaenkodmitry',
        'password' => 'pa3KU6@wFbO9',
        // 'cookieuser' => 'on',

    );

    $c = curl::app('https://freelancehunt.com')
                    ->set(CURLOPT_HEADER, 1)
                    ->set(CURLOPT_FOLLOWLOCATION, 1)
                    ->set(CURLOPT_POST, 1)
                    ->set(CURLOPT_POSTFIELDS, http_build_query($post))
                    ->set(CURLOPT_COOKIEJAR,  $_SERVER['DOCUMENT_ROOT'] . '/1.txt')
					->set(CURLOPT_COOKIEFILE, $_SERVER['DOCUMENT_ROOT'] . '/1.txt');
                    // ->set(CURLOPT_SSL_VERIFYPEER, 0)
                    // ->set(CURLOPT_SSL_VERIFYHOST, 0);

                    // curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    // $c = curl::app('https://en.wikipedia.org')
                    // ->set(CURLOPT_HEADER, 1);

    // $html = $c->request('home');
    
    $data = $c->request('profile/login');
    
    // $data = $c->request('');
    // echo '<pre>';
    print_r($data);
    // echo '</pre>';


?>