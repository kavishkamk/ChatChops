<?php

session_start();

// change profile details

if(isset($_POST['image']))
{
	$data = $_POST['image'];

	$image_array_1 = explode(";", $data);

	$image_array_2 = explode(",", $image_array_1[1]);

	$data = base64_decode($image_array_2[1]);

	$image_name = 'profile-pic/' . $_SESSION['uname'] . '.png';

	$_SESSION['profileLink'] = $_SESSION['uname'].'.png';

	require_once "phpClasses/ProfileEdit.class.php";
	$proObj = new ProfileEdit();
	$proObj->changeProfileLink('' . $_SESSION['uname'] . '.png', $_SESSION['userid']);
	unset($proObj);

	file_put_contents($image_name, $data);

	echo $image_name;
}

?>