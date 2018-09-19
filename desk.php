<?php
    session_start();
    // error_reporting(0);
?>
<html>
    <head>
        <title>
            Get Albums
        </title>
        <?php
            include("links.php");
        ?>
    </head>
    <body>
        <?php include("header.php"); ?>
		<div>
		<form name="getalbums" method="post">
            <div class="container">
                <div class="jumbotron" style="margin-top:100px;">
                    <div align="center" style="margin-top:75px;">
                    <?php
                        require_once("fb-callback.php");
                        header('location:getalbums.php');
                        echo "<a href='getalbums.php'><h2>Go to Album page</a>";
                    ?>
    		        </div>         
                    <br><br>
                </div>
            </div>
            

		</form>
	</div>
    </body>
</html>
