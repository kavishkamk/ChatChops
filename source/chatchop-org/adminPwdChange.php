<?php 
    // this is for show data sumary
    session_start();

    if(!isset($_SESSION['adminid'])){
         header("Location:../chatchop-org/adminlogin.php?adminlogstat=logoutok"); // no session
         exit();
    }
    else{
        require_once "../phpClasses/AdminSessionHandle.class.php";
        $sessObj = new AdminSessionHandle();
        $sessRes = $sessObj->checkSession($_SESSION['sessionId'], $_SESSION['adminid']); // invalid session
        unset($sessObj);
        if($sessRes != "1"){
            header("Location:../chatchop-org/adminlogin.php?adminlogstat=logoutok"); // no session
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
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
                if(isset($_GET['pwdtatus'])){
                    if($_GET['pwdtatus'] == "wrongpwd"){
                        echo '<p class="otperr">Wrong Password</p>';
                    }
                    else if($_GET['pwdtatus'] == "emptyfield"){
                        echo '<p class="otperr">Please Enter Passwords</p>';
                    }
                    else{
                        echo '<p class="logok"></p>';
                    }
                }
            ?>
        </div>
        <div id="logcont">
            <p>Change Password</p>
            <!-- form for get passwords -->
            <form action="../include/adminPwdCompare.inc.php" class="logform" method="post">
                <input type="hidden" name="userid" value="<?php echo $_SESSION['adminid']; ?>" required>
                <label for="upwd">Password</label><br>
                <input type="password" name="upwd" placeholder="enter new Password" size="30" class="flog"><br>
                <label for="ucpwd">Confirm Password</label><br>
                <input type="password" name="ucpwd" placeholder="Confirm Password" size="30" class="flog">
                <br>
                <button type="submit" name="pwd-submit" class="logbutn">Submit</button>
            </form>
            <!-- for back -->
            <form method="post" action="adminProfileChange.php">
                <button type="submit" name="goback" class="link-button">
                    BACK
                </button>  
            </form>
            <br>
        </div>
    </div>

</body>
</html>