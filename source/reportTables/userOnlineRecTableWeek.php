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
                    Report for number of Online users In given Week
                </h1>
            </nav>
            <main class="report-main">
                <div class="re-table1">
                <?php
                    if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                        // get week and year from
                        $yeMonth = $_GET['reporttime'];
                        $year = substr($yeMonth, 0, 4);
                        $week_no = substr($yeMonth, 6);
                        $week_start = new DateTime();
                        $week_start->setISODate($year,$week_no);
                        $sd = $week_start->format('Y-n-d');
                        $ed =  date('Y-n-d', strtotime($sd)+(86400*6)); // get next day
                        $arr = explode("-",$sd);
                        require_once "../reportPrivatePhpClass/GetPriTableData.class.php";
                        $priobj = new GetPriTableData();
                        $datas = $priobj->getWeekOnlineUsers($arr[0], $arr[1], $arr[2]);
                        unset($priobj);
                    }
                ?>
                <table class="d-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Number of Users</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
                            if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                                if($datas != NULL){
                                    $val = "d";
                                   for($i= 0; $i < 7; $i++){
                                       $j = $i + $arr[2];
                                        echo '<tr>
                                               <td>'.$days[$i].'</td>
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
                    <form action="userOnlineRecTableWeek.php" method="get">
                        <label for="reporttime">Select Week</label><br><br>
                        <input type="week" name="reporttime"><br><br>
                        <button type="submit" name="month-submit" >Genarate</button>
                    </form>
                </div>
                <div class="chart-dis">
                    <span>
                    <?php
                        if(isset($_GET['reporttime']) && !empty($_GET['reporttime'])){
                            echo '<p class="t-head">Number of online users in </p>';
                            echo '<p class="t-head">'.$sd.' - '.$ed.'</p>';
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