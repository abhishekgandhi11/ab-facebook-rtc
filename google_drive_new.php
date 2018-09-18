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
    print_r($links);
    echo "<br/><br/><pre>";
        print_r($graphNode);
    echo "</pre>";
	    // foreach ($albumname as $album)
		// {
        //     moveToDrive($album,$folderId,$drive);
        // }
   
   
}




//********

        
	    
//********************	
function moveToDrive($album_id,$folderId,$drive)
{
    
    
    
    
    $img=array("https://scontent.xx.fbcdn.net/v/t1.0-9/39760439_859661354230615_8995455293735305216_o.jpg?_nc_cat=0&oh=c1fe7ab85e829a26f1472d22138dc019&oe=5BEE1000", "https://scontent.xx.fbcdn.net/v/t1.0-0/p75x225/39799614_859661517563932_7874550640016359424_n.jpg?_nc_cat=0&oh=5a886fffc5164bf264fdc26a472d5aea&oe=5C33F5D5", "https://scontent.xx.fbcdn.net/v/t1.0-9/39917099_861599617370122_1588937861418188800_o.jpg?_nc_cat=0&oh=b8c60db2df8bb2278e709fef960e8ac6&oe=5C266E8B","https://scontent.xx.fbcdn.net/v/t1.0-0/p75x225/39944419_859661610897256_1205816779931123712_n.jpg?_nc_cat=0&oh=b828a9f10e9bd7ccc436af0a38afb1bd&oe=5C2CF670");
	
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


?>

