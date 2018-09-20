<?php
session_start();

echo "hello";

require_once 'google/vendor/autoload.php';

ini_set('max_execution_time', 300);
$client = new Google_Client();
$client->setAuthConfigFile('api.json');
$client->setRedirectUri('https://abhishekrtchallenge.herokuapp.com/google_drive_new.php');
$client->addScope(Google_Service_Drive::DRIVE);

if (! isset($_GET['code'])) 
{
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
} 
else 
{
    echo "else";
}

?>

