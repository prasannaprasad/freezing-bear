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
}