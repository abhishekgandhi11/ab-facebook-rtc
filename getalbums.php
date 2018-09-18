<?php
	session_start();
	ob_start();
	ini_set('max_excution_time',10000);
	set_time_limit(10000);
 	error_reporting(0);
	$ab=0;
	#delete directory after zip creation.
	function delete_directory($user_name) {
		$dir = 'download/'. $user_name;
		chmod($dir, 0777);
		$di = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
		$ri = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
		foreach ( $ri as $file ) 
		{
			$file->isDir() ?  rmdir($file) : unlink($file);
		}
		rmdir($dir);
	}
	#create zip file.   
	function folder_zip($main_folder)
	{
	
    	$rootPath = realpath($main_folder);
    	
    	$zip = new ZipArchive();
        $zip->open($rootPath.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $name => $file)
        {
            # Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                # Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
        $zip;
    return $rootPath;
    }
	#get pictures from albums.
	function get_pictures($graphNode,$NameNLinks) {
		$graphnode = $graphNode;
		#user name and id for creation main user directory on server
		$user_name = $graphnode['name'];
		#create path for download pictures on server.
		$path1 = 'download/' . $user_name . '/' . $NameNLinks[0];
		mkdir($path1, 0777, true);
			$index=0;
			$x=array();
			$urls = explode(' ', $NameNLinks[1]);
			foreach($urls as $url)
			   {
					$download_file = file_get_contents($url);
				if($download_file==NULL){
				}
				else{
					file_put_contents($path1 . '/' . $index . '.jpg',$download_file);
					$index++;
					$download_file=NULL;
				}
								   }    	
    }
	#get selected albums.
	function get_album($graphNode,$selected_album,$links) {
		
		$total = count($selected_album);
			for($i=0;$i<$total;$i++) {
				$temp = trim($selected_album[$i]," ");
				$allAlbums = explode(',', $links);
					foreach($allAlbums as $ab)
						{
							
						   $NameNLinks = explode('||', $ab);
						   $album_name = trim($NameNLinks[0]," ");
							if($temp == $album_name){
								#get pictures from albums.
								get_pictures($graphNode,$NameNLinks);
							}
						}
				
			}
		$user_name = $graphNode['name'];
		#album nam for create album directory on server.
		$album_name = $graphNode['albums'][$i]['name'];
		#create path for download pictures on server.
		$path2 = 'download/' . $user_name;
		#create zip file.
		$zip_file_name = folder_zip($path2);
		#remove directory.
		delete_directory($user_name);
		return $zip_file_name;
	}
?>
<html>
    <head>
        <title>
            Get Albums
        </title>
        <?php
            include("links.php");
        ?>
        <link rel="stylesheet" href="css/lightbox.min.css">
		<!-- Java script for select and deselect all albums. -->
		<script type="text/javascript">
				function selectAll(){
					var items=document.getElementsByName('checkbox_album[]');
					for(var i=0; i<items.length; i++){
						if(items[i].type=='checkbox')
							items[i].checked=true;
					}
				}				
				function UnSelectAll(){
					var items=document.getElementsByName('checkbox_album[]');
					for(var i=0; i<items.length; i++){
						if(items[i].type=='checkbox')
							items[i].checked=false;
					}
				}			
				function s() 
	            {
                    document.getElementById("link").href = '2.jpg';
		        }
		</script>
    </head>
    <body>
		<?php 
			#call header
			include("header.php"); 
		?>
		<div>
		<form name="getalbums" method="post">
        <div>
            <?php
                #include config file.
               require_once("config.php");
               #take access token from session.
			   $access_token = $_SESSION['access_token'];
			   $url = "https://graph.facebook.com/v3.1/me?fields=albums%7Bid%2Cname%2Cphotos%7Bimages%7D%7D&access_token=".$access_token;
                try {
                		#Returns a `FacebookFacebookResponse` object
                		$response = $fb->get('/me?fields=id,name,albums{id,name,count,photos.limit(500){images}}', $access_token);
                	  } catch(FacebookExceptionsFacebookResponseException $e) {
                		echo 'Graph returned an error: ' . $e->getMessage();
                		exit;
                	  } catch(FacebookExceptionsFacebookSDKException $e) {
                		echo 'Facebook SDK returned an error: ' . $e->getMessage();
                		exit;
                	  }
					  $graphNode = $response->getGraphNode();
					    $total_album = count($graphNode['albums']);
						echo "<div class='container'>";
							  echo "<br/><br/>";
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
									echo "<div class='row' align='center'>";
										echo "<div class='col-md-2'>";
										echo "</div>";
										echo "<div class='col-md-2'>";
											echo "<input type='button' onclick='selectAll()' value='Select All'/>";
										echo "</div>";
										echo "<div class='col-md-2'>";
											echo "<input type='button' onclick='UnSelectAll()' value='Unselect All'/>";
                                        echo "</div>";
										echo "<div class='col-md-2'>";
												echo "<Button type='submit' name='download_albums' style='birder:1px;border-radius:5%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);' class='btn-success'>Download Albums</Button>";
										echo "</div>";
										echo "<div class='col-md-2'>";
											echo "<Button type='submit' name='put_on_drive' style='birder:1px;border-radius:5%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);' class='btn-success'>Export To Drive</Button>";
										echo "</div>";
										echo "<div class='col-md-2'>";
										echo "</div>";
									echo "</div>";
									if(isset($_POST['download_albums'])) {
										if(isset($_POST['checkbox_album'])){
											$selected_album = $_POST['checkbox_album'];
											$user_name = "download/" . $graphNode['name'].".zip";
											if(file_exists($user_name)){
												unlink($user_name);
											}
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
											echo "<br/><br/><br/>";			
											 get_album($graphNode,$selected_album,$links);
											$zipfile = $zipfile.".zip";
											echo "<a style='margin-top:10;text-align:center;' href='$user_name' download>".$user_name."</a>";
										}
										else {
											echo "<br/>";
											echo "<div class='alert alert-danger'>";
												echo "<strong>Alert...! </strong> Please Select any album..!!";
											echo "</div>";
										}
									}
								if(isset($_POST['put_on_drive'])) {
										if(isset($_POST['checkbox_album'])){
											$selected_album = $_POST['checkbox_album'];
											$user_name = $graphNode['name'].".zip";
											if(file_exists($user_name)){
												unlink($user_name);
											}
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
											$zipfile = get_album($graphNode,$selected_album,$links);

										
					                        $_SESSION['session_zipname'] = $user_name;
											header('location: googledrivemsg.php');
											
										
										}
										else {
											echo "<br/>";
											echo "<div class='alert alert-danger'>";
												echo "<strong>Alert...! </strong> Please Select any album..!!";
											echo "</div>";
										}
									}
									echo "<br/>";
							  echo "<h2 class='textdesign'>facebook albums</h2> ";
							  echo "<br/><br/>";
							  echo "<div class='row' align='center'>";
					  			echo "<br/><br/><br/>";
								for($row = 0;$row<$total_album;$row++) { 
									echo "<div class='col-md-4' style='border:1px;'>";
									$id = $graphNode['albums'][$row]['id'];
									
									echo "<div class='card' style='birder:1px;border-radius:5%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>";		
										$total_photo = $graphNode['albums'][$row]['count'];
										echo "<img class='card-img-top' src='". $graphNode['albums'][$row]['photos'][0]['images'][1]['source']. "' height='200px' width='250px' style='border-radius:5%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'><br/>";
										echo "<div class='card-body'>";
											echo "<a href='getpictures.php?id=$id'>";
												echo "<h4 class='card-title'>" . $graphNode['albums'][$row]['name']."</h4><br/>";
											echo "</a>";
											echo "<p class='card-text'>Total Photos :" . $graphNode['albums'][$row]['count']."</p>";
											echo "<p class='card-text'>Select album "."<input type='checkbox' name='checkbox_album[]' value='".$graphNode['albums'][$row]['name']."'></p>";
																					echo "</div>";
											echo "<section>";
											    echo "<div>";
											    for($row1=0;$row1<$graphNode['albums'][$row]['count'];$row1++){
											       if($row1==0){
											            echo "<a class='example-image-link' href='". $graphNode['albums'][$row]['photos'][$row1]['images'][1]['source']. "' data-lightbox='example-set' data-max-width='600' data-max-height='600' >Slideshow</a>";
											       }
											       else{
											            echo "<a class='example-image-link' href='". $graphNode['albums'][$row]['photos'][$row1]['images'][1]['source']. "' data-lightbox='example-set' data-max-width='600' data-max-height='600' ></a>";   
											       }
											    }
											        
											    echo "</div>";
											echo "</section>";
										echo "<br>";
									echo "</div>";
		    						echo "<br/>";
        	                echo "</div>";
	    				    }
    				    ?> 
			          </div>
                    </div>
                </div> 
                  <script src="js/lightbox-plus-jquery.min.js"></script>
            </div>
		    </form>
	    </div>
    </body>
</html>
