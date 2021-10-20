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
      <main>
        <!-- Main -->
        <form>

            <form>
                <br />
                <button class="button button1">Get Today Report</button>
                <br>
                <br>
                <button class="button button1">Get Last Week Report</button>
                <br />
                <br />
                <button class="button button1">Get Last Month Report</button>

              </form>

    <!-- for drop down & calender -->


          <p>Select a date</p>
          <form>
            <label for="Choose a date">Date:</label>
            <input type="date" id="day" name="day" />
            <!-- <input type="submit" /> -->
          </form>
          <br>
          <br>
          <form>
            <label for="Type">Choose a Type to display the Report:</label>
            <select name="Type" id="Type">
              <option value="Table">Table</option>
              <option value="Graph">Graph</option>

            </select>
            <br />
            <form >
                <button class="button button1">Confirm</button>
                <br>
            </form>
            <form>
              <button class="button button3"><a href='chatdashboardnew.html' alt='Broken Link'>Back</a></button>

            </form>
          </form>


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
