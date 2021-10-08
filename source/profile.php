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
                <form class= "profile-edit-form" action="" method="post">
                    <div class="form-header">
                        <h1>Profile Edit</h1>
                    </div>
                    <div class="edit-form-body">
                        <div style="grid-column:1 / 2; grid-row: 1 / 2">
                            <label for="firstname" class="label-title" >First Name</label><br>
                            <?php 
                                if(isset($_GET['firstname'])){
                                    echo '<input type="text" name="firstname" placeholder="enter your first name" value="'.$_GET['firstname'].'" class="form-input" required>';
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
                                echo '<input type="text" name="lastname" placeholder="enter your last name" value="'.$_GET['lastname'].'" class="form-input" required>';
                            }
                            else{
                                echo '<input type="text" name="lastname" placeholder="enter your last name" class="form-input" required>';
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
                    </div>
                    <div class="form-footer">
                        <button type="submit" name="register-submit" class="btn" style="grid-column:3 / 4; margin-right:20px">Save</button>
                    </div>
                </form>
                <form class= "profile-edit-form" action="" method="post">
                    <div style="margin:30px 0px 0px 30px; text-align: left;">
                        <label for="upassword" class="label-title" style="margin-right:30px">Change Password : </label>
                        <input type="password" size="20" name="upassword" placeholder="enter current password" class="form-input-pwd" style="margin-right:20px">
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
