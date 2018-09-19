<?php
session_start();

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
    $client->authenticate($_GET['code']);
    $_SESSION['access_token_google'] = $client->getAccessToken();
	
	 $client->setAccessToken($_SESSION['access_token_google']);
	 $drive = new Google_Service_Drive($client);
	 
     $main_folder=$_SESSION['user_name'];
     echo $main_folder;
	// $fileMetadata = new Google_Service_Drive_DriveFile(array(
    //     'name' => $main_folder,
    //     'mimeType' => 'application/vnd.google-apps.folder'));
    // $file = $drive->files->create($fileMetadata, array('fields' => 'id'));
    // $folderId = $file->id;
    
    $links = $_SESSION['links'];
	$graphNode = $_SESSION['GraphNode'];
	$albumname=$_SESSION['Selected_albums'];

    #move to google drive
    function moveToDrive($album_id,$folderId,$drive,&$album_pic_link) {
        $img=$album_pic_link;
        print_r($img);       
        $fileMetadata1 = new Google_Service_Drive_DriveFile(array(
            'name' => $album_id,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => array($folderId)
        ));
        
        $file = $drive->files->create($fileMetadata1, array('fields' => 'id'));
        $album_folder = $file->id;
        
        for($i=0;$i<count($img);$i++)
        {
            $fileMetadata2 = new Google_Service_Drive_DriveFile(array(
                    'name' => $i.'.jpg',
                    'parents' => array($album_folder)
                ));
                $x=$img[$i];
                $content = file_get_contents($x);
                $file = $drive->files->create($fileMetadata2, array(
                    'data' => $content,
                    'mimeType' => 'image/jpeg',
                    'uploadType' => 'multipart',
                    'fields' => 'id'));
        }
        
        
    }
    
    #get links from albums
    function get_pictures($graphNode,$NameNLinks) {
        $album_pic_link = array();
		$graphnode = $graphNode;
		#user name and id for creation main user directory on server
		$user_name = $graphnode['name'];
			$index=0;
			$x=array();
			$urls = explode(' ', $NameNLinks[1]);
			foreach($urls as $url)
			   {
                $album_pic_link[] = $url;
				// 	$download_file = file_get_contents($url);
				// if($download_file==NULL){
				// }
				// else{
				// 	file_put_contents($path1 . '/' . $index . '.jpg',$download_file);
				// 	$index++;
				// 	$download_file=NULL;
				// }
                }
                print_r($album_pic_link);
                //moveToDrive($NameNLinks[0],$folderId,$drive,$album_pic_link);
    }
	#get selected albums.
	function get_album($graphNode,$selected_album,$links) {
		
		$total = count($selected_album);
			for($i=0;$i<$total;$i++) {
				$temp = trim($selected_album[$i]," ");
				$allAlbums = explode(',', $links);
					foreach($allAlbums as $ab) {
						   $NameNLinks = explode('||', $ab);
						   $album_name = trim($NameNLinks[0]," ");
							if($temp == $album_name){
		//						echo $temp . "<br/>";
								#get pictures from albums.
								get_pictures($graphNode,$NameNLinks);
							}
						}
				
			}
		
	}
    get_album($graphNode,$albumname,$links);

}




//********

        
	    
//********************	


?>

