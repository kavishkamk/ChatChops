<?php

require_once 'gsign-in-config.php';

$email = $_SESSION['email'];

require "../phpClasses/LoginHandle.class.php";
$logObj = new LoginHandle();
$check = $logObj->googleLoginCheck($email);

if($check == "0"){
    header("Location:../login.php?logstat=noacc&email=$email");
    unset($logObj);
    exit();
}
else if($check == "1"){
    header("Location:../insideUI/chatChops.php");
    unset($logObj);
    exit();
}
else if($check == "3"){
    header("Location:../login.php?logstat=sqlerror&email=$email");
    unset($logObj);
    exit();
}
else if($check[0] == "4"){
    $uname = substr($check, 1, strlen($check) - 1);
    header("Location:../insideUI/otpcode.php?logstat=notactived&username=$email");
    unset($logObj);
    exit();
}
else if($check == "2"){
    header("Location:../login.php?logstat=deletedacc&username=$email");
    unset($logObj);
    exit();
}
else if($check == "6"){
    header("Location:../login.php?logstat=activefail&username=$email");
    unset($logObj);
    exit();
}

?>