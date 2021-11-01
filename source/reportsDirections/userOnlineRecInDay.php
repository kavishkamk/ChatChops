<?php
    session_start();

    // session validation
    if(!isset($_SESSION['adminid'])){
        header("Location:../chatchop-org/adminlogin.php?adminlogstat=logoutok"); // no session
        echo "<script>window.close();</script>";
        exit();
    }
    else{
        require_once "../phpClasses/AdminSessionHandle.class.php";
        $sessObj = new AdminSessionHandle();
        $sessRes = $sessObj->checkSession($_SESSION['sessionId'], $_SESSION['adminid']); // invalid session
        unset($sessObj);
        if($sessRes != "1"){
            header("Location:../chatchop-org/adminlogin.php?adminlogstat=logoutok"); // no session
            echo "<script>window.close();</script>";
            exit();
        }
    }

    // check the valid access (to access this page this paramiters required)
    if(!isset($_GET['report-req-submit'])){
        header("Location:../chatchop-dashboard/chatreport.php"); // no access
        echo "<script>window.close();</script>";
        exit();
    }
    
    $reportType = $_GET['reType'];
    $grahtype = $_GET['Type'];
    if($reportType == 1){
        $day = date("Y-n-d");
    }
    else if($reportType == 4){
        $day = $_GET['timeforreport'];
    }

    if(empty($reportType) || empty($grahtype)){
        echo "<script>window.close();</script>"; // if empty inputs close tab
    }
    else if($grahtype == "Table"){
        // genarate table
        header("Location:../reportTables/userOnlineRecTableDay.php?reporttime=".$day."");
        exit();
    }
    else if($grahtype == "Graph"){
        // genarate graph using R
        exec('C:\\"Program Files"\\R\\R-4.0.3\\bin\\Rscript.exe C:\\xampp\\htdocs\\chatchops\\R_privatChat\\R_DayOnline.R ' . $day);
        header("Location:../RPlots/userOnlineTimeInGivenDate.html"); // show plot
        exit();
    }
    else{
        echo "<script>window.close();</script>";
    }
?>