<?php
//session_start();
require_once("key.php");
require_once("lib/Facebook/autoload.php");
$fb = new Facebook\Facebook([
  'app_id' => $app_id, // Application Id
  'app_secret' => $app_sec, //Application Secreate 
  'default_graph_version' => 'v3.1',
  ]);
$helper = $fb->getRedirectLoginHelper();
?>