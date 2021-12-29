<?php 
    session_start();

    if(!isset($_SESSION['userid'])){
         header("Location:../login.php?logout=logoutok"); // no session
         exit();
    }
    else{
        require_once "../phpClasses/SessionHandle.class.php";
        $sessObj = new SessionHandle();
        $sessRes = $sessObj->checkSession($_SESSION['sessionId'], $_SESSION['userid']); // invalid session
        unset($sessObj);
        if($sessRes != "1"){
            header("Location:../login.php?logout=logoutok"); // no session
            exit();
        }
    }
?>

<!-- This is header file for web pages -->
<!-- To access this page, session is mandotory. Otherwise this page direct to the login page -->

<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>ChatChops</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../css/header.css">
        <link rel="stylesheet" type="text/css" href="../css/body.css">
        <link rel="stylesheet" type="text/css" href="../css/chatUI.css">

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>        
		<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
		<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
		<script src="https://unpkg.com/dropzone"></script>
		<script src="https://unpkg.com/cropperjs"></script>
        <script src="https://kit.fontawesome.com/4ad1efa998.js" crossorigin="anonymous"></script>
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        <style>
        body {
            font-family: 'Roboto';
        }
        </style>
    </head>
    <body>
        <!--navigation bar-->
        <header>
            <ul>
            <li id="proName" style="float:left"><a href="../insideUI/chatChops.php" id="logolink">
                <img src="../images/s-chatchops.png"
                    style="background-color: white;
                            width: 130px;
                            height: auto;
                            margin-top: 15px;
                            margin-left: 5px;">
                </img></a></li>
                <li class="navbar navbar-dark bg-dark navbar-expand-sm dropdown pronav"><img src="../profile-pic/unknownPerson.jpg" width="36" height="36" class="img-circle" id="userprofile">
                    <div class="dropdown-content">
                    <img src="../profile-pic/unknownPerson.jpg" alt="Cinque Terre" width="200" height="200" class="img-circle pro-img" id="userprofile-dropdown">
                    <div class="desc"><p id="profileuser" style="color:white;">Name</p><p id="profileuname" style="color:white;">User Name</p></div>
                    <div class="dropdown-list">
                        <a href="../insideUI/profile.php">Profile</a>
                        <a href="../include/logout.inc.php">Logout</a>
                    </div>
                    </div>
                </li>
                <li><a href="../include/logout.inc.php">Logout</a></li>
                <li><a href="../insideUI/chatChops.php">Chat</a></li>
                <li><a href="../friendList/friendList.php">Friends</a></li>
            </ul>
            <script>
                document.getElementById("userprofile").src = "<?php echo '../profile-pic/'.$_SESSION["profileLink"].'';?>";
                document.getElementById("userprofile-dropdown").src = "<?php echo '../profile-pic/'.$_SESSION["profileLink"].'';?>";
                var name = "<?php echo ''.$_SESSION["fname"].' '.$_SESSION["lname"].'';?>";
                document.getElementById("profileuser").innerHTML = name;
                var usename = '<?php echo $_SESSION['uname'];?>';
                document.getElementById("profileuname").innerHTML = usename;
            </script>
        </header>