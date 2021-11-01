<?php 
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
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Reports</title>
        <link rel="stylesheet" type="text/css" href="../css/reportTable.css">
    </head>
    <body>
        <div class="container">
            <nav class = "header-bar">
                <h1>
                    <label for="">
                        <span class="las la-bars"></span>
                    </label>
                    Report for number of public Group Chat Messages In given Month
                </h1>
            </nav>
            <main class="report-main">
                <div class="re-table1">
                <?php
                    if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                        require_once "../reportPubGroupPhpClass/GetPubGrpTableData.class.php";
                        $priobj = new GetPubGrpTableData();
                        $arr = explode("-",$_GET['reporttime']);
                        $datas = $priobj->getMonthPubGrpMsg($arr[1], $arr[0]);
                        unset($priobj);
                        $d=cal_days_in_month(CAL_GREGORIAN,$arr[1],$arr[0]);
                    }
                ?>
                <table class="d-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Number of Messages</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                                $val = "d";
                                if($datas != NULL){
                                    for($i= 1; $i <= 16; $i++){
                                        echo '<tr>
                                                <td>'.$i.'</td>
                                                <td>'.$datas[$val.$i].'</td>
                                            </tr>';
                                    }
                                }
                            }
                        ?>
                    </tbody>
                </table>
                </div>
                <div class="re-table2">
                    <table class="d-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Number of Messages</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                                    $val = "d";
                                    if($datas != NULL){
                                        for($i= 17; $i <= $d; $i++){
                                            echo '<tr>
                                                    <td>'.$i.'</td>
                                                    <td>'.$datas[$val.$i].'</td>
                                                </tr>';
                                        }
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </main>
            <div id="sidebar">
                <div class="form-div">
                    <form action="pubGrpChatMsgRecTableMonth.php" method="get">
                        <label for="reporttime">Select Month</label><br><br>
                        <input type="month" name="reporttime"><br><br>
                        <button type="submit" name="month-submit" >Genarate</button>
                    </form>
                </div>
                <div class="chart-dis">
                    <span>
                    <?php
                        if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                            echo '<p class="t-head">Number of Public Group chat messages in </p>';
                            echo '<p class="t-head">'.$arr[0].' - '.$arr[1].'</p>';
                        }
                        else{
                            echo '<p class="t-head">No record</p>';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </body>
</html>