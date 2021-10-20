<?php

class PublicRoom{

    private $groupname;
    private $groupbio;
    
    public function __construct($groupname, $groupbio)
    {
        $this->groupname = $groupname;
        $this->groupbio = $groupbio;
    }

    //validate user input
    public function validate()
    {
        if(empty($this->groupname) || empty($this->groupbio)){
            return 1;
        }
        else if(!preg_match("/^[a-zA-Z0-9_ -]*$/", $this->groupname)){
            return 2;
        }
        else if(strlen($this->groupname) > 30){
            return 4;
        }
        else if(strlen($this->groupbio) > 100){
            return 5;
        }
        else{
            return 0;
        }
    }
}