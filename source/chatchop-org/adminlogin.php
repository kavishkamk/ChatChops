<!-- This is admin login form -->
<?php
    if(session_start()){
        session_unset();
        session_destroy();
    }
?>

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logging</title>
        <link rel="stylesheet" type="text/css" href="../css/login.css">

        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        <style>
        body {
            font-family: 'Roboto';
        }
        </style>
    </head>
    <body>
        <div class=container>
            <div id="logo">
                <img src= "../images/chatchops.png">
            </div>
            <div>
                <?php
                    if(isset($_GET['adminlogstat'])){
                        if($_GET['adminlogstat'] == "logoutok"){
                            echo '<p class="logsuss">You are logged out.!</p>';
                        }
                        else{
                            echo '<p class="logerr">Unauthorized Access</p>';
                        }
                    }
                    else{
                        echo '<p class="logok"> . </p>';
                    }

                ?>
            </div>
            <div id="logcont">
            <p>Log-In</p>
                <form action="../include/AdminLogin.inc.php" class="logform" method="post">
                    <label for="unameormail">User Name or Email*</label><br>
                    <input type="text" name="unameormail" placeholder="enter your email / username" size="30" class="flog">
                    <br>
                    <label for="pwd">Password*</label><br>
                    <input type="password" name="pwd" placeholder="enter your password" size="30" class="flog"><br>
                    <button type="submit" name="log-submit" class="logbutn">Login</button>
                </form>
                <br>
            </div>
        </div>
        <p>Copyright &copy; 2021 ChatChops. Inc. All rights reserved</p>
    </body>
</html>