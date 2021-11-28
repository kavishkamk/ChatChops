<?php

session_start();

require "../phpClasses/privateGroup.class.php";

if(isset($_POST['pri-grp-submit'])){

    $groupname = test_input($_POST['groupname']);
    $groupbio = test_input($_POST['groupbio']);
    $icon = test_input($_POST['foo']);

    $obj = new privateGroup($groupname, $groupbio);

    $validate = $obj->validate();

    if($validate == 0){
        //valid inputs
        require_once "privateGroupDbHandle.class.php";

        $pubObj = new privateGroupDbHandle();
        $checkName = $pubObj-> checkUniqueName($groupname);

        //name is valid
        if($checkName == 0){
            $create = $pubObj-> createPrivateGroup($groupname, $groupbio, $icon, $_SESSION['userid']);

            if($create == "ok"){
                //get the full dataset of that group
                $data = $pubObj-> full_group_dataset();
                
                $group_id = $data['group_id'];
                $group_name = $data['group_name'];
                $created = $data['created'];
                $bio = $data['bio'] ;
                $group_icon = $data['group_icon'];
                $created_user_id = $data['created_user_id'];

                $userid = $_SESSION['userid'];
                echo "<form method='post' name = 'formAlert' action= 'http://localhost/chatchops/source/private-groups/create-private-group.php'>
                        <input type='hidden' name='status' value = 'ok' />  
                        <input type='hidden' name='group_id' value = $group_id /> 
                        <input type='hidden' name='groupname' value = $group_name /> 
                        <input type='hidden' name='created_on' value = $created /> 
                        <input type='hidden' name='bio' value = $bio /> 
                        <input type='hidden' name='group_icon' value = $group_icon /> 
                        <input type='hidden' name='admin_userid' value =  $created_user_id /> 

                    </form>";
                
                echo '<script>
                        document.formAlert.submit();
                    </script>';
                
            }
            else{
                echo "<form method='post' name = 'formAlert' action= 'http://localhost/chatchops/source/insideUI/chatChops.php'>
                    <input type='hidden' name='status' value = 'wrong' />   
                </form>";
            
                echo '<script>
                        document.formAlert.submit();
                    </script>';
            }
        }else{
            header("Location:create-private-group.php?error=notavailable&groupname=$groupname&groupbio=$groupbio&picn=$icon");
        }
        
        //send to db and check errors
        //if all good, redirect to another page to select the members from his friends list

        unset($pubObj);
        exit();
    }

    else if($validate == 1){
        header("Location:create-private-group.php?error=emptyfields&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else if($validate == 2){
        header("Location:create-private-group.php?error=wrongname&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else if($validate == 4){
        header("Location:create-private-group.php?error=namemax&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else if($validate == 5){
        header("Location:create-private-group.php?error=biomax&groupname=$groupname&groupbio=$groupbio&picn=$icon");
    }
    else{
        
        echo "<form method='post' name = 'formAlert' action= 'http://localhost/chatchops/source/insideUI/chatChops.php'>
                <input type='hidden' name='status' value = 'wrong' />   
            </form>";
    
        echo '<script>
                document.formAlert.submit();
            </script>';
    
    }

    unset($obj);
    exit();
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}