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
        <div class="top-bar">
          <form>
            <button class="log-out-btn" formaction="../include/AdminLogout.inc.php">Log Out</button>
          </form>
        </div>
      </nav>

      <!-- main -->
      <main> <p>
         <h3> Welcome to ChatChop.......!</h3>

      </p>
      <br>
    <p>Implemented by 2nd year Software Engineering Undergraduates.</p>
    <br>
    <p>Group  : Game Changers</p>
    <br>
    <p>Kavishka Madushan</p>
    <br>
    <p>Rashmi Wijesekara
        <br>
        <p>Vishvi De Silva</p>
    </p>
    <form action="../include/AdminLogout.inc.php">
      <button class="button button2">logout</button>
      <br>
  </form>
  </main>

      <!-- setting the dashboard -->
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
        <br>
        <br>
        
      </div>
      <!-- setting the footer -->

      <footer>
        <p>ChatChop &copy; 2021</p>
      </footer>
    </div>
  </body>
</html>
