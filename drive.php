<html>
    <head>
        <title>
            Google Drive Upload
        </title>
        <?php
            include("links.php");
        ?>
    </head>
    <body>
        <?php
            include("header.php");
        ?>
        <div class="container" align="center">
            <h2 style="margin-top:150px;font-size:35px">Albums are Uploaded on Your Google Drive</h2>
            <form name="f1" method="post">
                    <br><br><br>
                    <button type="submit" formaction="getalbums.php" class="btn btn-primary btn-lg" style="height:75px;width:350px;">Album Page</button> 
                </form>
        </div>
    </body>
</html>