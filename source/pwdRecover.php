<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <link rel="stylesheet" type="text/css" href="css/footer.css">

        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        <style>
        body {
            font-family: 'Roboto';
        }
        </style>
    </head>
    <body>
        <div class=container>
            <div class="logo">
                <img src= "images/chatchops.png">
            </div>
                <?php
                    if(isset($_GET['res'])){
                        $errmsg = setErrorMessages();
                        echo '<p class="logerr">'.$errmsg.'</p>';
                    }
                    else{
                        echo '<p class="logok"> . </p>';
                    }
                ?>
            <div>
            </div>
            <div id="logcont">
                <p>Password Recovery</p>
                <form action="include/pwdRecovery.inc.php" class="logform" method="post">
                    <label for="unameormail">Email*</label><br>
                    <?php
                        if(isset($_GET['username'])){
                            echo '<input type="text" name="unameormail" placeholder="enter your email" value ="'.$_GET['username'].'" size="30" class="flog">';
                        }
                        else{
                            echo '<input type="text" name="unameormail" placeholder="enter your email" size="30" class="flog">';
                        }
                    ?>
                    <br>
                    <button type="submit" name="pwd-rec-submit" class="logbutn">Recover</button>
                </form>
                <form method="post" action="login.php">
                    <input type="hidden" name="usernameotp" value="">
                    <button type="submit" name="forgotpwd-submit" class="link-button">
                        log to your account ?
                    </button>  
                </form>
                <br>
                <form method="post" action="registration.php">
                    <button type="submit" name="forgotpwd-submit" class="link-button">
                        Create Account
                    </button>  
                </form>
                <br>
            </div>
        </div>
    </body>
</html>

<?php
    function setErrorMessages(){
        if(isset($_GET['res'])){
            if($_GET['res'] == "empty"){
                return "Enter your email";
            }
            else if($_GET['res'] == "notava"){
                return "Wrong Email";
            }
            else if($_GET['res'] == "deleted"){
                return "Deleted Account";
            }
        }
    }
?>