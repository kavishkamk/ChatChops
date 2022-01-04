<?php
    // this class for get databse connection
    class DbConnection {

        private $dbservername = "remotemysql.com:3306";
        private $dBuserName = "qi68lgkqMS";
        private $dBpassword = "U5ahDqIXu6";
        private $dBname = "qi68lgkqMS";

        protected function connect(){
            $conn = mysqli_connect($this->dbservername, $this->dBuserName, $this->dBpassword, $this->dBname);

            if($conn->connect_error){
                die("Connection failed: " . $conn->connect_error);
            }

            return $conn;
        }
    }
