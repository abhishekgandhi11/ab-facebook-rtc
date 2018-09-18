<?php
session_start();
ob_start();
error_reporting(0);
function get_pictures($graphNode,$selected_pictures,$album_row) {
    $zip_file_name;
    if(isset($selected_pictures)) {
        if(isset($selected_pictures) and count($selected_pictures) > 0){
            #get album name
            $album_name = $graphNode['albums'][$album_row]['name']; 	  #get user id 								
            $user_id = $graphNode['id'];
            
            #create path for server download file
            $path = 'download/' . $user_id . '/' . $album_name;
            mkdir($path, 0777, true);
            $cnt=0;
            
            
            foreach($selected_pictures as $file) { 
                //get files from link
                $download_file =  file_get_contents($file);
                file_put_contents($path . '/' . $cnt . '.jpg',$download_file);
                $cnt++; 
            }
            
            // Get real path for our folder
            $rootPath = realpath($path);
        
            # Initialize archive object
            $zip = new ZipArchive();
            $zip_file_name = $album_name.'.zip';
            $zip->open($zip_file_name, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        
            # Create recursive directory iterator
            /** @var SplFileInfo[] $files */
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
        
            foreach ($files as $name => $file)
            {
                # Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    # Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
            
                    # add files from download folder to zip file
                    $zip->addFile($filePath, $relativePath);
                }
            }
        
            # close zip file
            $zip->close();
        }                   
    }
    return $zip_file_name;
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Get Pictures</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php include("links.php"); ?>
		<link rel="stylesheet" href="lib/css/lightbox.css">
		<script type="text/javascript" src="lib/js/lightbox.min.js"></script>
		<script type="text/javascript">
				function selectAll(){
					var items=document.getElementsByName('checkbox_picture[]');
					for(var i=0; i<items.length; i++){
						if(items[i].type=='checkbox')
							items[i].checked=true;
					}
				}				
				function UnSelectAll(){
					var items=document.getElementsByName('checkbox_picture[]');
					for(var i=0; i<items.length; i++){
						if(items[i].type=='checkbox')
							items[i].checked=false;
					}
				}			
		</script>
	</head>
	<body>
		<form name="f1" method="post">
		<?php
			include("header.php");
			echo "<br/><br/>";
			echo "<div>";
					require_once("config.php");
					try {
						$accesstoken = $_SESSION['access_token'];
							// Returns a `FacebookFacebookResponse` object
							$response = $fb->get('me?fields=id,name,albums{id,name,count,photos.limit(100){images}}', $accesstoken);
						} catch(FacebookExceptionsFacebookResponseException $e) {
							echo 'Graph returned an error: ' . $e->getMessage();
						exit;
						} catch(FacebookExceptionsFacebookSDKException $e) {
							echo 'Facebook SDK returned an error: ' . $e->getMessage();
							exit;
						}
					$graphNode = $response->getGraphNode();
					//print_r($graphNode);
					if(empty($_GET)) {
						$album_id = $_SESSION['album_id'];	
					}
					else {
						$album_id = $_GET['id'];
						$_SESSION['album_id'] =$album_id;
					}
					$total_album = count($graphNode['albums']);
					for($album_row=0;$album_row<$total_album;$album_row++) {
						if($album_id == $graphNode['albums'][$album_row]['id']) {
							echo "<div class='container'>";
								echo "<form name='form_picture' action='getpictures.php' method='POST'>";
                                   echo "<div class='container'>";
                                    echo "<div class='row'>";
                                        echo "<div class='col-md-4'>";
                                        echo "</div>";
                                        echo "<div class='col-md-4'>";
                                        echo "</div>";
                                        echo "<div class='col-md-3'>";
                                        echo "</div>";
                                        echo "<div class='col-md-1'>";
                                            echo "<Button type='submit' name='logout' style='margin-right:10px;margin-top:10px;birder:1px;border-radius:5%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);' class='btn-success'>Logout</Button>";
                                        echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                       if(isset($_POST['logout'])){
                                            session_destroy();
                                         header('Location:login.php');
                                    }
									echo "<div class='row'>";
										echo "<div class='col-md-1'>";
										echo "</div>";
										echo "<div class='col-md-10'>";
										echo "<a><h2 align='center'>Tools</h2></a><br><br>";	
										echo "</div>";
										echo "<div class='col-md-1'>";
										echo "</div>";
									echo "</div>";
									echo "<div class='row'>";
										echo "<div class='col-md-2'>";
										echo "</div>";
										echo "<div class='col-md-2'>";
											echo "<input type='button' onclick='selectAll()' value='Select All'/>";
										echo "</div>";
										echo "<div class='col-md-2'>";
											echo "<input type='button' onclick='UnSelectAll()' value='Unselect All'/>";
										echo "</div>";
										echo "<div class='col-md-2'>";
												echo "<Button type='submit' name='download_images' class='btn-success'>Download Images</Button>";
										echo "</div>";
										echo "<div class='col-md-2'>";
											echo "<Button type='submit' name='put_on_drive' class='btn-success'>Export To Drive</Button>";
										echo "</div>";
										echo "<div class='col-md-2'>";
										echo "</div>";
									echo "</div>";
									if(isset($_POST['download_images'])) {
										if(isset($_POST['checkbox_picture'])){
									    		$selected_pictures = $_POST['checkbox_picture'];
									    		if(file_exists($elected_pictures)){
												unlink($elected_pictures);
											}
									    		$zipfile = get_pictures($graphNode,$selected_pictures,$album_row);
									   		 echo "<a style='margin-top:10;text-align:center;' href='$zipfile'>".$zipfile."</a>";
										}
										else{
											echo "<br/>";
											echo "<div class='alert alert-danger'>";
												echo "<strong>Alert...! </strong> Please Select any Image..!!";
											echo "</div>";
										}
									}
									if(isset($_POST['put_on_drive'])) {
										if(isset($_POST['checkbox_picture'])){
									    		$selected_pictures = $_POST['checkbox_picture'];
											if(file_exists($selected_pictures)){
												unlink($selected_pictures);
											}
		                                			    		$zipfile = get_pictures($graphNode,$selected_pictures,$album_row);
		                                			   		$_SESSION['session_zipname'] = $selected_pictures;
											header('location: googledrivemsg.php');
										}
										else{
											echo "<br/>";
											echo "<div class='alert alert-danger'>";
												echo "<strong>Alert...! </strong> Please Select any Image..!!";
											echo "</div>";
										}
									}
									echo "<br/>";
									echo "<a><h2 align='center'>" .$graphNode['albums'][$album_row]['name'] ."</h2></a><br><br>";
									//echo "<h2 >" . $graphNode['albums']['$album_row']['name']."</h2><br/><br/>";
									$total_photos = $graphNode['albums'][$album_row]['count'];
									echo "<div class='row' align ='center'>";
										$cnt=0;
										echo "<br/>";
										for($row = 0;$row<$total_photos;$row++) { 
											echo "<div class='col-md-4' style='border:0px;'>";
												echo "<div class='card' style='border-radius:5%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>";
													//$height = $graphNode['albums'][$album_row]['photos'][$row]['images']['height'];
													//$width = $graphNode['albums'][$album_row]['photos'][$row]['images']['width'];
													echo "<img class='card-img-top' style='border-radius:5%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);' src='" . $graphNode['albums'][$album_row]['photos'][$row]['images'][1]['source'] ."' height='200px' width='250px'></img>";	
														echo "<div class='card-body'>";
															echo "Select Image "."<input type='checkbox' name='checkbox_picture[]' value='".$graphNode['albums'][$album_row]['photos'][$row]['images'][1]['source']."'>";
														echo "</div>";
												echo "</div>";
												echo "<br><br><br>";
											echo "</div>";
											$cnt++;
											if($cnt==100){
												break;
											}
										}
									echo "</div>";
								echo "</form>";
							echo "</div>";
						}
					}
			echo "</div>";
		?>
		</form>
	</body>
</html>
