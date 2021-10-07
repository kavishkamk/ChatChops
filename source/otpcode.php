<!-- This file is for get OTP code for email verification -->
<!-- To access this page it is riquired a user name as a paramiter of url -->
<?php
    // check relevent conditions to enter the page
    // if this access come after registration, to access this page registraion should be error free
    if(isset($_GET['otpsend'])){
        if($_GET['otpsend'] != "sendok"){
            header("Location:registration.php");
            exit();
        }
    }
    // if thid access come from after checking enterd verification code it shoud be reselt of
    // wrong verification code or empty field
    if(isset($_GET['otpstatus'])){
        if(!($_GET['otpstatus'] == "WrongOtp" || $_GET['otpstatus'] == "emptyfield")){
            header("Location:login.php");
            exit();
        }
    }

    if(isset($_GET['logstat'])){
        if(isset($_GET['logstat']) != "notactived"){
            header("Location:login.php");
            exit();
        }
    }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging</title>
    <link rel="stylesheet" type="text/css" href="css/otpstyle.css">
</head>
<body>
    <div class=container>
        <div id="logo">
            <img src= "images/chatchops.png">
        </div>
        <div>
            <?php
                // ceheck error status from url and set error messages
                if(isset($_GET['otpstatus'])){
                    if($_GET['otpstatus'] == "WrongOtp"){
                        echo '<p class="otperr">Invalid Verification Code..</p>';
                    }
                    else if($_GET['otpstatus'] == "emptyfield"){
                        echo '<p class="otperr">Please Enter Code</p>';
                    }
                    else{
                        echo '<p class="logok"></p>';
                    }
                }
                else if(isset($_GET['reotpstatus'])){
                    if($_GET['reotpstatus'] == "ok"){
                        echo '<p class="otpok">Resended verification Code..</p>';
                    }
                    else if($_GET['reotpstatus'] == "sqlerror"){
                        echo '<p class="otperr">Please try again..</p>';
                    }
                    else if($_GET['reotpstatus'] == "otpsenderr"){
                        echo '<p class="otperr">Please try again..</p>';
                    }
                }
                else{
                    echo '<p class="logok"></p>';
                }
            ?>
        </div>
        <div id="logcont">
            <p>Verification</p>
            <!-- form for check reserved OTP code -->
            <form action="include/OtpOthentication.inc.php" class="logform" method="post">
                <input type="hidden" name="username" value="<?php echo $_GET['username']; ?>" required>
                <label for="verification">Verification Code</label><br>
                <input type="text" name="verification" placeholder="enter verification" size="30" class="flog">
                <br>
                <p class="otpmsg">OTP code send to your E-mail.</p>
                <p class="otpmsg"> Please check and enter..</p>
                <button type="submit" name="otp-submit" class="logbutn">Submit</button>
            </form>
            <!-- to resend OTP code -->
            <form method="post" action="include/OtpResend.inc.php">
                <input type="hidden" name="usernameotp" value="<?php echo $_GET['username']; ?>" required>
                <button type="submit" name="reotp-submit" class="link-button">
                    resend OTP code..
                </button>  
            </form>
            <br>
        </div>
    </div>
    
</body>
</html>