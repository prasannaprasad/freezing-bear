<?php
include_once('DBConnection.php');
include_once __SITE_PATH . '/model/entities/' . 'Verb.php';
include_once __SITE_PATH . '/application/WebServiceException.php';


class VerbDao
{

    public function getVerbById($verb_id)
    {
        $db = DBConnection::getInstance()->getHandle();
        $query = "Select * from Verbs where id='" . $verb_id . "'";
        $result = $db->getSingleRecord($query);
        return new Verb($result["id"],$result["name"], $result["create_time"]);
    }


    public function getVerbByName($verb_name)
    {
        $db = DBConnection::getInstance()->getHandle();
        $query = "Select * from Verbs where name='" . $verb_name . "'";
        $result = $db->getSingleRecord($query);
        return new Verb($result["id"],$result["name"], $result["create_time"]);
    }

    public function addVerb($name,$graceful = false)
    {
        $db = DBConnection::getInstance()->getHandle();


        if($graceful)
             $prepared_query = " INSERT IGNORE  into Verbs(name,create_time) values (?,?)";
        else
            $prepared_query = " INSERT  into Verbs(name,create_time) values (?,?)";
        $create_time = date("Y-m-d H:i:s");

        $stmt = $db->getPreparedStatement($prepared_query);
        $stmt->bind_param("ss",$name,$create_time);

        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $id = $stmt->insert_id;
        $stmt->close();

        if($graceful && $id == 0 ) // row previously existed, fetch id
            return $this->getVerbByName($name);

        error_log("Verb $name inserted with $id and status $status");
        return new Verb($id,$name,$create_time);
    }

    public function searchVerbs($query)
    {
        $db = DBConnection::getInstance()->getHandle();
        $query = " SELECT * from Verbs where name like '%" . $query . "%'";

        $results = $db->getRecords($query);
        $verbs = array();

        foreach($results as $r)
            array_push($verbs, new Verb($r["id"],$r["name"],$r["create_time"]));

        return $verbs;

    }
}