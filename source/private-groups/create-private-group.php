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
    <link rel="stylesheet" href="css/chatUI.css">

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
                                echo '<img src="private-group-icons/'.$_GET['picn'].'" id="uploaded_image" class="img-responsive img-circle" />';
                            }
                            else {
                                echo '<img src="private-group-icons/groupchat-icon.png" id="uploaded_image" class="img-responsive img-circle" />';
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
    
    <form class = "hidden-form" method='post' name = 'prigCreate' action= 'http://localhost/chatchops/source/insideUI/chatChops.php'>
        <input type="hidden" id="create-ok" name="create_ok" value=""> <!-- group created, ready to add members -->
        <input type="hidden" id="group-id" name="group-id" value="">
        <input type="hidden" id="group-name" name="group-name" value=""> <!-- group name -->
        <input type="hidden" id="created-on" name="created-on" value="">
        <input type="hidden" id="bio" name="bio" value="">
        <input type="hidden" id="group-icon" name="group-icon" value="">
        <input type="hidden" id="admin-userid" name="admin-userid" value=""> <!-- admin's user id -->
        <input type="hidden" id="member-userids" name="member-userids" value=""> <!-- selected user ids -->

    </form>

    <!-- member adding popup -->
    <div id="member-list" class="modals" style= "display:none;">
        <div class="modal-content1">
            <p class = "modal-topic1" id= "mem-count-show">Select Members</p><hr class= "hrr">

            <div class= "mem-list" id="mem-list" style="max-height: 300px; overflow-y: scroll;">
                <!-- sample member info -->
                <!--
                <div class= "mem-item">
                    <div class="col11">
                        <img src= 'private-group-icons/groupchat-icon.png' width='60'height='60' class='img-circle mem-icon'>
                    </div>
                    <div class="col22">
                        <div class= "mem-fullname">rashmi wijesekara</div>
                        <div class= "mem-username">#rashmi</div>
                    </div>
                    <div class= "col33">
                        <div id= "add-btn" class= "col33-1">Add</div>
                        <div id= "remove-btn" class= "col33-2">Remove</div>
                    </div>
                </div>
                <hr class="hrr"> 
                -->
                
            </div>

            <!-- buttons at the bottom -->
            <div class= "button-section">
                <div id= "cancel-btn" class= "col1" onclick= "cancel_btn()">Cancel</div>
                <div id= "members-save-btn" class= "col2" onclick= "members_save()" style= "visibility:hidden;">Add Members</div>
            </div>

        </div>
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

if(isset($_POST['status'])){
    if($_POST['status'] == 'ok'){
        $id = $_POST['group_id'];
        $name = $_POST['groupname'];
        $on = $_POST['created_on'];
        $bio = $_POST['bio'];
        $icon = $_POST['group_icon'];
        $admin = $_POST['admin_userid'];

        echo "<script>
                document.getElementById('create-ok').value = 'ok';
                document.getElementById('group-id').value = $id;
                document.getElementById('group-name').value = '$name';
                document.getElementById('created-on').value = '$on';
                document.getElementById('bio').value = '$bio';
                document.getElementById('group-icon').value = '$icon';
                document.getElementById('admin-userid').value = $admin;
            </script>";
    }
    
    unset($_POST['status']);
}


?>

<!-- script for inserting group icon -->
<script>

$(document).ready(function(){

    var modals = document.getElementById("member-list");

    //this user has just created a new private group
    if(document.getElementById("create-ok").value == 'ok'){
        // add members from his friends list

        modals.style.display = "block";
        document.getElementById("members-save-btn").style.visibility = "visible";
        set_friend_list();
    }

    //set friend list in the member selecting popup
    function set_friend_list()
    {
        document.getElementById("mem-list").innerHTML = "";
        var userid = document.getElementById("admin-userid").value;

        $.ajax({
            method: "POST",
            url: "private-groups/ajax-handle.php",
            data: {
                set_friend_list: "set",
                userid: userid
            },
            success: function(result){
                var obj = JSON.parse(result);

                if(obj == "sqlerror"){
                    return;
                }else if(obj == ""){
                    document.getElementById("members-save-btn").style.visibility = "hidden";
                    return;
                }
                document.getElementById("cancel-btn").style.visibility = "hidden";

                var i=0;
                while(obj[i])
                {
                    console.log(obj[i]);
                    var userid = obj[i].user_id;
                    var addid = "add"+ userid;
                    var removeid = "remove"+ userid;

                    var friend = `<div class= "mem-item">
                                    <div class="col11">
                                        <img src= 'profile-pic/`+obj[i].profilePicLink+`' width='60'height='60' class='img-circle mem-icon'>
                                    </div>
                                    <div class="col22">
                                        <div class= "mem-fullname">`+obj[i].first_name+ ' '+ obj[i].last_name +`</div>
                                        <div class= "mem-username">#`+obj[i].username + `</div>
                                    </div>
                                    <div class= "col33">
                                        <div class= "add-btn" id= "`+addid +`" onclick="member_added(`+userid+`)" class= "col33-1" style= "visibility:visible;">Add</div>
                                        <div class= "remove-btn" id= "`+removeid+`" onclick="member_removed(`+userid+`)" class= "col33-2" style= "visibility:hidden;">Remove</div>
                                    </div>
                                </div>
                                <hr class="hrr">`;
                    
                    $("#mem-list").append(friend);
                    i++;
                }
            }
        });
    }
    
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
					data:{priGIcon:base64data, pre:prePhoto},
					success:function(data)
					{
						$modal.modal('hide');
						$('#uploaded_image').attr('src', data);
                        document.getElementById("prouppic").value = data.substr(20);
					}
				});
			};
		});
	});
	
})

