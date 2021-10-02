<!-- This is login interface -->
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Logging</title>
        <link rel="stylesheet" type="text/css" href="css/login.css">
    </head>
    <body>
        <div class=container>
            <div id="logo">
                <img src= "images/chatchops.png">
            </div>
            <div>
                <?php
                    if(isset($_GET['logstat'])){
                        $errmsg = setErrorMessages();
                        echo '<p class="logerr">'.$errmsg.'</p>';
                    }
                    else if(isset($_GET['otpstatus'])){
                        if($_GET['otpstatus'] == "otpverified"){
                            echo '<p class="logsuss">Your Account Activated</p>';
                        }
                        else{
                            $errmsg = setOtpErrorMessgess();
                            echo '<p class="logerr">'.$errmsg.'</p>';
                        }
                    }
                    else if(isset($_GET['reotpstatus'])){
                        $errmsg = setOtpResendErrorMsg();
                        echo '<p class="logerr">'.$errmsg.'</p>';
                    }
                    else if(isset($_GET['signerror'])){
                        $errmsg = setRegistrationErrorMsg();
                        echo '<p class="logerr">'.$errmsg.'</p>';
                    }
                    else if(isset($_GET['logout'])){
                        if($_GET['logout'] == "logoutok"){
                            echo '<p class="logsuss">You are logged out.!</p>';
                        }
                    }
                    else{
                        echo '<p class="logok"> . </p>';
                    }
                ?>
            </div>
            <div id="logcont">
                <p>Log-In</p>
                <form action="include/login.inc.php" class="logform" method="post">
                    <label for="unameormail">User Name or Email*</label><br>
                    <?php
                        if(isset($_GET['username'])){
                            echo '<input type="text" name="unameormail" placeholder="enter your email / username" value ="'.$_GET['username'].'" size="30" class="flog">';
                        }
                        else{
                            echo '<input type="text" name="unameormail" placeholder="enter your email / username" size="30" class="flog">';
                        }
                    ?>
                    <br>
                    <label for="pwd">Password*</label><br>
                    <input type="password" name="pwd" placeholder="enter your password" size="30" class="flog"><br>
                    <button type="submit" name="log-submit" class="logbutn">Login</button>
                </form>
                <form method="post" action="">
                    <input type="hidden" name="usernameotp" value="">
                    <button type="submit" name="forgotpwd-submit" class="link-button">
                        Forgot your password?
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
        <p>Copyright &copy; 2021 ChatChops. Inc. All rights reserved</p>
    </body>
</html>

<?php
    function setErrorMessages(){
        if(isset($_GET['logstat'])){
            if($_GET['logstat'] == "emptyfield"){
                return "Give uasename and password";
            }
            else if($_GET['logstat'] == "noacc"){
                return "Not Avilable account";
            }
            else if($_GET['logstat'] == "wrongpwd"){
                return "Wrong password or username";
            }
            else if($_GET['logstat'] == "deletedacc"){
                return "Deleted Account";
            }
            else if($_GET['logstat'] == "sqlerror"){
                return "Somting Wrang. Try again";
            }
            else if($_GET['logstat'] == "activefail"){
                return "Not Actived Account. Retry to Log";
            }
        }
    }

    function setOtpErrorMessgess(){
        if(isset($_GET['otpstatus'])){
            if($_GET['otpstatus'] == "alradyactived"){
                return "Something Wrong. Try Again";
            }
            else if($_GET['otpstatus'] == "usernotfound"){
                return "Not availabal Account";
            }
            else if($_GET['otpstatus'] == "sqlError" || $_GET['otpstatus'] == "sqlError1"){
                return "Something Wrong. Try Again.";
            }
        }
    }

    function setOtpResendErrorMsg(){
        if(isset($_GET['reotpstatus'])){
            $errtyp = $_GET['reotpstatus'];
            if($errtyp == "emptyusername" || $errtyp == "nouser" || $errtyp == "alradyactive" || $errtyp == "emailnotfound"){
                return "Something Wrong. Try Again.";
            }
        }
    }

    function setRegistrationErrorMsg(){
        if(isset($_GET['signerror'])){
            $errtyp = $_GET['signerror'];
            if($errtyp == "sqlerror" || $errtyp == "notaregistermail" || $errtyp == "otpsenderror"){
                return "Something Wrong. Try Again.";
            }
        }
    }
?>