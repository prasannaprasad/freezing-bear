<?php

class User
{
    public $id;
    public $fb_id;
    public $last_name;
    public $first_name;
    public $create_time;
    public $mod_time;
    public $fb_access_token;
    public $profile_pic;
    public $gender;
    public $hometown_name;
    public $relationship_status;
    public $birthdate;

    public function __construct($id,$fb_id,$first_name,$last_name,$create_time,$mod_time,$fb_access_token, $profile_pic,
                                $gender,$hometown_name,$relationship_status,$birthdate)
    {
        $this->id = $id;
        $this->fb_id = $fb_id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->create_time = $create_time;
        $this->mod_time = $mod_time;
        $this->fb_access_token = $fb_access_token;
        $this->profile_pic = $profile_pic;
        $this->gender = $gender;
        $this->hometown_name = $hometown_name;
        $this->relationship_status = $relationship_status;
        $this->birthdate = $birthdate;
    }


    public function getJSON()
    {
        return json_encode($this);
    }
}

