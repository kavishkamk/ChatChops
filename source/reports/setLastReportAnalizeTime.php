<?php
    // this used for set last analize time
    if(isset($_POST['premsreq'])){
        require_once "../reportClasses/report.class.php";
        $repObj = new RepoerDetails();
        $ldate = $repObj->setLastAnalizeTime();
        unset($repObj);
        echo json_encode($ldate);
    }