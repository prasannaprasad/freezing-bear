<?php
include_once('DBConnection.php');
include_once __SITE_PATH . '/model/entities/' . 'StampCloud.php';
include_once __SITE_PATH . '/model/entities/' . 'MiniUser.php';


class StampCloudDao
{


    public function getUserStampCloud($fb_user_id,$offset = 0 , $limit = 20,$fetch_users = false)
    {

        $db = DBConnection::getInstance()->getHandle();

        $query = "Select * from StampCloud where fb_user_id='" . $fb_user_id . "' ORDER by count desc limit $offset,$limit";
        $result_set = $db->getRecords($query);

        error_log("Got " . count($result_set) . " results");
        $stamp_cloud = new StampCloud($fb_user_id);


        foreach($result_set as $result)
        {
            $stamp_key["count"] = $result["count"];
            if($fetch_users)
                 $stamp_key["users"] = $this->getUsersforStamp($fb_user_id,$result["noun_name"]);
            else
                $stamp_key["users"] = array();

            $stamp_cloud->addNoun($result["noun_name"],$stamp_key);
        }

        return $stamp_cloud;
    }

    public function getUsersForStamp($fb_user_id,$noun_name)
    {
        $db = DBConnection::getInstance()->getHandle();

        $noun_name = addslashes($noun_name);
        $query = "select Usertest.fb_id, Usertest.name,Usertest.profile_pic,Usertest.email from Usertest where fb_id in
                  (Select by_user_id from Stamps where to_user_id = '" . $fb_user_id . "' and noun_name = '" . $noun_name . "')";
        $result_set = $db->getRecords($query);
        $mini_users = array();
        foreach($result_set as $result)
        {
            $mini_user = new MiniUser($result["fb_id"],$result["profile_pic"],$result["email"],$result["name"]);
            error_log(print_r($mini_user,1));
            array_push($mini_users,$mini_user);
        }
        return $mini_users;

    }

    public function updateStampCount($fb_user_id,$noun_name)
    {
        $db = DBConnection::getInstance()->getHandle();
        $prepared_query = " INSERT  into StampCloud(fb_user_id,noun_name,count) values (?,?,?) ON DUPLICATE KEY UPDATE count=count+1;";

        $stmt = $db->getPreparedStatement($prepared_query);
        $count = 1;
        $stmt->bind_param("ssi",$fb_user_id,$noun_name,$count);

        error_log("Executing $prepared_query with params $fb_user_id,$noun_name,$count");

        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $stmt->close();
        return $status;
    }
}