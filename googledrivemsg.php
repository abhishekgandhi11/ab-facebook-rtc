<?php

session_start();
include_once 'gmail/src/Google_Client.php';
include_once 'gmail/src/contrib/Google_Oauth2Service.php';
require_once 'gmail/src/contrib/Google_DriveService.php';

$client = new Google_Client();
$client->setClientId('603280148984-rvsoprf1vrsq2s5g8m2ud3tvg4psvufv.apps.googleusercontent.com');
$client->setClientSecret('M404H5bPQRZ8Bm57lfRxtFbz');
$client->setRedirectUri('https://abhishekrtchallenge.herokuapp.com/googledrivemsg.php');
$client->setScopes(array('https://www.googleapis.com/auth/drive.file'));
 

if (isset($_GET['code']) || (isset($_SESSION['access_token1']))) {
	
	
	$service = new Google_DriveService($client);
    if (isset($_GET['code'])) {
		$client->authenticate($_GET['code']);
		$_SESSION['access_token1'] = $client->getAccessToken();		
    } else
        $client->setAccessToken($_SESSION['access_token1']);
	
   
    //Insert a file
    $fileName= $_SESSION['session_zipname'];
	$file = new Google_DriveFile();
    $file->setTitle($fileName);
    $file->setMimeType('application/zip');
    $file->setDescription('A User Details is uploading in json format');
	//print_r($file);
    //exit;
    $path_name='download/'.$_SESSION['session_zipname'];
    $createdFile = $service->files->insert($file, array(
          'data' =>file_get_contents($path_name),
          'mimeType' => 'application/zip',
		  'uploadType'=>'multipart'
        ));
		
	//unlink($fileName);
    header('location:drive.php');
	//print_r($createdFile);

} else {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . $authUrl);
    exit();
}

?>
