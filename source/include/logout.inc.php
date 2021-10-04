<?php
    // for log out
    session_start();
    require_once "../phpClasses/OnlineOffline.class.php";
    $offlineObj = new OnlineOffline();
    $offlineObj->setUserOffline($_SESSION['userid'], $_SESSION['onlineRecordid']);
    unset($offlineObj);

    session_unset();
    session_destroy();

    header("Location:../login.php?logout=logoutok");