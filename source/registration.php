<!-- this is for user registration -->
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Registration</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>        
		<link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
		<link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
		<script src="https://unpkg.com/dropzone"></script>
		<script src="https://unpkg.com/cropperjs"></script>
        <link rel="stylesheet" type="text/css" href="css/register.css">
        <link rel="stylesheet" type="text/css" href="css/header.css">
        <link rel="stylesheet" type="text/css" href="css/footer.css">
    </head>
    <body>
    <center>
        <div class='logo'><img src= "images/chatchops.png"></div>
        <div><b>
            Already have an Account?
            <a href= "login.php" id= "login-link">Login</a></b>
        </div>
    </center>

    <div id= "box" style= "position: center;">
        <!-- registration form -->
        <form class= "signup-form" action="include/register.inc.php" method="post" enctype="multipart/form-data">
            <div class="form-header">
                <h1>Create Account</h1>
            </div>
            <?php 
                // set profile picture paramiters
                if(isset($_GET['picn'])){
                    echo '<input type="hidden" name="foo" value="'.$_GET['picn'].'" id="prouppic">';
                }
                else{
                    echo '<input type="hidden" name="foo" value="unknownPerson.jpg" id="prouppic">';
                }
            ?>
            <div class="form-body">
                <!-- for first name-->
                <div style="grid-column:1 / 2; grid-row: 1 / 2">
                    <label for="firstname" class="label-title" >First Name</label><br>
                    <?php 
                        if(isset($_GET['firstname'])){
                            echo '<input type="text" name="firstname" placeholder="enter your first name" value='.$_GET['firstname'].' class="form-input" required>';
                        }
                        else{
                            echo '<input type="text" name="firstname" placeholder="enter your first name" class="form-input" required>';
                        }
                    ?>
                </div>
                <!-- for last name -->
                <div style="grid-column:2 / 3; grid-row: 1 / 2">
                    <label for="lastname" class="label-title">Last Name</label><br>
                    <?php
                    if(isset($_GET['lastname'])){
                        echo '<input type="text" name="lastname" placeholder="enter your last name" value='.$_GET['lastname'].' class="form-input" required>';
                    }
                    else{
                        echo '<input type="text" name="lastname" placeholder="enter your last name" class="form-input" required>';
                    }
                    ?>
                </div>
                <!-- this div for handel profile pictures -->
                <div style="grid-column:4 / 5; grid-row: 1 / 4">
                    <div class="image_area">
                    <label for="upload_image">
                        <?php
                        if(isset($_GET['picn'])){
                            echo '<img src="profile-pic/'.$_GET['picn'].'" id="uploaded_image" class="img-responsive img-circle" />';
                        }
                        else {
                            echo '<img src="profile-pic/unknownPerson.jpg" id="uploaded_image" class="img-responsive img-circle" />';
                        }
                        ?>
                        <div class="overlay">
                            <div class="text">Click to Change Profile Image</div>
                        </div>
                        <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                    </label>
                    </div>

                    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Crop Image Before Upload</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="img-container">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <img src="" id="sample_image" />
                                            </div>
                                            <div class="col-md-4">
                                                <div class="preview"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" id="crop" class="btn btn-primary">Crop</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- for email -->
                <div style="grid-column:1 / 2; grid-row: 2 / 3">
                    <label for="uemail" class="label-title">Email*</label><br>
                    <?php
                        if(isset($_GET['umail'])){
                            echo '<input type="email" name="uemail" placeholder="enter your email"  value='.$_GET['umail'].' class="form-input">';
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
                            echo '<input type="text" name="username" placeholder="enter your user name" value='.$_GET['username'].' class="form-input">';
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
                <span class= "status" >* required</span>

                <?php
                    $errmsg = "";

                    if(isset($_GET['signerror'])){
                        $errmsg = setErrMessage();
                    }

                    echo '<span id="error-bar" >'.$errmsg.'</span>';
                ?>

                <button type="submit" name="register-submit" class="btn" >Create</button>
            </div>
        </form>
    </div>

    <footer>
        <p>Copyright &copy; 2021 ChatChops. Inc. All rights reserved</p>
    </footer>
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
            else if($_GET['signerror'] == "wrongfname" || $_GET['signerror'] == "errlname"){
                return "Use Only characters (A-Z and a-z)";
            }
            else if($_GET['signerror'] == "errusername"){
                return "Use Only characters and numbers (A-Z , a-z, 0-9)";
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

<!-- script for profile photo -->
<script>

$(document).ready(function(){

	var $modal = $('#modal');

	var image = document.getElementById('sample_image');

	var cropper;

	$('#upload_image').change(function(event){
		var files = event.target.files;

		var done = function(url){
			image.src = url;
			$modal.modal('show');
		};

		if(files && files.length > 0)
		{
			reader = new FileReader();
			reader.onload = function(event)
			{
				done(reader.result);
			};
			reader.readAsDataURL(files[0]);
		}
	});

	$modal.on('shown.bs.modal', function() {
		cropper = new Cropper(image, {
			aspectRatio: 1,
			viewMode: 3,
			preview:'.preview'
		});
	}).on('hidden.bs.modal', function(){
		cropper.destroy();
   		cropper = null;
	});

	$('#crop').click(function(){
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});

		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
				$.ajax({
					url:'profileUpload.php',
					method:'POST',
					data:{image:base64data},
					success:function(data)
					{
						$modal.modal('hide');
						$('#uploaded_image').attr('src', data);
                        document.getElementById("prouppic").value = data.substr(12);
					}
				});
			};
		});
	});
	
});
</script>