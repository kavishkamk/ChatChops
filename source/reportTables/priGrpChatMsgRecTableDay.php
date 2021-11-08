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
                    Report for number of Private Group Chat Messages In given Day
                </h1>
            </nav>
            <main class="report-main">
                <div class="re-table1">
                <?php
                    if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                        require_once "../reportPriGroupPhpClass/GetPriGroTableData.class.php";
                        $priobj = new GetPriGrpTableData();
                        $datas = $priobj->getDayPriGrpChatMsg($_GET['reporttime']);
                        unset($priobj);
                    }
                ?>
                <table class="d-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Number of chat Messages</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $days = array('00:00:00','01:00:00','02:00:00','03:00:00','04:00:00','05:00:00','06:00:00','07:00:00',
                            '08:00:00','09:00:00','10:00:00','11:00:00','12:00:00','13:00:00','14:00:00','15:00:00','16:00:00',
                            '17:00:00','18:00:00','19:00:00','20:00:00','21:00:00','22:00:00','23:00:00','24:00:00');
                                if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                                    if($datas != NULL){
                                        $val = "h";
                                       for($i= 0; $i < 12; $i++){
                                           $j = $i + 1;
                                            echo '<tr>
                                                   <td>'.$days[$i].' -'.$days[$i + 1].' </td>
                                                   <td>'.$datas[$val.$j].'</td>
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
                                <th>Time</th>
                                <th>Number of chat Messages</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                                    $val = "h";
                                    if($datas != NULL){
                                        for($i= 12; $i < 24; $i++){
                                            $j = $i + 1;
                                            echo '<tr>
                                                <td>'.$days[$i].' -'.$days[$i + 1].' </td>
                                                <td>'.$datas[$val.$j].'</td>
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
                    <form action="priGrpChatMsgRecTableDay.php" method="get">
                        <label for="reporttime">Select Day</label><br><br>
                        <input type="date" name="reporttime"><br><br>
                        <button type="submit" name="month-submit" >Genarate</button>
                    </form>
                </div>
                <div class="chart-dis">
                    <span>
                    <?php
                        if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                           echo '<p class="t-head">Number of private group chat messages in </p>';
                            echo '<p class="t-head">'.$_GET['reporttime'].'</p>';
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