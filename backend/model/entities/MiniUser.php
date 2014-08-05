<?php
include_once('BaseContainer.php');

class MiniUser extends  BaseContainer
{
    public $fb_id;
    public $profile_pic;
    public $email;
    public $name;


    public function __construct($fb_id, $profile_pic,$email,$name)
    {
        $this->fb_id = $fb_id;
        if(!isset($profile_pic)) $this->profile_pic = "";
        else $this->profile_pic = $profile_pic;
        if(!isset($name)) $this->name = "";
        else $this->name = $name;
        if(!isset($email)) $this->email = "";
        else $this->email = $email;
    }

}

