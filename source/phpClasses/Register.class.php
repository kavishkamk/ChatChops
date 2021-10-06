<?php

    // this class can use to admins and users
    class Register{

        private $firstName;
        private $lastName;
        private $mail;
        private $username;
        private $pwd;
        private $cpwd;

        public function __construct($firstName, $lastName, $mail, $username, $pwd, $cpwd){
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->mail = $mail;
            $this->username = $username;
            $this->pwd = $pwd;
            $this->cpwd = $cpwd;
        }

        // check user input with given condition to user registration
        public function checkRegInput(){
            if(empty($this->firstName) || empty($this->lastName) || empty($this->mail) || empty($this->username) || empty($this->pwd) || empty($this->cpwd)) {
                return 1;
            }
            else if(!filter_var($this->mail, FILTER_VALIDATE_EMAIL)){
                return 2;
            }
            else if(!preg_match("/^[a-zA-Z]*$/", $this->firstName)){
                return 3;
            }
            else if(!preg_match("/^[a-zA-Z]*$/", $this->lastName)){
                return 4;
            }
            else if(!preg_match("/^[a-zA-Z0-9]*$/", $this->username)){
                return 5;
            }
            else if($this->pwd != $this->cpwd){
                return 6;
            }
            else if(strlen($this->firstName) > 30){
                return 7;
            }
            else if(strlen($this->lastName) > 30){
                return 8;
            }
            else if(strlen($this->username) > 50){
                return 9;
            }
            else{
                return 0;
            }
        }

    }