<?php
//require_once 'glogin-config.php';

//user data
$fname = "Rashmi";
$lname = "Wijesekara";
$email;
$pic;

?>
<html>
    <head>
        <title>Password Setting</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/glogin-password.css">
    </head>
    <body>
    <center>
        <div class='logo'><img src= "../images/chatchops.png"></div>
        
        <div class = "user-name-display">
        <b><?php echo $fname . " ". $lname; ?></b></div>

        <div class = "welcome">
            Welcome to ChatChops!
        </div>

        <div id= "box" style= "position: center;">
        <form class= "pswd-setting" action="include/register.inc.php" method="post">
    </center>
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