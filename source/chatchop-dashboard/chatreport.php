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
          <form>
            <button class="log-out-btn" formaction="../include/AdminLogout.inc.php">Log Out</button>
          </form>
          <!-- nav -->
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
              <input type="button" value="All" id="t17" class="f-button">
              <span></span>
              <span class="" id="analize-status"></span>
            </div>
          </div>
          <div class = "report-type">
            <div class="report-lable">User Online Records</div>
            <div class="report-input">
              <input type="button" value="Today" id="t1" class="f-button">
              <input type="button" value="Month" class="report-time" id="t2">
              <input type="button" value="Week" class="report-time" id="t3">
              <input type="button" value="Date" class="report-time" id="t4">
            </div>
          </div>
          <div class = "report-type">
            <div class="report-lable">Private Chat Messages</div>
            <div class="report-input">
              <input type="button" value="Today" id="t5" class="f-button">
              <input type="button" value="Month" class="report-time" id="t6">
              <input type="button" value="Week" class="report-time" id="t7">
              <input type="button" value="Date" class="report-time" id="t8">
            </div>
          </div>
          <div class = "report-type">
            <div class="report-lable">Private Group Chat Messages</div>
            <div class="report-input">
              <input type="button" value="Today" id="t9" class="f-button">
              <input type="button" value="Month" class="report-time" id="t10">
              <input type="button" value="Week" class="report-time" id="t11">
              <input type="button" value="Date" class="report-time" id="t12">
            </div>
          </div>
          <div class = "report-type">
            <div class="report-lable">Public Group Chat Messsages</div>
            <div class="report-input">
              <input type="button" value="Today" id="t13" class="f-button">
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
              <div class="goption" id="disp-type">
                
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
    document.getElementById('chooes-report').innerHTML = "Report Type : Today User Online"; 
    document.getElementById('reporttime').type = "hidden";
    document.getElementById('report-form').action = "../reportsDirections/userOnlineRecInDay.php";
    document.getElementById('reType').value = 1;
    document.getElementById('timelable').innerHTML = "Set Time : Today";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of online users in Today";
    document.getElementById('report-discription').innerHTML = "This is for genarate report to user online data in Today.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t2").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : User Online Data in given Month"; 
    document.getElementById('reporttime').type = "month";
    document.getElementById('report-form').action = "../reportsDirections/userOnlineRecInMonth.php";
    document.getElementById('reType').value = 2;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "User Online data in given month";
    document.getElementById('report-discription').innerHTML = "This is for genarate report to user online data in given month.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t3").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : User Online data in given Week";
    document.getElementById('reporttime').type = "week";
    document.getElementById('report-form').action = "../reportsDirections/userOnlineRecInWeek.php";
    document.getElementById('reType').value = 3;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "User Online data in given Week";
    document.getElementById('report-discription').innerHTML = "This is for genarate report to user online data in given Week.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t4").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Number of online users in given Date";
    document.getElementById('reporttime').type = "date";
    document.getElementById('report-form').action = "../reportsDirections/userOnlineRecInDay.php";
    document.getElementById('reType').value = 4;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of online users in given Date";
    document.getElementById('report-discription').innerHTML = "This is for genarate report to user online data in given date.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t5").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Today Number of Private chat messages";
    document.getElementById('reporttime').type = "hidden";
    document.getElementById('report-form').action = "../reportsDirections/priChatMsgRecInDay.php";
    document.getElementById('reType').value = 5;
    document.getElementById('timelable').innerHTML = "Set Time : Today";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of private chat messages in Today";
    document.getElementById('report-discription').innerHTML = "This genarate reports about private chat messages data in Today.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t6").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Number Of Private Chat messages in given Month"; 
    document.getElementById('reporttime').type = "month";
    document.getElementById('report-form').action = "../reportsDirections/priChatMsgRecInMonth.php";
    document.getElementById('reType').value = 6;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of private chat messages in given Month";
    document.getElementById('report-discription').innerHTML = "This genarate reports about private chat messages data in given month.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t7").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Number of Private Chat messages in given Week";
    document.getElementById('reporttime').type = "week";
    document.getElementById('report-form').action = "../reportsDirections/priChatMsgRecInWeek.php";
    document.getElementById('reType').value = 7;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of private chat messages in given Week";
    document.getElementById('report-discription').innerHTML = "This genarate reports about private chat messages data in given Week.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t8").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Number of Private Chat Messages in Given Date";
    document.getElementById('reporttime').type = "date";
    document.getElementById('report-form').action = "../reportsDirections/priChatMsgRecInDay.php";
    document.getElementById('reType').value = 8;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of private chat messages in given Day";
    document.getElementById('report-discription').innerHTML = "This genarate reports about private chat messages data in given Day.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t9").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Today Number of Private Group chat messages";
    document.getElementById('reporttime').type = "hidden";
    document.getElementById('report-form').action = "../reportsDirections/priGrpChatMsgRecInDay.php";
    document.getElementById('reType').value = 9;
    document.getElementById('timelable').innerHTML = "Set Time : Today";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of private Group chat messages in Today";
    document.getElementById('report-discription').innerHTML = "This genarate reports about private Group chat messages data in Today.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t10").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Number of Private Group Chat Messages in Given Month"; 
    document.getElementById('reporttime').type = "month";
    document.getElementById('report-form').action = "../reportsDirections/priGrpChatMsgRecInMonth.php";
    document.getElementById('reType').value = 10;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of private group chat messages in given Month";
    document.getElementById('report-discription').innerHTML = "This genarate reports about private group chat messages data in given Month.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t11").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Number of Private Group Chat Messages in Given Week";
    document.getElementById('reporttime').type = "week";
    document.getElementById('report-form').action = "../reportsDirections/priGrpChatMsgRecInWeek.php";
    document.getElementById('reType').value = 11;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of private group chat messages in given Week";
    document.getElementById('report-discription').innerHTML = "This genarate reports about private group chat messages data in given Week.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t12").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Number of Private Group Chat Message in Given Day";
    document.getElementById('reporttime').type = "date";
    document.getElementById('report-form').action = "../reportsDirections/priGrpChatMsgRecInDay.php";
    document.getElementById('reType').value = 12;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of private group chat messages in given Day";
    document.getElementById('report-discription').innerHTML = "This genarate reports about private group chat messages data in given Day.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t13").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Today Number of public Group chat messages";
    document.getElementById('reporttime').type = "hidden";
    document.getElementById('report-form').action = "../reportsDirections/pubGrpChatMsgRecInDay.php";
    document.getElementById('reType').value = 13;
    document.getElementById('timelable').innerHTML = "Set Time : Today";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of public Group chat messages in Today";
    document.getElementById('report-discription').innerHTML = "This genarate reports about public Group chat messages data in Today.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t14").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Number of Public Group Chat Message in Given Month";
    document.getElementById('reporttime').type = "month";
    document.getElementById('report-form').action = "../reportsDirections/pubGrpChatMsgRecInMonth.php";
    document.getElementById('reType').value = 14;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of public group chat messages in given Month";
    document.getElementById('report-discription').innerHTML = "This genarate reports about public group chat messages data in given Month.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t15").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Number of Public Group Chat Message in Given Week";
    document.getElementById('reporttime').type = "week";
    document.getElementById('report-form').action = "../reportsDirections/pubGrpChatMsgRecInWeek.php";
    document.getElementById('reType').value = 15;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of public group chat messages in given Week";
    document.getElementById('report-discription').innerHTML = "This genarate reports about public group chat messages data in given Week.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t16").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Number of Public Group Chat Message in given Day";
    document.getElementById('reporttime').type = "date";
    document.getElementById('report-form').action = "../reportsDirections/pubGrpChatMsgRecInDay.php";
    document.getElementById('reType').value = 16;
    document.getElementById('timelable').innerHTML = "Set Time : ";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option><option value='Graph'>Graph</option></select>";
    document.getElementById('report-head').innerHTML = "Number of public group chat messages in given Day";
    document.getElementById('report-discription').innerHTML = "This genarate reports about public group chat messages data in given Day.<br><ul><li>1. Graph</li><li>2. Table</li></ul>";
  });
  $("#t17").click(function(){
    document.getElementById('chooes-report').innerHTML = "Report Type : Usege Summary";
    document.getElementById('reporttime').type = "hidden";
    document.getElementById('report-form').action = "../reports/userAccReports.php";
    document.getElementById('reType').value = 17;
    document.getElementById('timelable').innerHTML = "Set Time : Overrall";
    document.getElementById('disp-type').innerHTML = "<label for='Type'>Report Display Type : </label><select name='Type' id='Type'><option value='Table'>Table</option></select>";
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
  01 = this is used to get report of today online users
       - number of online users in today in each hour
  02 = this is used to get report of given month online users
       - number of online users in given month in each day
  03 = this is used to get report of given week online users
       - number of online users in given week in each day
  04 = this is used to get report of given day online users
       - number of online users in given day in each hour
  05 = this is used to get report of today private chat messages
       - number of private chat messages in today in each hour
  06 = this is used to get report of given month private chat messages
       - number of private chat messages in month in each day
  07 = this is used to get report of given week private chat messages
       - number of private chat messages in week in each day
  08 = this is used to get report of given day private chat messages
       - number of private chat messages in day in each hour
  09 = this is used to get report of today private group chat messages
       - number of private group chat messages in today in each hour
  10 = this is used to get report of given month private group chat messages
       - number of private group chat messages in month in each day
  11 = this is used to get report of given week private group chat messages
       - number of private group chat messages in week in each day
  12 = this is used to get report of given day private group chat messages
       - number of private gropu chat messages in day in each hour
  13 = this is used to get report of today public group chat messages
       - number of public group chat messages in today in each hour
  14 = this is used to get report of given month public group chat message
       - number of public gropu chat messages in month in each day
  15 = this is used to get report of given week public group chat message
       - number of public group chat message in week in each day
  16 = this is used to get report of given day public group chat message
       - number of public group chat message in day in each hour
  17 = this is used to anlize overrall user accouts data 
       - get total number of users
  18 = This is used to analize system records
      - set last analize date
->
