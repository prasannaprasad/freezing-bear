<?php
include_once('DBConnection.php');
include_once __SITE_PATH . '/model/entities/' . 'UserFriend.php';

class UserFriendsDao
{

    public function addFriend($source_friend_id,$target_friend_id)
    {
        $db = DBConnection::getInstance()->getHandle();

        $prepared_query = " INSERT IGNORE into UserFriends(source_friend_id,target_friend_id,create_time)   values (?,?,?)";
        $create_time = date("Y-m-d H:i:s");

        $stmt = $db->getPreparedStatement($prepared_query);
        $stmt->bind_param("sss",$source_friend_id,$target_friend_id,$create_time);

        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $stmt->bind_param("sss",$target_friend_id,$source_friend_id,$create_time);

        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $id = $stmt->insert_id;
        $stmt->close();

        error_log("Two way Friend link created between $source_friend_id and $target_friend_id ");
        return new UserFriend($id,$source_friend_id,$target_friend_id,$create_time);
    }

    public function getUserFriends($user_id)
    {
        $db = DBConnection::getInstance()->getHandle();

        $query = "Select fb_id,name,email,profile_pic from Usertest,UserFriends where source_friend_id ='" . $user_id . "' AND target_friend_id = fb_id ";
        $result = $db->getRecords($query);

        $mini_users = array();
        foreach($result as $r)
        {
            array_push($mini_users,$r);
        }

        return $mini_users;
    }
}