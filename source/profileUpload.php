<?php

session_start();
//upload.php to upload poto after croping

if(isset($_POST['image']))
{
	$data = $_POST['image'];
	$prePic = $_POST['pre'];

	if($prePic != "000"){
		if(file_exists("profile-pic/".$prePic)){
			unlink("profile-pic/".$prePic);
		}
	}

	$image_array_1 = explode(";", $data);

	$image_array_2 = explode(",", $image_array_1[1]);

	$data = base64_decode($image_array_2[1]);

	$image_name = 'profile-pic/' . time() . '.png';

	file_put_contents($image_name, $data);

	echo $image_name;
}


//public chat room icons upload
if(isset($_POST['pubGIcon']))
{
	$data = $_POST['pubGIcon'];
	$prePic = $_POST['pre'];

	if($prePic != "000"){
		if(file_exists("group-icons/".$prePic)){
			unlink("group-icons/".$prePic);
		}
	}

	$image_array_1 = explode(";", $data);

	$image_array_2 = explode(",", $image_array_1[1]);

	$data = base64_decode($image_array_2[1]);

	$image_name = 'group-icons/' . time() . '.png';

	file_put_contents($image_name, $data);

	echo $image_name;
}

//private group icons upload
if(isset($_POST['priGIcon']))
{
	$data = $_POST['priGIcon'];
	$prePic = $_POST['pre'];

	if($prePic != "000"){
		if(file_exists("private-group-icons/".$prePic)){
			unlink("private-group-icons/".$prePic);
		}
	}

	$image_array_1 = explode(";", $data);

	$image_array_2 = explode(",", $image_array_1[1]);

	$data = base64_decode($image_array_2[1]);

	$image_name = 'private-group-icons/' . time() . '.png';

	file_put_contents($image_name, $data);

	echo $image_name;
}

?>