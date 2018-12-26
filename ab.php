<?php
/*
gfkihasdihidhisd
.ashdgckishcojhasc
lsidhcgkiasjcijhSCSD
CSDKSJDCHIS */
session_start();
$token= $_SESSION['access_token'];
//Calling
$url = "https://graph.facebook.com/v3.1/me?fields=albums%7Bid%2Cname%2Cphotos%7Bimages%7D%7D&access_token=".$token;
function getData($url)
{
	//  Initiate curl
	$ch = curl_init();
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($ch, CURLOPT_URL,$url);
	// Execute
	$result=json_decode(curl_exec($ch),true);
	// Closing
	curl_close($ch);      
	return $result;
}
   
$link=array();	
$links='';        	
function getNextParser($url,$tmp)
{
	$innerData = getData($url);
	foreach($innerData['data'] as $image)
	     {
		 $GLOBALS['links'].=$image['images'][0]['source']." ";
		 $tmp[]=($image['images'][0]['source']);
	     }
	if(isset($innerData['paging']['next'])){
		$tmp = getNextParser($innerData['paging']['next'],$tmp);
	}
}

// Main calling 
$result = getData($url);     		

foreach($result['albums']['data'] as $album)
{
	$GLOBALS['links'].=$album['name']."||";
	foreach($album['photos']['data'] as $image)
	{
		 $GLOBALS['links'].= ($image['images'][0]['source']);
		$tmp[]=($image['images'][0]['source']);
		$cnt++;
	}

	if(isset($album['photos']['paging']['next']))
	{
		$tmp =(getNextParser($album['photos']['paging']['next'],$tmp));
	}	
	$GLOBALS['links'].=" , ";
}	
$allAlbums = explode(',', $links);
foreach($allAlbums as $ab)
{
   $NameNLinks = explode('||', $ab);
   echo $NameNLinks[0]." ";
   $urls = explode(' ', $NameNLinks[1]);
   foreach($urls as $url)
   {
   	echo $url;
   }   
	echo "<br/><br/>";
}
?>	
