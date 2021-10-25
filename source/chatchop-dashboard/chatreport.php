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
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title>Document</title>
    <link
    rel="stylesheet"
    href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css"

  />
  </link>
<link rel="stylesheet" href="style.css">



  </head>

  <body>
    <div class="container">
      <nav>
        <div>
          <!-- nav -->
          <img
            src="/chatchop_new/1.jpeg"
            width="60px"
            alt="hansana"
            style="float: right"
          />
          <!-- <p class="a">Vishvi De Silva</p> -->
        </div>

      </nav>

      <!-- setting the main -->
      <main class="report-main">
        <!-- Main -->
        <div>
          <div class="goption system-analize" style="margin-left: 20px;">
            <div>
              <?php
                require_once '../reportPrivatePhpClass/GetDataToReport.class.php';
                $dateObj = new GetDataToReport();
                $number = $dateObj->getNumberOfusers(1);
                unset($dateObj);
                echo '<div> Totol Number of active accounts : '.$number.' <br></div>';
              ?>
            </div>
            <div class="analize-btn">
              <?php
                require_once "../reportClasses/report.class.php";
                $repObj = new RepoerDetails();
                $ltime = $repObj->getLastAnalizeTime();
                unset($repObj);
                echo '<span id="lantime">Last Analize Time : '.$ltime.'</span>';
              ?>
              <input type="button" value="AnalizeRecords" id="t18">
            </div>
          </div>
          <div class = "report-type">
            <div class="report-lable">Usege Summary</div>
            <div class="report-input">
              <input type="button" value="All" id="t17">
              <span></span>
              <span class="" id="analize-status"></span>
            </div>
          </div>
          <div class = "report-type">
            <div class="report-lable">Fisrst Report</div>
            <div class="report-input">
              <input type="button" value="Today" id="t1">
              <input type="button" value="Month" class="report-time" id="t2">
              <input type="button" value="Week" class="report-time" id="t3">
              <input type="button" value="Date" class="report-time" id="t4">
            </div>
          </div>
          <div class = "report-type">
            <div class="report-lable">First Report</div>
            <div class="report-input">
              <input type="button" value="Today" id="t5">
              <input type="button" value="Month" class="report-time" id="t6">
              <input type="button" value="Week" class="report-time" id="t7">
              <input type="button" value="Date" class="report-time" id="t8">
            </div>
          </div>
          <div class = "report-type">
            <div class="report-lable">First Report</div>
            <div class="report-input">
              <input type="button" value="Today" id="t9">
              <input type="button" value="Month" class="report-time" id="t10">
              <input type="button" value="Week" class="report-time" id="t11">
              <input type="button" value="Date" class="report-time" id="t12">
            </div>
          </div>
          <div class = "report-type">
            <div class="report-lable">First Report</div>
            <div class="report-input">
              <input type="button" value="Today" id="t13">
              <input type="button" value="Month" class="report-time" id="t14">
              <input type="button" value="Week" class="report-time" id="t15">
              <input type="button" value="Date" class="report-time" id="t16">
            </div>
          </div>
        </div>
        <!-- this is used to get details for given report type and submit form details -->
        <div class="report-details-area">
          <div class = "report-disply">
            <form id="report-form" action="" method="get" target="_blank">
              <div id="chooes-report"  class="goption">Report Type : </div>
              <input type="hidden" id="reType" name ="reType" value="">
              <div id="settime"  class="goption">
                <label for="timeforreport" id="timelable">Set Time :</label>
                <input type="hidden" id="reporttime" name="timeforreport">
              </div>
              <div class="goption">
                <label for="Type">Report Display Type : </label>
                <select name="Type" id="Type">
                  <option value="Table">Table</option>
                  <option value="Graph">Graph</option>
                </select>
              </div>
              <div>
                <button type="submit" name="report-req-submit" class="button1">Genarate</button>
              </div>
            </form>
          </div>

          <div class="report-explane">
            <div id="report-head"></div>
            <div id="report-discription"></div>
          </div>
        </div>
