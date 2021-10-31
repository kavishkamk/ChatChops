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
    $yeMonth = $_GET['timeforreport'];
    
    $year = substr($yeMonth, 0, 4);
    $month = substr($yeMonth, 5);

    if(empty($reportType) || empty($grahtype) || empty($yeMonth)){
        echo "<script>window.close();</script>"; // if empty inputs close tab
    }
    else if($grahtype == "Table"){
        // genarate table
        //header("Location:../reportTables/priChatMsgRecTableMonth.php?reporttime=".$yeMonth."");
        echo "<script>window.close();</script>"; // if empty inputs close tab
        exit();
    }
    else if($grahtype == "Graph"){
        // genarate graph using R
        exec('C:\\"Program Files"\\R\\R-4.0.3\\bin\\Rscript.exe C:\\xampp\\htdocs\\chatchops\\R_privatGroupChat\\R_MonthPriGrpChat.R ' . $year . ' ' . $month);
        header("Location:../RPlots/privateGroupMessageDataInGivenMonth.html"); // show plot
        exit();
    }
    else{
        echo "<script>window.close();</script>";
    }