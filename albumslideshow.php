<?php
	session_start();
	ini_set('max_excution_time',300);
    // error_reporting(0);
?>
<html>
    <head>
        <title>
            Get Albums
        </title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <?php
            include("links.php");
        ?>
		<!-- Java script for select and deselect all albums. -->
		<script type="text/javascript">
				//function for selecting all  albums.
				function selectAll(){
					var items=document.getElementsByName('checkbox_album[]');
					for(var i=0; i<items.length; i++){
						if(items[i].type=='checkbox')
							items[i].checked=true;
					}
				}				
				//function for De-selecting all  albums.
				function UnSelectAll(){
					var items=document.getElementsByName('checkbox_album[]');
					for(var i=0; i<items.length; i++){
						if(items[i].type=='checkbox')
							items[i].checked=false;
					}
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
               require_once("config.php");
			   $access_token = $_SESSION['access_token'];
                try {
                		// Returns a `FacebookFacebookResponse` object
                		$response = $fb->get('/me?fields=id,name,albums{id,name,count,photos.limit(100){images}}', $access_token);
                	  } catch(FacebookExceptionsFacebookResponseException $e) {
                		echo 'Graph returned an error: ' . $e->getMessage();
                		exit;
                	  } catch(FacebookExceptionsFacebookSDKException $e) {
                		echo 'Facebook SDK returned an error: ' . $e->getMessage();
                		exit;
                	  }
					  $graphNode = $response->getGraphNode();
                      //$_SESSION['gnode'] = $graphNode;
                      $total_album = count($graphNode['albums']);
						echo "<div class='container'>";
							  echo "<br/><br/>";
							  echo "<div class='row'>";
										echo "<div class='col-md-1'>";
										echo "</div>";
										echo "<div class='col-md-10'>";
										echo "<a><h2 align='center'>Slide show</h2></a><br><br>";	
										echo "</div>";
										echo "<div class='col-md-1'>";
										echo "</div>";
									echo "</div>";
									if(isset($_POST['slideshow'])) {
                                        $album_id = $_POST['slideshow'];
                                        echo $album_id;
                                        // get_pictures($graphNode,$album_id);
                                        // else {
										// 	echo "<br/>";
										// 	echo "<div class='alert alert-danger'>";
										// 		echo "<strong>Alert...! </strong> Please Select any album..!!";
										// 	echo "</div>";
										// }
									}
									echo "<br/>";
							  echo "<h2 class='textdesign'>facebook albums</h2> ";
							  echo "<br/><br/>";
							  echo "<div class='row' align='center'>";
					  			echo "<br/><br/><br/>";
								for($row = 0;$row<$total_album;$row++) { 
									echo "<div class='col-md-4' style='border:1px;'>";
								// 	echo "<a href='getpictures.php?id=$id'>";
									echo "<div class='card' style='birder:1px;border-radius:5%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);'>";										
										$total_photo = $graphNode['albums'][$row]['count'];
										echo "<img class='card-img-top' style='border-radius:5%;box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);' src='images/album.png' height='200px' width='250px'><br/>";
                                        echo "<div class='card-body'>";

                                            $album_id = $graphNode['albums'][$row]['id'];
                                            echo "<h4 class='card-title'>" . $graphNode['albums'][$row]['name']."</h4><br/>";
											echo "<p class='card-text'>Total Photos :" . $graphNode['albums'][$row]['count']."</p>";
								            echo "<a href='slideshow.php?id=$album_id'>Slide Show</Button>";
										echo "</div>";
										echo "</div>";
										echo "<br/>";
            	    	            echo "</div>";
								  }
							 ?> 
							</div>
        	            </div>
		           </div>    	
		     </form>
	    </div>
    </body>
</html>
