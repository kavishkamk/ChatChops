<?php
//window popup to create a new public chat room
?>

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

        <link rel="stylesheet" href="../css/create-pub-room.css">
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
        <style>
        body {
            font-family: 'Roboto';
        }
        </style>
    </head>
    <body>
    
    <script>
        //disable right click handling inside this popup-window
        window.addEventListener('contextmenu', event => event.preventDefault());
    </script>

    <div id= "box" style= "position: center;">
        <!-- public group creating form -->
        <form class= "pub-room-form" action="include/create-pub-room.inc.php" method="post" enctype="multipart/form-data">
            <div class="form-header">
                <h1>Create a Public Chat Room</h1>
            </div>
            <?php 
                // set profile picture paramiters
                if(isset($_GET['picn'])){
                    echo '<input type="hidden" name="foo" value="'.$_GET['picn'].'" id="prouppic">';
                }
                else{
                    echo '<input type="hidden" name="foo" value="groupchat-icon.png" id="prouppic">';
                }
            ?>
            <div class="form-body" style= "position: center;">
                <!-- for group name-->
                <div>
                    <label for="groupname" class="label-title" >Chat Room Name *</label><br>
                    <?php 
                        if(isset($_GET['groupname'])){
                            echo '<input type="text" name="groupname" placeholder="enter chat room name" value="'.$_GET['groupname'].'" class="form-input" required>';
                        }
                        else{
                            echo '<input type="text" name="groupname" placeholder="enter chat room name" class="form-input" required>';
                        }
                    ?>
                </div>

                <!-- this div is to handle group icons -->
                <div>
                    <div class="image_area">
                        <label for="upload_image">
                        <?php
                        if(isset($_GET['picn'])){
                            echo '<img src="../group-icons/'.$_GET['picn'].'" id="uploaded_image" class="img-responsive img-circle" />';
                        }
                        else {
                            echo '<img src="../group-icons/groupchat-icon.png" id="uploaded_image" class="img-responsive img-circle" />';
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
            </div>
            
            <!-- form footer -->
            <div class="form-footer">
                <span class= "status" >* required</span>

                <?php
                    $errmsg = "";

                    if(isset($_GET['signerror'])){
                        $errmsg = setErrMessage();
                    }

                    echo '<span class="error-bar" >'.$errmsg.'</span>';
                ?>
                <button type="submit" name="pub-room-submit" class="btn" >Create Room</button>
            </div>
        </form>
    </div>
    </body>
</html>

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

                //var prePhoto = document.getElementById("prouppic").value;
                

				var base64data = reader.result;
				$.ajax({
					url:'../profileUpload.php',
					method:'POST',
					data:{pubGIcon:base64data},
					success:function(data)
					{
						$modal.modal('hide');
						$('#uploaded_image').attr('src', "../" + data);
                        //document.getElementById("prouppic").value = data.substr(12);
					}
				});
			};
		});
	});
	
});
</script>