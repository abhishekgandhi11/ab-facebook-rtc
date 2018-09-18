<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Log-in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include("links.php"); ?>
</head>
<body >
    <div class="loginback">
    <div class="container">
         <h1 style="color:white;margin-top:10px;font-size:40px;">Facebook Album Manager</h1> 
         <hr>
         <hr>
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <form name="f1" method="post">
                    <br><br><br>
                    <button type="submit" formaction="login.php" class="btn btn-primary btn-lg" style="height:100px;width:400px;">Log-in Page</button> 
                </form>
            </div>
            <div class="col-md-4">
            </div>
            <br><br><br>
            
        </div>
        <br><br><br>
        <a href="#"><h3 style="color:white;margin-top:15px;font-size:20px;">Developed By: Abhishek Gandhi</h3></a>
        <br>
        <a href="#"><h3 style="color:white;margin-top:15px;font-size:20px;color:blue;">Email id: abhishekgandhi63@gmail.com</h3></a>
        </div>
    </div>
</body>
</html>
