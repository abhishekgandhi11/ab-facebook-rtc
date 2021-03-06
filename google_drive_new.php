<?php
session_start();


require_once 'google/vendor/autoload.php';

ini_set('max_execution_time', 3000);
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
	$fileMetadata = new Google_Service_Drive_DriveFile(array(
        'name' => $main_folder,
        'mimeType' => 'application/vnd.google-apps.folder'));
    $file = $drive->files->create($fileMetadata, array('fields' => 'id'));
    $folderId = $file->id;

    #put main folder id in session.
    $_SESSION['folder_id'] = $folderId;
    $links = $_SESSION['links'];
	$graphNode = $_SESSION['GraphNode'];
    $albumname=$_SESSION['Selected_albums'];
   try{
    #get selected albums.
   get_album($graphNode,$albumname,$links,$drive);
   header('location:drive.php');
   }
   catch(Exception $e){
        header('location:drive.php');
   }
    
}
#get selected albums.
function get_album($graphNode,$selected_album,$links,$drive) {
    $total = count($selected_album);
        for($i=0;$i<$total;$i++) {
            $temp = trim($selected_album[$i]," ");
            $allAlbums = explode(',', $links);
                foreach($allAlbums as $ab) {
                       $NameNLinks = explode('||', $ab);
                       $album_name = trim($NameNLinks[0]," ");
                        if($temp == $album_name){
                            //  echo $aname;
                            $aname = $NameNLinks[0];
                            //  echo $aname;
                            #get pictures from albums.
                             get_pictures($graphNode,$NameNLinks,$aname,$drive);
                        }
                    }
            
        }
    
}
#get pictures from albums.
function get_pictures($graphNode,$NameNLinks,$aname,$drive) {
    $album_pic_link = array();
    $album_pic_link = NULL;
    $urls = explode(' ', $NameNLinks[1]);
    foreach($urls as $url)
    {
        if($url!=NULL) {
            $album_pic_link[] = $url;
        }
    }   
    #this function can create sub directory on google drive and put images in that folders.
    ab_test($album_pic_link,$aname,$drive);        
}

#this function can create sub directory on google drive and put images in that folders.
function ab_test($album_ar,$aname,$drive){
    $album_id = $aname;
    $folder_Id = $_SESSION['folder_id']; 
    #create directory with album name.
    $fileMetadata1 = new Google_Service_Drive_DriveFile(array(
        'name' => $album_id,
        'mimeType' => 'application/vnd.google-apps.folder',
        'parents' => array($folder_Id)
    ));
	
    $file = $drive->files->create($fileMetadata1, array('fields' => 'id'));
    $album_folder = $file->id;

    #put images in directory on google drive.
    $i=0;
    foreach(array_slice($album_ar,1) as $url1) {
        $fileMetadata2 = new Google_Service_Drive_DriveFile(array(
            'name' => $i.'.jpg',
            'parents' => array($album_folder)
        ));
        $imgname=$url1;
        $content = file_get_contents($imgname);
        $file = $drive->files->create($fileMetadata2, array(
            'data' => $content,
            'mimeType' => 'image/jpeg',
            'uploadType' => 'multipart',
            'fields' => 'id'));
            $i++;
    }
    echo $i;
}

?>

