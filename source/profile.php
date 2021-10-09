<?php 
    require_once "header.php";
?>  <!--This file for changing profile details-->
    <main>
        <div class="main-grid">
            <div style="grid-column:1 / 2" align="center" class="propic-change">
                <div class="conteiner" style= "position: center;">
                    <div class="image_area">
                        <label for="upload_image">
                            <img src="<?php echo 'profile-pic/'.$_SESSION["profileLink"].'';?>" id="uploaded_image" class="img-responsive img-circle" />
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
            </div>
            <div style="grid-column:2 / 3;" class="edit-details">
                <form class= "profile-edit-form" action="include/changeUserProfile.inc.php" method="post">
                    <div class="form-header">
                        <h1>Profile Edit</h1>
                    </div>
                    <div class="edit-form-body">
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
                        <!-- for username -->
                        <div style="grid-column:1 / 2; grid-row: 2 / 3">
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
                    </div>
                    <div class="form-footer">
                        
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
                        <button type="submit" name="profile-submit" class="btn" style="grid-column:3 / 4; margin-right:20px">Save</button>
                    </div>
                </form>
                <form class= "profile-edit-form" action="include/ChangeMail.inc.php" method="post">
                    <div style="margin:30px 0px 0px 30px; text-align: left;">
                        <label for="uemail" class="label-title" style="margin-right:30px">Change Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </label>
                        <input type="email" size="29" name="uemail" placeholder="enter your email" class="form-input-pwd" style="margin-right:20px; margin-bottom:0px">
                        <button type="submit" name="email-change-submit" class="btn" text-align="right">Change Email</button>
                    </div>
                </form>
                <form class= "profile-edit-form" action="" method="post">
                    <div style="margin:30px 0px 0px 30px; text-align: left;">
                        <label for="upassword" class="label-title" style="margin-right:30px">Change Password : </label>
                        <input type="password" size="29" name="upassword" placeholder="enter current password" class="form-input-pwd" style="margin-right:20px; margin-bottom:0px">
                        <button type="submit" name="pwd-change-submit" class="btn" text-align="right">Change Password</button>
                    </div>
                </form>
                <form class= "profile-edit-form" action="" method="post">
                    <div style="margin:30px 7% 0px 30px; float: right;">
                        <button type="submit" name="acc-delete-submit" class="btn" style="background-color: red; margin-bottom:20px">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>

    </main>
    </body>
</html>

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
?>

<script>
// frofile change script
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
                url:'changeProfile.php',
                method:'POST',
                data:{image:base64data},
                success:function(data)
                {
                    $modal.modal('hide');
                    $('#uploaded_image').attr('src', data);
                    window.location.reload(true);
                }
            });
        };
    });
});

});
</script>
