<?php

class User
{
    public $id;
    public $user_id;
    public $name;
    public $create_time;
    public $mod_time;
    public $access_token;
    public $profile_pic;

    public function __construct($id,$user_id,$name,$create_time,$mod_time,$access_token, $profile_pic)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->name = $name;
        $this->create_time = $create_time;
        $this->mod_time = $mod_time;
        $this->access_token = $access_token;
        $this->profile_pic = $profile_pic;
    }


    public function getJSON()
    {
        return json_encode($this);
    }
}