//when click on a add button of a user
function member_added(userid)
{
    var removebtn = "remove"+ userid;
    var addbtn = "add"+ userid;

    var str = document.getElementById("member-userids").value;
    
    var add;
    if(str == ""){
        add = userid;
    }else{
        add = ","+ userid;
    }

    var newstr = str + add;
    document.getElementById("member-userids").value = newstr;

    console.log(document.getElementById("member-userids").value);

    document.getElementById(removebtn).style.visibility = 'visible';
    document.getElementById(addbtn).style.visibility = 'hidden';

}

//when click on a remove button of a user
function member_removed(userid)
{
    var removebtn = "remove"+ userid;
    var addbtn = "add"+ userid;

    var str = document.getElementById("member-userids").value;
    var ids = str.split(",");

    var newstr ="";
    var str1;
    var i=0;
    while(ids[i]){
        var id = ids[i];
        
        if(id != userid){
            if(newstr == "")
                str1 = id;
            else
                str1 = ","+ id;

            i++;
        }else{
            i++;
            continue;
        }
        newstr = newstr+ str1;
        
    }
    document.getElementById("member-userids").value = newstr;

    console.log(document.getElementById("member-userids").value);

    document.getElementById(removebtn).style.visibility = 'hidden';
    document.getElementById(addbtn).style.visibility = 'visible';
}

// when clicked on cancel button
function cancel_btn()
{
    if(document.getElementById("members-save-btn").style.visibility == "hidden"){
        members_save();
    }
}

//when clicked on add members button
function members_save()
{   
    /********************************* */
    /** send the member list of the new group to the DB
        if successful ==> submit the hidden form to the main UI

        else ==> create the member list empty
                send the hidden form to the main UI with a notif saying 
                    (Add members to the 'groupname' group again)*/
    var memlist = document.getElementById("member-userids").value;
    var groupid = document.getElementById('group-id').value;

    var temp = new Array();

    if(memlist == ""){
        document.prigCreate.submit();
        return;
    }else{
        temp = memlist.split(",");

        for (a in temp){ 
            //store the userids as base 10 integers instead of strings
            temp[a] = parseInt(temp[a], 10); 
        }
    }

    $.ajax({
        method: "POST",
        url: "private-groups/ajax-handle.php",
        data: {
            members_save: "set",
            group_id: groupid,
            memlist: temp
        },
        success: function(result){
            var obj = JSON.parse(result);
            console.log(obj);

            if(obj == "ok"){
                document.prigCreate.submit();
            }
        }
    });

}
</script>