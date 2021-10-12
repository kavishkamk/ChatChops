<?php
    session_start();

    // sesssion checking
    if(!isset($_SESSION['userid'])){
        header("Location:../login.php?logout=logoutok"); // no session
        exit();
    }
    else{
        require_once "../phpClasses/SessionHandle.class.php";
        $sessObj = new SessionHandle();
        $sessRes = $sessObj->checkSession($_SESSION['sessionId'], $_SESSION['userid']); // invalid session
        unset($sessObj);
        if($sessRes != "1"){
            header("Location:../login.php?logout=logoutok"); // no session
            exit();
        }
    }

    if(!isset($_GET['uid'])){
        header("Location:profile.php"); // no session
            exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logging</title>
    <link rel="stylesheet" type="text/css" href="../css/otpstyle.css">

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
            ?>
        </div>
        <div id="logcont">
            <p>Account Delete Verification</p>
            <!-- form for check reserved OTP code -->
            <form action="../include/DeleteAccOk.inc.php" class="logform" method="post">
                <input type="hidden" name="uid" value="<?php echo $_GET['uid']; ?>" required>
                <label for="verification">Verification Code</label><br>
                <input type="text" name="verification" placeholder="enter verification" size="30" class="flog">
                <br>
                <button type="submit" name="delete-submit" class="logbutn">Submit</button>
            </form>
            <!-- to resend OTP code -->
            <form method="post" action="profile.php">
                <button type="submit" name="back-submit" class="link-button">
                    Back
                </button>  
            </form>
            <br>
        </div>
    </div>
    
</body>
</html>