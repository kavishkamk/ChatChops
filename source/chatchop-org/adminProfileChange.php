<?php 
    // this is for show data sumary
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
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" type="text/css" href="../css/adminProfile.css">
        <title>Report</title>
    </head>
    <body>
    <main class="container">
        <div class= "signup-form" style= "position: center;">
            <!-- registration form -->
                <div class="form-header">
                    <h1>Profile Update</h1>
                </div>
                <div class="form-body">
                    <div style="grid-row: 1 / 2;">
                        <form action="../include/AdminDetailsChange.inc.php" method="post">
                            <div class="user-details">
                                <div style="grid-row: 1 / 2;">
                                    <label for="fname" class="label-title">First Name</label><br>
                                    <input type="text" name="fname" placeholder="enter your first name" class="form-input">
                                </div>
                                <div style="grid-column:3 / 4; grid-row: 1 / 2;">
                                    <label for="lname" class="label-title">Last Name</label><br>
                                    <input type="text" name="lname" placeholder="enter your last name" class="form-input">
                                </div>
                                <div style="grid-column:1 / 2; grid-row: 2 / 3;">
                                    <label for="uname" class="label-title">User Name</label><br>
                                    <input type="text" name="uname" placeholder="enter your uset name" class="form-input">
                                </div>
                                <div style="grid-column:1 / 4; grid-row: 3 / 4;" class="user-status-bar">
                                    <?php
                                        $errmsg = "";

                                        if(isset($_GET['proedit'])){
                                            $errmsg = setErrMessage();
                                            echo '<span style="grid-column:1 / 3;" class="error-bar">'.$errmsg.'</span>';
                                        }
                                        else if(isset($_GET['proedits'])){
                                            $msg = setMessage();
                                            echo '<span style="grid-column:1 / 3;" class="success-bar">'.$msg.'</span>';
                                        }   
                                    ?>
                                    <div style="grid-column:3 / 4;">
                                        <button type="submit" name="profile-submit" class="btn">Change</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="ad-email" style="grid-row: 2 / 3;">
                        <form action="../include/ChaangeAdminMail.inc.php" method="post">
                            <label for="umeil" class="label-title">Change Email</label>
                            <input type="email" name="umeil" placeholder="Enter user mail" class="form-input">
                            <button type="submit" name="email-submit" class="btn">Change</button>
                        </form>
                    </div>
                    <div style="grid-row: 3 / 4;">
                        <form action="../include/AdminPwdChange.inc.php" method="post">
                            <label for="upwd" class="label-title">Change Password</label>
                            <input type="password" name="upwd" placeholder="enter current password" class="form-input">
                            <button type="submit" name="pwd-submit" class="btn">Change</button>
                        </form>
                    </div>
                    <div style="grid-row: 4 / 5;" class="footer-div back-btn">
                        <div>
                        <form>
                            <button  formaction="../chatchop-dashboard/chatadmin.php" class="btn">
                                    Back..
                            </button>  
                        </form>
                        </div>
                        <div>
                        <?php
                            $errmsg = "";

                            if(isset($_GET['mailedit'])){
                                if($_GET['mailedit'] == "s"){
                                    echo '<span style="grid-column:1 / 3;" class="success-bar">Email Changed</span>';
                                }
                                else{
                                    $errmsg = setMailErrMessage();
                                    echo '<span style="grid-column:1 / 3;" class="error-bar">'.$errmsg.'</span>';
                                }
                            }
                            else if(isset($_GET['pwdedit'])){
                                if($_GET['pwdedit'] == "ok"){
                                    echo '<span style="grid-column:1 / 3;" class="success-bar">Password Change Success</span>';
                                }
                                else{
                                    $errmsg = setPwdErrMessage();
                                    echo '<span style="grid-column:1 / 3;" class="error-bar">'.$errmsg.'</span>';
                                } 
                            }
                        ?>
                        </div>
                        <div style="grid-column:3 / 4;">
                        <form action="../include/AdminDeleteAdd.inc.php" method="post">
                            <input type="hidden" name="delstat" value="okDelete" required>
                            <button type="submit" name="del-submit" class="btn" onclick = "clicked();" style="background-color: red; float: right; width: 200px;">DELETE ACCOUNT</button>
                        </form>
                        </div>
                    </div>
                </div>
        </div>
    </main>
    </body>
</html>

<script type="text/javascript">
    function clicked() {
       if (confirm('Do you realy want to Delete your accout?')) {
            delacc.submit();
       } else {
           return false;
       }
    }

</script>

<!-- set registration error messages -->
<?php
    function setErrMessage(){
        if(isset($_GET['proedit'])){
            if($_GET['proedit'] == "allempty"){
                return "Nothing to Change";
            }
            else if($_GET['proedit'] == "availableuname"){
                return "Alrady have this. Use another one";
            }
            else if($_GET['proedit'] == "sqlerr" || $_GET['proedit'] == "error"){
                return "Somting wrong. Please try again";
            }
            else if($_GET['proedit'] == "fnamechar"){
                return "Use Only characters (A-Z and a-z) for first name";
            }
            else if($_GET['proedit'] == "fnamenum"){
                return "Max 30 for first Name.";
            }
            else if($_GET['proedit'] == "lnamechar"){
                return "Use Only characters (A-Z and a-z) for last name";
            }
            else if($_GET['proedit'] == "lnamenum"){
                return "Max 30 for last Name.";
            }
            else if($_GET['proedit'] == "unamechar"){
                return "Use Only characters and numbers (A-Z , a-z, 0-9) for username";
            }
            else if($_GET['proedit'] == "unamenum"){
                return "Max 50 for username";
            }
        }
    }

    function setMessage(){
        if(isset($_GET['proedits'])){
            if($_GET['proedits'] == "success" || $_GET['proedits'] == "unameok"){
                return "Successfully Changed";
            }
        }
    }

    function setMailErrMessage(){
        if(isset($_GET['mailedit'])){
            if($_GET['mailedit'] == "empty"){
                return "Nothing to Change";
            }
            else if($_GET['mailedit'] == "invalid"){
                return "Wrong Email";
            }
            else if($_GET['mailedit'] == "avilablemail"){
                return "Available Email. User another one";
            }
            else if($_GET['mailedit'] == "sqlerr"){
                return "Something Wrong. Try again";
            }
        }
    }

    function setPwdErrMessage(){
        if(isset($_GET['pwdedit'])){
            if($_GET['pwdedit'] == "empty"){
                return "Nothing to Change";
            }
            else if($_GET['pwdedit'] == "wrongpwd"){
                return "Wrong Password";
            }
            else {
                return "Something Wrong. Try again";
            }
        }
    }
?>