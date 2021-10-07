<?php
    // for admin log out
    session_start();
    require_once "../phpClasses/AdminOnlineOffline.class.php";
    $offlineObj = new AdminOnlineOffline();
    $offlineObj->setAdminOffline($_SESSION['adminid']);
    unset($offlineObj);

    session_unset();
    session_destroy();

    header("Location:../chatchop-org/adminlogin.php?adminlogstat=logoutok");