<?php
require_once 'glogin-config.php';

$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];

?>
<html>
    <head>
        <title>Password Setting</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/glogin-password.css">
        <link rel="stylesheet" href="../css/footer.css">
    </head>
    <body>
    <center>
        <div class='logo'><img src= "../images/chatchops.png"></div>
        
        <div class = "username-display">
        <b><?php echo $fname . " ". $lname; ?></b></div>

        <div class = "welcome">
            Welcome to ChatChops!
        </div>

        <form class= "pswd-setting" action="../include/google-signin.inc.php" method="post">
        <div class="form-header">
            <h1>Password Setting</h1>
        </div>
        <div id= "box" style= "position: center;">
            <b><label for="pswd">Password</label></b><br>
            <input type="password" name="pswd" placeholder="Enter password" size="30" class="pswd-enter">
            <br><br>
            <b><label for="pswd">Confirm Password</label></b><br>
            <input type="password" name="confirm-pswd" placeholder="Enter password again" size="30" class="pswd-enter">
            <br>
                    
            <button type="submit" name="pswd-submit" class="logbutn">Enter</button>
        </div>
        </form>
    </center>

    <footer style= "margin-top: 180px;">
        <p>Copyright &copy; 2021 ChatChops. Inc. All rights reserved</p>
    </footer>

    </body>
</html>

<?php
/*
    if($login_button == ''){
        echo '<div>Welcome User</div>';
        echo '<img src="'.$_SESSION["user_image"].'" />';
        echo '<h3><b>Name :</b> '.$_SESSION['user_first_name'].' '.$_SESSION['user_last_name'].'</h3>';
        echo '<h3><b>Email :</b> '.$_SESSION['user_email_address'].'</h3>';
        echo '<h3><a href="../logout.php">Logout</a></h3></div>';

        //require_once 'send-data-to-db.php';
    }
    else{
        echo '<div align="center">'.$login_button . '</div>';
    }*/
?>