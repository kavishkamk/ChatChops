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

<!-- this is for admin registration -->
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Registration</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="../css/register.css">
        <link rel="stylesheet" type="text/css" href="../css/header.css">
        <link rel="stylesheet" type="text/css" href="../css/footer.css">

        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        <style>
        body {
            font-family: 'Roboto';
        }
        </style>
    </head>
    <body>
        <center>
            <div class='logo'><img src= "../images/chatchops.png"></div>
        </center>
        <div id= "box" style= "position: center;">
            <!-- registration form -->
            <form class= "signup-form" action="../include/adminRegistration.inc.php" method="post">
                <div class="form-header">
                    <h1>Create Account</h1>
                </div>
                <div class="admin-form-body">
                    <div style="grid-column:1 / 2; grid-row: 1 / 2">
                        <label for="firstname" class="label-title" >First Name</label><br>
                        <?php 
                            if(isset($_GET['firstname'])){
                                echo '<input type="text" name="firstname" placeholder="enter your first name" value="'.$_GET['firstname'].'" class="form-input">';
                            }
                            else{
                                echo '<input type="text" name="firstname" placeholder="enter your first name" class="form-input">';
                            }
                        ?>
                    </div>
                    <!-- for last name -->
                    <div style="grid-column:2 / 3; grid-row: 1 / 2">
                        <label for="lastname" class="label-title">Last Name</label><br>
                        <?php
                        if(isset($_GET['lastname'])){
                            echo '<input type="text" name="lastname" placeholder="enter your last name" value="'.$_GET['lastname'].'" class="form-input">';
                        }
                        else{
                            echo '<input type="text" name="lastname" placeholder="enter your last name" class="form-input">';
                        }
                        ?>
                    </div>
                    <!-- for email -->
                    <div style="grid-column:1 / 2; grid-row: 2 / 3">
                        <label for="uemail" class="label-title">Email*</label><br>
                        <?php
                            if(isset($_GET['umail'])){
                                echo '<input type="email" name="uemail" placeholder="enter your email"  value="'.$_GET['umail'].'" class="form-input">';
                            }
                            else{
                                echo '<input type="email" name="uemail" placeholder="enter your email" class="form-input">';
                            }
                        ?>
                    </div>
                    <!-- for username -->
                    <div style="grid-column:2 / 3; grid-row: 2 / 3">
                        <label for="username" class="label-title">Username</label><br>
                        <?php
                            if(isset($_GET['username'])){
                                echo '<input type="text" name="username" placeholder="enter your user name" value="'.$_GET['username'].'" class="form-input">';
                            }
                            else{
                                echo '<input type="text" name="username" placeholder="enter your user name" class="form-input">';
                            }
                        ?>
                    </div>
                    <!-- for password and comfirm password-->
                    <div style="grid-column:1 / 2; grid-row: 3 / 4">
                        <label for="upassword" class="label-title">Password</label><br>
                        <input type="password" name="upassword" placeholder="enter password" class="form-input">
                    </div>
                    <div style="grid-column:2 / 3; grid-row: 3 / 4">
                        <label for="confirm-password" class="label-title">Comfirm Password</label><br>
                        <input type="password" name="confirm-password" placeholder="enter your password again" class="form-input">
                    </div>
                </div>
                <!-- form footer -->
                <div class="form-footer">
                <form>
                    <button  formaction="../chatchop-dashboard/chatadmin.php" class="back-button">
                            Back..
                    </button>  
                </form>

                    <?php
                        $errmsg = "";

                        if(isset($_GET['signerror'])){
                            $errmsg = setErrMessage();
                            echo '<span class="error-bar" >'.$errmsg.'</span>';
                        }
                        else if(isset($_GET['register'])){
                            echo '<span class="success-bar" >Registration Success</span>';
                        }
                        else{
                            echo '<span class="error-bar" > </span>';
                        }
                    ?>

                    <button type="submit" name="register-submit" class="btn" >Create</button>
                </div>
            </form>
        </div>   
    </body>
</html>

<!-- set registration error messages -->
<?php
    function setErrMessage(){
        if(isset($_GET['signerror'])){
            if($_GET['signerror'] == "emptyfield"){
                return "Fill all the fields";
            }
            else if($_GET['signerror'] == "wrongmail"){
                return "Wrong email address";
            }
            else if($_GET['signerror'] == "wrongfname"){
                return "Use Only characters (A-Z and a-z) for first name";
            }
            else if($_GET['signerror'] == "errlname"){
                return "Use Only characters (A-Z and a-z) for last name";
            }
            else if($_GET['signerror'] == "errusername"){
                return "Use Only characters and numbers (A-Z , a-z, 0-9) username";
            }
            else if($_GET['signerror'] == "errpwd"){
                return "Wrong password";
            }
            else if($_GET['signerror'] == "abailableEmail"){
                return "This email is alrady used to create account..";
            }
            else if($_GET['signerror'] == 'abailableuname'){
                return "This username is alrady used to create account..";
            }
            else if($_GET['signerror'] == 'fnamemax'){
                return "Max 30 for first Name.";
            }
            else if($_GET['signerror'] == 'lnamemax'){
                return "Max 30 for last Name.";
            }
            else if($_GET['signerror'] == 'unamemax'){
                return "Max 50 for username";
            }
        }
    }
?>