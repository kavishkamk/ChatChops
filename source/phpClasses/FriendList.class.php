<?php
    require_once "DbConnection.class.php";
    class FriendList extends DbConnection{

        // this method for get user list for send friend request
        // get output as user_id, profilePicLink, first_name, last_name
        // check alrady have a friend requst. if yes this query ignore that user
        // check friends table both side to check connections
        // check user block status. if user blocked this query ignore that user
        // check user is alray friend. if yes this query igore that user
        // if user accout deactive or deleted thid query ignore that user
        public function getFriendList($uid){
            $sqlQ = "SELECT users.user_id, users.profilePicLink, users.first_name, users.last_name FROM users WHERE users.user_id NOT IN
            (SELECT friends.to_user_id FROM ((friends INNER JOIN friend_req_friend_map ON friends.friend_id = friend_req_friend_map.friend_id)
            INNER JOIN friend_request ON friend_req_friend_map.req_id = friend_request.req_id AND
            (friend_request.friendStatus = ? OR friend_request.req_status = ? OR friend_request.block_status=?)) WHERE friends.from_user_id = ? UNION
            SELECT friends.from_user_id FROM ((friends INNER JOIN friend_req_friend_map ON friends.friend_id = friend_req_friend_map.friend_id)
            INNER JOIN friend_request ON friend_req_friend_map.req_id = friend_request.req_id AND
            (friend_request.friendStatus = ? OR friend_request.req_status = ? OR friend_request.block_status=?))
            WHERE friends.to_user_id = ?) AND NOT users.user_id = ? AND users.active_status = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else {
                $val1 = 1;
                mysqli_stmt_bind_param($stmt, "iiiiiiiiii", $val1, $val1, $val1, $uid, $val1, $val1, $val1, $uid, $uid, $val1);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $this->connclose($stmt, $conn);
                return $result;
                exit();
            }
        }

        // this for send comfirm friend requests
        public function addFriends($uid, $fid){

            $friendInsertId = $this->set_friends_table($uid, $fid);
            $detailsInsertId = $this->set_friend_request_table();
            if($friendInsertId == 0 || $detailsInsertId == 0){
                return 0;
                exit();
            }
            else{
                $res = $this->set_friend_req_friend_map_table($friendInsertId, $detailsInsertId);
                if($res == "1"){
                    return "1";
                    exit();
                }
                else{
                    return 0;
                    exit();
                }
            }
        }

        // this query used for get reserved friend requests from other users
        public function getfriendRequestList($uid){
            $sqlQ = "SELECT users.user_id, users.profilePicLink, users.first_name, users.last_name FROM users WHERE users.user_id IN(
                SELECT friends.from_user_id FROM 
                ((friends INNER JOIN friend_req_friend_map ON friends.friend_id = friend_req_friend_map.friend_id)
                INNER JOIN friend_request ON friend_req_friend_map.req_id = friend_request.req_id AND
                (friend_request.req_status = ? AND friend_request.block_status=?)) WHERE friends.to_user_id = ?)
                AND NOT users.user_id = ? AND users.active_status = ?;";
                $conn = $this->connect();
                $stmt = mysqli_stmt_init($conn);

                if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                    $this->connclose($stmt, $conn);
                    return "sqlerror";
                    exit();
                }
                else {
                    $val1 = 1; $val0 = 0;
                    mysqli_stmt_bind_param($stmt, "iiiii", $val1, $val0, $uid, $uid, $val1);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $this->connclose($stmt, $conn);
                    return $result;
                    exit();
                }
        }

        // this used for accept friend request. this set req_status to 0 and friendStatus as 1
        public function requestconfirm($userId, $friendId){

            $friendfieldid = $this->getFriendId($userId, $friendId);
            $reqid = $this->getRequestId($friendfieldid);
            $sqlQ = "UPDATE friend_request SET req_status = ?, friendStatus = ?, accept_time = ? WHERE req_id = ? ;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                $val0 = 0; $val1 = 1;
                $onlieTime = date("Y-n-d H:i:s");
                mysqli_stmt_bind_param($stmt, "iisi", $val0, $val1, $onlieTime, $reqid);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1"; // success
                exit();
            }
        }

        public function blockFriend($userId, $friendId){
            $friendfieldid = $this->getFriendId($userId, $friendId);
            $reqid = $this->getRequestId($friendfieldid);
            $sqlQ = "UPDATE friend_request SET block_status = ? WHERE req_id = ? ;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "0"; // sql error
                exit();
            }
            else{
                $val1 = 1;
                mysqli_stmt_bind_param($stmt, "ii", $val1, $reqid);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1"; // success
                exit();
            }
        }

        // this for get friend_id for friends table for given users and friend
        private function getFriendId($userId, $friendId){
            $sqlQ = "SELECT friend_id FROM friends WHERE from_user_id = ? AND  to_user_id = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ii", $friendId, $userId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    return $row['friend_id'];
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "usernotfund";
                    exit();
                }
            }
        }

        // this function for get req_id accourding to friend_id
        private function getRequestId($friendId){
            $sqlQ = "SELECT req_id FROM friend_req_friend_map WHERE friend_id = ?";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "i", $friendId);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if($row = mysqli_fetch_assoc($result)){
                    return $row['req_id'];
                }
                else{
                    $this->connclose($stmt, $conn);
                    return "usernotfund";
                    exit();
                }
            }
        }

        // this is used for set friend_req_friend_map table
        private function set_friend_req_friend_map_table($friendInsertId, $detailsInsertId){
            $sqlQ = "INSERT INTO friend_req_friend_map(req_id, friend_id) VALUES(?,?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ii", $detailsInsertId,  $friendInsertId);
                mysqli_stmt_execute($stmt);
                $this->connclose($stmt, $conn);
                return "1";
                exit();
            }
        }

        // this for send a friend request data and set it as friend request (set req_status = 1)
        private function set_friend_request_table(){
            $sqlQ = "INSERT INTO friend_request(req_status, block_status, req_time, accept_time) VALUES(?,?,?,?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                $val0 = 0; $val1 = 1;
                $onlieTime = date("Y-n-d H:i:s"); // acout log date and time
                mysqli_stmt_bind_param($stmt, "iiss", $val1, $val0, $onlieTime, $onlieTime);
                mysqli_stmt_execute($stmt);
                $detailsInsertId = mysqli_stmt_insert_id($stmt);
                $this->connclose($stmt, $conn);
                return $detailsInsertId;
                exit();
            }
        }

        // set friends request as sender and resever
        private function set_friends_table($uid, $fid){
            $sqlQ = "INSERT INTO friends(from_user_id, to_user_id) VALUES(?, ?);";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else{
                mysqli_stmt_bind_param($stmt, "ii", $uid, $fid);
                mysqli_stmt_execute($stmt);
                $friendInsertId = mysqli_stmt_insert_id($stmt);
                $this->connclose($stmt, $conn);
                return $friendInsertId;
                exit();
            }
        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }
    }