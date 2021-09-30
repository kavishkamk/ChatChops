<?php
    // this class for get databse connection
    class DbConnection {

        private $dbservername = "localhost:3307";
        private $dBuserName = "root";
        private $dBpassword = "";
        private $dBname = "chatchops";

        protected function connect(){
            $conn = mysqli_connect($this->dbservername, $this->dBuserName, $this->dBpassword, $this->dBname);

            if($conn->connect_error){
                die("Connection failed: " . $conn->connect_error);
            }

            return $conn;
        }
    }