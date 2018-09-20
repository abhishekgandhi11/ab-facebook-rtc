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
<body >
<?php include("header.php"); ?>
    <div class="">
    <div class="container" style="margin-top:50px;" align="centers">
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
        </div>
    </div>
</body>
</html>