</main>

      <!-- sidebar -->
      <div id="sidebar">
        <!-- <h1>Dashboard</h1>*/ -->
        <h1>
        <label for="">
      <span class="las la-bars"></span>
       </label>
        Dashboard</h1>
        <ul>
          <li>

            <a href="chatreport.php"
              ><span class="las-la-reports"></span> <span>Report</span></a
            >
            <img src="https://img.icons8.com/external-sbts2018-mixed-sbts2018/30/000000/external-productivity-business-and-finance-sbts2018-mixed-sbts2018.png"/>


          </li>
          <br>

          <li>
            <a href="chatadmin.php"
              ><span class="las-la-admin"></span> <span>Admin</span></a
            >
            <img src="https://img.icons8.com/ios-glyphs/30/000000/admin-settings-male.png"/>

          </li>
          <br>
        </ul>

      </div>
      <!-- setting the footer -->

      <footer>
        <p>ChatChop &copy; 2021</p>
      </footer>
    </div>
  </body>
</html>

<script>
  $("#t1").click(function(){ 
    document.getElementById('chooes-report').innerHTML = "Report Type : 1"; 
    document.getElementById('reporttime').type = "time";
  });
  $("#t2").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 2"; 
    document.getElementById('reporttime').type = "month";
  });
  $("#t3").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 3";
    document.getElementById('reporttime').type = "week";
  });
  $("#t4").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 4"; 
    document.getElementById('reporttime').type = "date";
  });
  $("#t5").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 5";
    document.getElementById('reporttime').type = "time";
  });
  $("#t6").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 6"; 
    document.getElementById('reporttime').type = "month";
  });
  $("#t7").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 7";
    document.getElementById('reporttime').type = "week";
  });
  $("#t8").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 8";
    document.getElementById('reporttime').type = "date";
  });
  $("#t9").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 9"
    document.getElementById('reporttime').type = "time";
  });
  $("#t10").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 10"; 
    document.getElementById('reporttime').type = "month";
  });
  $("#t11").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 11";
    document.getElementById('reporttime').type = "week";
  });
  $("#t12").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 12";
    document.getElementById('reporttime').type = "date";
  });
  $("#t13").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 13";
    document.getElementById('reporttime').type = "time";
  });
  $("#t14").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 14";
    document.getElementById('reporttime').type = "month";
  });
  $("#t15").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 15";
    document.getElementById('reporttime').type = "week";
  });
  $("#t16").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : 16";
    document.getElementById('reporttime').type = "date";
  });
  $("#t17").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Usege Summary";
    document.getElementById('reporttime').type = "hidden";
    document.getElementById('report-form').action = "../reports/userAccReports.php";
    document.getElementById('reType').value = 17;
    document.getElementById('timelable').innerHTML = "Set Time : Overrall";
    document.getElementById('report-head').innerHTML = "Usege Summary";
    document.getElementById('report-discription').innerHTML = "This for get user activity summary.<br><ul><li>Persional Account Summary</li><li>Private Group Summary</li><li>Public Group Summary</li></ul>";
  });
  $("#t18").click(function(){
    setLastAnalizeTime();
});

// set last analize time
function setLastAnalizeTime(){
  setLastAnalize("");
  setAnalizeStartStatus();
  $.ajax({
      method: "POST",
      url: "../reports/setLastReportAnalizeTime.php",
      data: { premsreq: "ok"},
    success:function(result){
      var obj = JSON.parse(result);
      setLastAnalize(obj);
      setAnalizeEndStatus();
    }
  });
}

// set last analize time in interface label
function setLastAnalize(obj){
  document.getElementById('lantime').innerHTML = "Last Analize Time : " + obj;
}

// set analize lable in interface
function setAnalizeStartStatus(){
  document.getElementById('analize-status').className = "loding-analize";
  document.getElementById('analize-status').innerHTML = "Analizing..........";
}

// set analize complited interface
function setAnalizeEndStatus(){
  document.getElementById('analize-status').className = "success-analize";
  document.getElementById('analize-status').innerHTML = "Process Compleated.";
}
</script>

<!-- 
  17 = this is used to anlize overrall user accouts data 
       - get total number of users

  18 = This is used to analize system records
      - set last analize date
->
