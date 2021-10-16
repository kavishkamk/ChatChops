<?php
require_once "DbConnection.class.php";

    class PrivateChatHandle extends DbConnection{

        public function getFriendList($uid){
            $sqlQ = "SELECT users.user_id, users.profilePicLink, users.first_name, users.last_name
            FROM users WHERE users.user_id IN
            (SELECT friends.to_user_id FROM ((friends INNER JOIN friend_req_friend_map ON friends.friend_id = friend_req_friend_map.friend_id)
            INNER JOIN friend_request ON friend_req_friend_map.req_id = friend_request.req_id AND
            (friend_request.friendStatus = ? AND friend_request.block_status=?)) WHERE friends.from_user_id = ? UNION
            SELECT friends.from_user_id FROM ((friends INNER JOIN friend_req_friend_map ON friends.friend_id = friend_req_friend_map.friend_id)
            INNER JOIN friend_request ON friend_req_friend_map.req_id = friend_request.req_id AND
            (friend_request.friendStatus = ? AND friend_request.block_status=?))
            WHERE friends.to_user_id = ?) AND NOT users.user_id = ? AND users.active_status = ?;";
            $conn = $this->connect();
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sqlQ)){
                $this->connclose($stmt, $conn);
                return "sqlerror";
                exit();
            }
            else {
                $datas = array();
                $val1 = 1; $val0 = 0;
                mysqli_stmt_bind_param($stmt, "iiiiiiii", $val1, $val0, $uid, $val1, $val0, $uid, $uid, $val1);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($result)){
                    $datas[] = $row;
                }
                $this->connclose($stmt, $conn);
                return $datas;
                exit();
            }

        }

        private function connclose($stmt, $conn){
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        }

    }