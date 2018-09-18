<?php
	session_start();
	ini_set('max_excution_time',300);
//error_reporting(0);
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
		<script>
            var Index = 0;
            carousel();

            function carousel() {
               
                var i;
                var x = document.getElementsByClassName("mySlides");
                for (i = 0; i < x.length; i++)
                {
                    x[i].style.display = "none";  
                }
                Index++;
                if (Index > x.length) {Index = 1}    
                x[Index-1].style.display = "block";  
                setTimeout(carousel, 2000); // Change image every 2 seconds
            }
</script>
    </head>
    <body onload="carousel();">
		<?php 
			#call header
			include("header.php"); 
		?>
            <?php
               require_once("config.php");
                $id = $_GET['id'];
                $access_token = $_SESSION['access_token'];
                try {
                    // Returns a `FacebookFacebookResponse` object
                    $response = $fb->get('/me?fields=id,name,albums{id,name,count,link,photos.limit(100){images}}', $access_token);
                  } catch(FacebookExceptionsFacebookResponseException $e) {
                    echo 'Graph returned an error: ' . $e->getMessage();
                    exit;
                  } catch(FacebookExceptionsFacebookSDKException $e) {
                    echo 'Facebook SDK returned an error: ' . $e->getMessage();
                    exit;
                  }
                  $graphNode = $response->getGraphNode();
               echo "<form name='form_logout' method='post'>";
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
            echo "</form>";
                $total_album = count($graphNode['albums']);
					for($album_row=0;$album_row<$total_album;$album_row++) {
						if($id == $graphNode['albums'][$album_row]['id']) {
                            $total_photos = $graphNode['albums'][$album_row]['count'];
                            // $picture = $graphNode['albums'][$album_row];
                            // $_SESSION['picture'] = $picture;
                            
                            if(isset($_POST['logout'])){
                                session_destroy();
                                echo "<script>";
                                    // $path='login.php';
                                    // window.location.$path;
                                echo "</script>";
                            }
                            echo "<h2 class='w3-center'>Automatic Slideshow</h2>";
                            $cnt=0;
                            for($row = 0;$row<$total_photos;$row++) 
                            { 
                                if($total_photos>=99){
                                    $name=$graphNode['albums'][$album_row]['photos'][$row]['images'][1]['source'];
                                    echo "<div class='w3-content w3-section' style='max-width:800px;max-height:450px;'>";
                                           //echo $name;
                                          echo "<img src='" . $name  ."' class='mySlides'  style='width:100%;height:100%'></img>";	
                                    echo "</div>";
                                    $cnt++;
                                    if($cnt==99){
                                        break;
                                    }
                                }
                                else{
                                    $name=$graphNode['albums'][$album_row]['photos'][$row]['images'][1]['source'];
                                    echo "<div class='w3-content w3-section' style='max-width:800px;max-height:450px;'>";
                                           //echo $name;
                                          echo "<img src='" . $name  ."' class='mySlides'  style='width:100%;height:100%'></img>";	
                                    echo "</div>";
                                }
                             //echo "<script type='text/javascript'>download('$total_photos');<script>"
                            }
                        }
					}
                ?>
    </body>
</html>