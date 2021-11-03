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

    if(empty($reportType) || empty($grahtype) || empty($yeMonth)){
        echo "<script>window.close();</script>"; // if empty inputs close tab
    }
    else if($grahtype == "Table"){
        // genarate table
        header("Location:../reportTables/priGrpChatMsgRecTableWeek.php?reporttime=".$yeMonth."");
        exit();
    }
    else if($grahtype == "Graph"){

        // get week and year from 
        $year = substr($yeMonth, 0, 4);
        $week_no = substr($yeMonth, 6);
        $week_start = new DateTime();
        $week_start->setISODate($year,$week_no);
        $sd = $week_start->format('Y-n-d');
        $ed = date('Y-n-d', strtotime($sd . ' +6 day'));

        //genarate graph using R
        require_once "../R_execute/R_script_execute.class.php";
        $robj = new R_ScriptExecute();
        $scriptPath = 'R_privatGroupChat\\R_WeekPriGrpChat.R ' . $sd . ' ' . $ed;
        echo $scriptPath;
        $robj->rExecutive($scriptPath);
        unset($robj);
        header("Location:../RPlots/privateGrpChatMsgDataInGivenWeek.html"); // show plot
        exit();
    }
    else{
        echo "<script>window.close();</script>";
    }