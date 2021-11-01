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

    if(!isset($_GET['report-req-submit'])){
        header("Location:../chatchop-dashboard/chatreport.php"); // no access
        exit();
    }

    $reportType = $_GET['reType'];
    $grahtype = $_GET['Type'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" type="text/css" href="../css/reports.css">
        <title>Report</title>
    </head>
    <body>
        <main class="container">
            <div style="grid-column:1 / 4; grid-row: 1 / 2; text-align: right;">
                <nav class = "header-bar">
                    <h1>
                        <label for="">
                            <span class="las la-bars"></span>
                        </label>
                        ChatChops User Summary
                    </h1>
                </nav>
            </div>
            <div class="user-div">
                <table>
                    <thead>
                        <caption>Private Users</caption>
                        <tr>
                            <th></th>
                            <th>Record Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require_once '../reportPrivatePhpClass/GetDataToReport.class.php';
                            $dataObj = new GetDataToReport();
                            $activeAcc = $dataObj->getNumberOfusers(1);
                            echo '<tr>
                                    <td>1</td>
                                    <td>Number of Active Accounts</td>
                                    <td>'.$activeAcc.'</td>
                                </tr>';
                            $onlineusers = $dataObj->getNumOfOnOfflineUsers(1);
                            echo '<tr>
                                    <td>2</td>
                                    <td>Number of Online Users</td>
                                    <td>'.$onlineusers.'</td>
                                </tr>';
                            echo '<tr>
                                    <td>3</td>
                                    <td>Number of Offline Users</td>
                                    <td>'.$activeAcc - $onlineusers.'</td>
                                </tr>';
                            $allAcc = $dataObj->numOfAccount();
                            echo '<tr>
                                    <td>4</td>
                                    <td>Number of All created Accounts</td>
                                    <td>'.$allAcc.'</td>
                                </tr>';
                            $deletedAcc = $dataObj->getNumOfDeletedAcc();
                            echo '<tr>
                                    <td>5</td>
                                    <td>Number of Deleted Accounts</td>
                                    <td>'.$deletedAcc.'</td>
                                </tr>';
                            echo '<tr>
                                <td>6</td>
                                <td>Number of Not Activated Accounts</td>
                                <td>'.$allAcc - ($activeAcc + $deletedAcc).'</td>
                            </tr>';
                            $todyCreate = $dataObj->getNumCreatedAccGivenDate(date("Y-n-d"));
                            echo '<tr>
                                <td>7</td>
                                <td>Number of Created Accounts (Today)</td>
                                <td>'.$todyCreate.'</td>
                            </tr>';
                            $todyactive = $dataObj->activeAccNumRegisterInGivenDay(date("Y-n-d"));
                            echo '<tr>
                                <td>8</td>
                                <td>Number of activated Accounts (Today)</td>
                                <td>'.$todyactive.'</td>
                            </tr>';
                            $todyDelete = $dataObj->deletedAccGivenDate(date("Y-n-d"), 1);
                            echo '<tr>
                                <td>9</td>
                                <td>Number of Deleted Accounts (Today)</td>
                                <td>'.$todyDelete.'</td>
                            </tr>';
                            echo '<tr>
                                <td>10</td>
                                <td>Number of Not Activated Accounts (Created Today)</td>
                                <td>'.$todyCreate - ($todyactive + $todyDelete).'</td>
                            </tr>';
                            unset($dataObj);
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="private-group-div">
                <table>
                    <thead>
                        <caption>Private Groups</caption>
                        <tr>
                            <th></th>
                            <th>Record Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require_once '../reportPriGroupPhpClass/GetDataToPriGroup.class.php';
                            $pgdataObj = new GetDataToPriGroupReport();
                            $activegrps = $pgdataObj->getNumberOfActivePriGroup(1);
                            echo '<tr>
                                    <td>1</td>
                                    <td>Number of Active Groups</td>
                                    <td>'.$activegrps.'</td>
                                </tr>';
                            $allPriGroup = $pgdataObj->getNumOfAllCreatedGroups();
                            echo '<tr>
                                    <td>2</td>
                                    <td>Number of Created All Groups</td>
                                    <td>'.$allPriGroup.'</td>
                                </tr>';
                            echo '<tr>
                                    <td>3</td>
                                    <td>Number of Deactivated Groups</td>
                                    <td>'.$allPriGroup - $activegrps.'</td>
                                </tr>';
                            $todayCreatePriGrp = $pgdataObj->numOfCridDelPriGroupGivenDate(date("Y-n-d"), 1);
                            echo '<tr>
                                    <td>4</td>
                                    <td>Number of Created Groups (Today)</td>
                                    <td>'.$todayCreatePriGrp.'</td>
                                </tr>';
                            $todaydelPriGrp = $pgdataObj->numOfCridDelPriGroupGivenDate(date("Y-n-d"), 0);
                            echo '<tr>
                                    <td>5</td>
                                    <td>Number of Deleted Groups (Today)</td>
                                    <td>'.$todaydelPriGrp.'</td>
                                </tr>';
                            unset($pgdataObj);
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="public-group-div">
                <table>
                    <thead>
                        <caption>Public Groups</caption>
                        <tr>
                            <th></th>
                            <th>Record Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require_once '../reportPubGroupPhpClass/GetDataToPubGroup.class.php';
                            $pubgdataObj = new GetDataToPubGroupReport();
                            $actipubvegrps = $pubgdataObj->getNumberOfActivePubGroup(1);
                            echo '<tr>
                                    <td>1</td>
                                    <td>Number of Active Groups</td>
                                    <td>'.$actipubvegrps.'</td>
                                </tr>';
                            $allPubGroup = $pubgdataObj->getNumOfAllCreatedPubGroups();
                            echo '<tr>
                                    <td>2</td>
                                    <td>Number of Created All Groups</td>
                                    <td>'.$allPubGroup.'</td>
                                </tr>';
                            echo '<tr>
                                    <td>3</td>
                                    <td>Number of Deactivated Groups</td>
                                    <td>'.$actipubvegrps - $allPubGroup.'</td>
                                </tr>';
                            $todayCreatePubGrp = $pubgdataObj->numOfCridDelpubGroupGivenDate(date("Y-n-d"), 1);
                            echo '<tr>
                                    <td>4</td>
                                    <td>Number of Created Groups (Today)</td>
                                    <td>'.$todayCreatePubGrp.'</td>
                                </tr>';
                            $todaydelPubGrp = $pubgdataObj->numOfCridDelpubGroupGivenDate(date("Y-n-d"), 0);
                            echo '<tr>
                                    <td>5</td>
                                    <td>Number of Deleted Groups (Today)</td>
                                    <td>'.$todaydelPubGrp.'</td>
                                </tr>';
                        ?>
                    </tbody>
                </table>
            </div>
       </main>
    </body>
</html>
