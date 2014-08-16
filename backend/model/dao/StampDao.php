<?php
include_once('DBConnection.php');
include_once __SITE_PATH . '/model/entities/' . 'Stamp.php';
include_once __SITE_PATH . '/application/WebServiceException.php';
include_once('VerbDao.php');
include_once('NounDao.php');
include_once('StampCloudDao.php');


class StampDao
{
    public function addStamp($by_user_id,$to_user_id,$verb_name,$verb_form_name,$noun_name, $create_time = "")
    {
        $db = DBConnection::getInstance()->getHandle();

        $verbDao = new VerbDao();
        $verb_id  = $verbDao->addVerb($verb_name,$verb_form_name, true)->id;

        $nounDao = new NounDao();
        $noun_id  = $nounDao->addNoun($noun_name, true)->id;


        $prepared_query = " INSERT  into Stamps(by_user_id,to_user_id,verb_id,verb_form_name,noun_id,noun_name,create_time)
                            values (?,?,?,?,?,?,?)";
        if($create_time == ""){
            $create_time = date("Y-m-d H:i:s");
        }else{
            $create_time = date("Y-m-d H:i:s",strtotime($create_time));
            
        }

        $stmt = $db->getPreparedStatement($prepared_query);
        $stmt->bind_param("ssisiss",$by_user_id,$to_user_id,$verb_id,$verb_form_name,$noun_id,$noun_name,$create_time);

        error_log("Executing $prepared_query with params $by_user_id,$to_user_id,$verb_id,$verb_form_name,$noun_id,$noun_name,$create_time");
        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $id = $stmt->insert_id;
        $stmt->close();

        $stampcloudDao = new StampCloudDao();
        $stampcloudDao->updateStampCount($to_user_id,$noun_name);

        error_log("Stamp  inserted with $id and status $status");
        return new Stamp($id,$by_user_id,$to_user_id,$noun_id,$noun_name,$verb_id,$verb_form_name,$create_time);

    }

    public function getUserProfileFeed($user_id,$offset,$limit)
    {
        $db = DBConnection::getInstance()->getHandle();
        $query = "SELECT * from Stamps where by_user_id = '" . $user_id . "' OR to_user_id = '" . $user_id . "' order by create_time desc LIMIT $offset,$limit  ";

        $result = $db->getRecords($query);
        $stamps = array();

        $user_ids = array();
        foreach($result as $r)
        {

            array_push($user_ids,$r["by_user_id"]);
            array_push($user_ids,$r["to_user_id"]);

            $userDao = new UserDao();
            $mini_user_map = $userDao->fillMiniUserMap($user_ids);

            $by_mini_user = $mini_user_map[$r["by_user_id"]];
            $to_mini_user = $mini_user_map[$r["to_user_id"]];

            $stamp = new Stamp($r["id"],$by_mini_user,$to_mini_user,$r["noun_id"],$r["noun_name"],$r["verb_id"],$r["verb_form_name"],
                $r["create_time"]);
            array_push($stamps,$stamp);
        }

        return $stamps;
    }

    public function getUserHomeFeed($user_id,$offset,$limit)
    {
        $db = DBConnection::getInstance()->getHandle();

        $userFriendsDao = new UserFriendsDao();
        $mini_friends = $userFriendsDao->getUserFriends($user_id);
        $user_friend_ids = array($user_id);

        $userDao = new UserDao();
        $user = $userDao->getUserById($user_id);

        $mini_user_map[$user_id] = new MiniUser($user->fb_id,$user->profile_pic,$user->email,$user->name);
        $mini_user_map["facebook"] = new MiniUser("facebook","","","facebook");

        foreach($mini_friends as $friend)
        {
            array_push($user_friend_ids, $friend->fb_id);
            $mini_user_map[$friend->fb_id] = $friend;
        }

        $unique_user_ids = array_unique($user_friend_ids);
        $in_clause = "('" . implode("','", $unique_user_ids) . "')";

        $query = "SELECT * from Stamps where by_user_id IN $in_clause OR to_user_id IN  $in_clause  order by create_time desc LIMIT $offset,$limit  ";

        $result = $db->getRecords($query);
        $stamps = array();

        foreach($result as $r)
        {

            $by_mini_user = $mini_user_map[$r["by_user_id"]];
            $to_mini_user = $mini_user_map[$r["to_user_id"]];

            $stamp = new Stamp($r["id"],$by_mini_user,$to_mini_user,$r["noun_id"],$r["noun_name"],$r["verb_id"],$r["verb_form_name"],
                $r["create_time"]);
            array_push($stamps,$stamp);
        }

        return $stamps;
    }
}