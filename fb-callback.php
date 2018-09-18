<?php
   // session_start();
	require_once("config.php");
	$helper = $fb->getRedirectLoginHelper();

	try {
	  $accessToken = $helper->getAccessToken();
	} catch(lib\Facebook\Exceptions\FacebookResponseException $e) {
	  // When Graph returns an error
	  echo 'Graph returned an error: ' . $e->getMessage();
	  exit;
	} catch(lib\Facebook\Exceptions\FacebookSDKException $e) {
	  // When validation fails or other local issues
	  echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  exit;
	}
	
	if (! isset($accessToken)) {
	  if ($helper->getError()) {
		header('HTTP/1.0 401 Unauthorized');
		echo "Error: " . $helper->getError() . "\n";
		echo "Error Code: " . $helper->getErrorCode() . "\n";
		echo "Error Reason: " . $helper->getErrorReason() . "\n";
		echo "Error Description: " . $helper->getErrorDescription() . "\n";
	  } else {
		header('HTTP/1.0 400 Bad Request');
		echo 'Bad request';
	  }
	  exit;
	}
	if (!$accessToken->isLongLived()){
		$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
	}
	// Logged in
	// echo '<h3>Access Token</h3>';
	$access_token = $accessToken->getValue(); 
	$_SESSION['access_token'] = $access_token;
	//var_dump($accessToken->getValue());
	
?>