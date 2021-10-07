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
<html lang="en">
    <head>
        <title>Settings</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/header.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>
    <body>
        <header>
            <ul>
            <li id="proName" style="float:left">ChatApp</li>
                <li class="navbar navbar-dark bg-dark navbar-expand-sm dropdown"><img src="profile-pic/unknownPerson.jpg" width="36" height="36" class="rounded-circle" id="userprofile">
                    <div class="dropdown-content">
                    <img src="profile-pic/unknownPerson.jpg" alt="Cinque Terre" width="200" height="200" class="rounded-circle pro-img" id="userprofile-dropdown">
                    <div class="desc"><p id="profileuser" style="color:white;">Name</p><p id="profileuname" style="color:white;">User Name</p></div>
                    <div class="dropdown-list">
                        <a href="#">Profile</a>
                        <a href="#">Setting</a>
                        <a href="#">Logout</a>
                    </div>
                    </div>
                </li>
                <li><a href="include/logout.inc.php">Logout</a></li>
                <li><a href="settings.php" class="active">Settings</a></li>
                <li><a href="profile.php">Friends</a></li>
            </ul>
            <script>
                if(){
                    document.getElementById("userprofile").src = "<?php echo 'profile-pic/'.$_SESSION["profileLink"].'';?>";
                    document.getElementById("userprofile-dropdown").src = "<?php echo 'profile-pic/'.$_SESSION["profileLink"].'';?>";
                }
                var name = "<?php echo ''.$_SESSION["fname"].' '.$_SESSION["lname"].'';?>";
                document.getElementById("profileuser").innerHTML = name;
                var usename = '<?php echo $_SESSION['uname'];?>';
                document.getElementById("profileuname").innerHTML = usename;
            </script>
        </header>
        <main>
        </main>

    </body>
</html>