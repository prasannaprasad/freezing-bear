<?php


include_once __SITE_PATH . '/model/dao/' . 'DBConnection.php';
include_once __SITE_PATH . '/model/entities/' . 'User.php';

class UserDao
{


    public function getUserById($user_id)
    {

        $db = DBConnection::getInstance()->getHandle();

        $query = "Select * from Usertest where fb_id='" . $user_id . "'";
        $result = $db->getSingleRecord($query);


        $user = new User($result["id"],$result["fb_id"],$result["first_name"],$result["last_name"],$result["create_time"],
                        $result["profile_pic"],$result["gender"],$result["hometown_name"],$result["relationship_status"],$result["birthdate"],
                        $result["email"],$result["name"],$result["location"],$result["timezone"]);

        return $user;
    }

    public function addUser($fb_id,$first_name,$last_name,$profile_pic,
                         $gender,$hometown_name,$relationship_status,$birthdate,$email,$name,$location,$timezone)
    {
        $db = DBConnection::getInstance()->getHandle();

        $prepared_query = " INSERT  into Usertest(fb_id,first_name,last_name,name,email,location,gender,relationship_status,
                            timezone,create_time,birthdate,profile_pic) values (?,?,?,?,?,?,?,?,?,?,?,?)";
        $create_time = date("Y-m-d H:i:s");

        $stmt = $db->getPreparedStatement($prepared_query);
        $stmt->bind_param("ssssssssssss",$fb_id,$first_name,$last_name,$name,$email,$location,$gender,$relationship_status,$timezone,
                                         $create_time,$birthdate,$profile_pic);

        error_log("Executing $prepared_query with params $fb_id,$first_name,$last_name,$name,$email,$location,$gender,$relationship_status,$timezone,
                                         $create_time,$birthdate,$profile_pic");
        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $id = $stmt->insert_id;
        $stmt->close();

        error_log("User $name inserted with $id and status $status");
        return new User($id,$fb_id,$first_name,$last_name,$create_time,$profile_pic,$gender,$hometown_name,$relationship_status,
                        $birthdate,$email,$name,$location,$timezone);
    }

    public function fillMiniUserMap($user_ids)
    {
        $db = DBConnection::getInstance()->getHandle();
        $unique_user_ids = array_unique($user_ids);

        $in_clause = "('" . implode("','", $unique_user_ids) . "')";

        error_log("Fetching user $in_clause");

        $query = "SELECT fb_id, name,profile_pic,email from Usertest where fb_id in $in_clause";

        $results = $db->getRecords($query);

        $userMap = '';
        foreach($results as $r)
        {
            $userMap[$r["fb_id"]] = new MiniUser($r["fb_id"],$r["profile_pic"],$r["email"],$r["name"]);
        }

        error_log(print_r($userMap,1));
        return $userMap;

    }
}