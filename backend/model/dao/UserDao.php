<?php
include('DBConnection.php');
include __SITE_PATH . '/model/entities/' . 'User.php';

class UserDao
{


    public function getUserById($user_id)
    {

        $db = DBConnection::getInstance()->getHandle();

        $query = "Select * from Usertest where fb_id='" . $user_id . "'";
        $result = $db->getSingleRecord($query);

        $user = new User($result["id"],$result["fb_id"],$result["first_name"],$result["last_name"],$result["create_time"],
                         $result["mod_time"],$result["fb_access_token"],$result["profile_pic"],$result["gender"],$result["hometown_name"],
                         $result["relationship_status"],$result["birthdate"]);

        return $user;
    }
}