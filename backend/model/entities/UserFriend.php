<?php
include_once('BaseContainer.php');

class UserFriend extends  BaseContainer
{
    public $id;
    public $source_friend_id;
    public $create_time;
    public $target_friend_id;


    public function __construct($id,$source_friend_id,$target_friend_id,$create_time)
    {
        $this->id = $id;
        $this->source_friend_id = $source_friend_id;
        $this->target_friend_id = $target_friend_id;
        $this->create_time = $create_time;
    }

}

