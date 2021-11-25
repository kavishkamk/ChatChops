<html>

<head>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>        
    <link rel="stylesheet" href="https://unpkg.com/dropzone/dist/dropzone.css" />
    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
    <script src="https://unpkg.com/dropzone"></script>
    <script src="https://unpkg.com/cropperjs"></script>

    <base href="http://localhost/chatchops/source/"/>
    <link rel="stylesheet" href="css/create-private-group.css">
    
    <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
    <style>
    body {
        font-family: 'Roboto';
    }
    </style>
</head>

<body>
    <div id= "box" style= "position: center;">
        <!-- private group creating form -->
        <form class= "pri-grp-form" id="prig" action="private-groups/create-private-group.inc.php" method="post" enctype="multipart/form-data">
            <div class="form-header">
                <h1>Create a Private Group</h1>
            </div>

            <?php
                // set group icon
                if(isset($_GET['picn'])){
                    //echo '<script>alert ("'.$_GET['picn'].'")</script>';
                    echo '<input type="hidden" name="foo" value="'.$_GET['picn'].'" id="prouppic">';
                }
                else{
                    echo '<input type="hidden" name="foo" value="groupchat-icon.png" id="prouppic">';
                }
            ?>
            
            <div class="form-body" style= "position: center;">

                <!-- this div is to handle group icons -->
                <div>
                    <div class="image_area">
                        <label for="upload_image">
                            <?php
                            if(isset($_GET['picn'])){
                                //echo '<script>alert ("'.$_GET['picn'].'")</script>';
                                echo '<img src="group-icons/'.$_GET['picn'].'" id="uploaded_image" class="img-responsive img-circle" />';
                            }
                            else {
                                echo '<img src="group-icons/groupchat-icon.png" id="uploaded_image" class="img-responsive img-circle" />';
                            }
                            ?>
                            <div class="overlay">
                                <div class="text">Click to Change the Group Icon</div>
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

                                <!-- crop preview-->
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

                <!-- for group name-->
                <div>
                    <label for="groupname" class="label-title" >Group Name *</label><br>
                    <?php 
                        if(isset($_GET['groupname'])){
                            echo '<input type="text" 
                            name="groupname" 
                            placeholder="enter the group name" 
                            value="'.$_GET['groupname'].'" 
                            class="form-input" 
                            required>';
                        }
                        else{
                            echo '<input type="text" 
                            name="groupname" 
                            placeholder="enter the group name" 
                            class="form-input" 
                            required>';
                        }
                    ?>
                </div>
                
                <!-- for group bio-->
                <div>
                    <label for="groupbio" class="label-title">Description *</label><br>
                    <?php 
                        if(isset($_GET['groupbio'])){
                            $groupbio = $_GET['groupbio'];
                            echo '<textarea name="groupbio" form="prig" placeholder="enter a description about your group chat" required>'.$groupbio.'</textarea>';
                        }
                        else{
                            echo '<textarea name="groupbio" form="prig" placeholder="enter a description about your group chat" required></textarea>';
                        }
                    ?>
                </div>
            </div>

            <?php
                    $errmsg = "";
                    if(isset($_GET['error'])){
                        $errmsg = setErrMessage();
                    }
                    echo '<span class="error-bar" >'.$errmsg.'</span>';
            ?>

            <!-- form footer -->
            <div class="form-footer">
                <span class= "status" >* required</span>
                <button type="submit" name="pri-grp-submit" class="btn" >Create Group</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
// set error message on the screen

function setErrMessage()
{
    if(isset($_GET['error'])){
        if($_GET['error'] == "emptyfields"){
            return "Fill all the fields";
        }
        else if($_GET['error'] == "wrongname"){
            return "Use ONLY letters and numbers for the group name";
        }
        else if($_GET['error'] == "namemax"){
            return "Maximum 30 characters are allowed for the group name";
        }
        else if($_GET['error'] == "biomax"){
            return "Maximum 100 characters are allowed for the description";
        }
        else if($_GET['error'] == "notavailable"){
            return "The requested group name is already available";
        }
    }
}
?>

<!-- script for inserting group icon -->
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

        //user has selected a photo
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

        //cropper plugin will destroy when the modal is closed
		cropper.destroy();
   		cropper = null;
	});

    //when the crop button is clicked
	$('#crop').click(function(){

        //getting selected image area
		canvas = cropper.getCroppedCanvas({
            //define image size
			width:400,
			height:400
		});

        //compress the image into better quality
		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);

			reader.onloadend = function(){

                var prePhoto = document.getElementById("prouppic").value;
                if(prePhoto == "groupchat-icon.png" || prePhoto == ""){
                    prePhoto = "000";
                }

				var base64data = reader.result;
				$.ajax({
					url:'profileUpload.php',
					method:'POST',
					data:{pubGIcon:base64data, pre:prePhoto},
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