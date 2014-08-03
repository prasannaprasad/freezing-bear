<?php
include_once('DBConnection.php');
include_once __SITE_PATH . '/model/entities/' . 'StampCloud.php';

class StampCloudDao
{


    public function getUserStampCloud($fb_user_id)
    {

        $db = DBConnection::getInstance()->getHandle();

        $query = "Select * from StampCloud where fb_user_id='" . $fb_user_id . "'";
        $result_set = $db->getRecords($query);

        error_log("Got " . count($result_set) . " results");
        $stamp_cloud = new StampCloud($fb_user_id);

        foreach($result_set as $result)
        {
            $stamp_cloud->addNounCount($result["noun_name"],$result["count"]);
        }

        return $stamp_cloud;
    }

    public function updateStampCount($fb_user_id,$noun_name)
    {
        $db = DBConnection::getInstance()->getHandle();
        $prepared_query = " INSERT  into StampCloud(fb_user_id,noun_name,count) values (?,?,?) ON DUPLICATE KEY UPDATE count=count+1;";

        $stmt = $db->getPreparedStatement($prepared_query);
        $count = 1;
        $stmt->bind_param("ssi",$fb_user_id,$noun_name,$count);

        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $stmt->close();
        return $status;
    }
}