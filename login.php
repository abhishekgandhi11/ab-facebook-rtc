<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include("links.php"); ?>
</head>
<body style="background-color:#a3c9ff;">
<?php
//  include("header.php");
?>
    <div>
    <div align="center" class="container" style="margin-top:50px;" align="centers">
        <br><br><br>
         <h1 style="color:black;margin-top:10px;font-size:50px;">Facebook Album Manager</h1> 
         
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
            <form name="f1">
                <br><br>    
                <br>
                <br>
                    <br><br><br>
                    <?php
                        require_once("config.php");
                        # permissions
                        $permissions = ['email','user_photos']; 
                        #Redirect to this url After user authentication
                        $redirecturl = 'https://abhishekrtchallenge.herokuapp.com/desk.php'; 
                        $loginUrl = $helper->getLoginUrl($redirecturl , $permissions);
                        // header('location:htmlspecialchars($loginUrl)')
                        echo "<a href='" . htmlspecialchars($loginUrl) . "'><h3 style='color: black;'>Log in with Facebook!</h3></a>";
                    ?>
                </form>
            </div>
            <div class="col-md-4">
            </div>
            <br><br><br>
        </div>
        <br><br><br>
        </div>
    </div>
</body>
</html>
