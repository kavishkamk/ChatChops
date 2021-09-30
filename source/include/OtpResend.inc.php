<?php
    if(isset($_POST['reotp-submit'])){
        $username = $_POST['usernameotp'];

        if(empty($username)){
            header("Location:../login.php?reotpstatus=emptyusername");
            exit();
        }
        else{
            header("Location:../otpcode.php?reotpstatus=ok&username=$username");
            exit();
        }
    }
    else{
        header("Location:../otpcode.php");
        exit();
    }