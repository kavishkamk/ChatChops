<?php 
    session_start();

    if(!isset($_SESSION['userid'])){
        header("Location:login.php?logout=logoutok");
        exit();
    }
?>

<!-- This is Main interface -->
<!-- To access this page, session is mandotory. Otherwise this page direct to the login page -->

<!DOCTYPE HTML>
<html>
    <head>
        <title>Settings</title>
        <link rel="stylesheet" href="css/header.css">
    </head>
    <body>
        <header>
            <ul>
                <li id="proLogo" style="float:left">
                    <img src = "images/chatchops.png"></img>
                </li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="settings.php" class="active">Settings</a></li>
                <li style="float:right"><a href="include/logout.inc.php">Logout</a></li>
            </ul>
        </header>
        <main>
        <?php
           if(isset($_SESSION['userid'])){
                echo '<p style="color:green">You are logged In. Session created</p>';
           }
        ?>
        </main>

    </body>
</html>