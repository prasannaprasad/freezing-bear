<?php
include_once('DBConnection.php');
include_once __SITE_PATH . '/model/entities/' . 'Stamp.php';
include_once __SITE_PATH . '/application/WebServiceException.php';
include_once('VerbDao.php');
include_once('NounDao.php');
include_once('StampCloudDao.php');


class StampDao
{
    public function addStamp($by_user_id,$to_user_id,$verb_name,$noun_name) //TODO take create_time as optional arg
    {
        $db = DBConnection::getInstance()->getHandle();

        $verbDao = new VerbDao();
        $verb_id  = $verbDao->addVerb($verb_name, true)->id;

        $nounDao = new NounDao();
        $noun_id  = $nounDao->addNoun($noun_name, true)->id;


        $prepared_query = " INSERT  into Stamps(by_user_id,to_user_id,verb_id,verb_name,noun_id,noun_name,create_time)
                            values (?,?,?,?,?,?,?)";
        $create_time = date("Y-m-d H:i:s");

        $stmt = $db->getPreparedStatement($prepared_query);
        $stmt->bind_param("ssisiss",$by_user_id,$to_user_id,$verb_id,$verb_name,$noun_id,$noun_name,$create_time);

        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $id = $stmt->insert_id;
        $stmt->close();

        $stampcloudDao = new StampCloudDao();
        $stampcloudDao->updateStampCount($to_user_id,$noun_name);

        error_log("Stamp  inserted with $id and status $status");
        return new Stamp($id,$by_user_id,$to_user_id,$noun_id,$noun_name,$verb_id,$verb_name,$create_time);

    }
}