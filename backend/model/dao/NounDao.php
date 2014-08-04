<?php
include_once('DBConnection.php');
include_once __SITE_PATH . '/model/entities/' . 'Noun.php';
include_once __SITE_PATH . '/application/WebServiceException.php';


class NounDao
{

    public function getNounById($noun_id)
    {
        $db = DBConnection::getInstance()->getHandle();
        $query = "Select * from Nouns where id='" . $noun_id . "'";
        $result = $db->getSingleRecord($query);
        return new Noun($result["id"],$result["name"], $result["create_time"]);
    }

    public function getNounByName($noun_name)
    {
        /*
        $query = "Select * from Nouns where name='" . $noun_name . "'";
        $result = $db->getSingleRecord($query);
        return new Noun($result["id"],$result["name"], $result["create_time"]);
        */
        $db = DBConnection::getInstance()->getHandle();
        $prepared_query = "Select id,name,create_time from Nouns where name= ?";
        $stmt = $db->getPreparedStatement($prepared_query);
        $stmt->bind_param("s",$noun_name);
        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $id = $name = $create_time = '';
        $stmt->bind_result($id,$name,$create_time);
        $stmt->fetch();
        return new Noun($id,$name,$create_time);
    }

    public function addNoun($name,$graceful = false)
    {
        $db = DBConnection::getInstance()->getHandle();

        if($graceful)
            $prepared_query = " INSERT IGNORE  into Nouns(name,create_time) values (?,?)";
        else
            $prepared_query = " INSERT  into Nouns(name,create_time) values (?,?)";
        $create_time = date("Y-m-d H:i:s");

        $stmt = $db->getPreparedStatement($prepared_query);
        $stmt->bind_param("ss",$name,$create_time);

        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $id = $stmt->insert_id;
        $stmt->close();

        if($graceful && $id == 0 ) // row previously existed, fetch id
            return $this->getNounByName($name);

        error_log("Noun $name inserted with $id and status $status");
        return new Noun($id,$name,$create_time);
    }

    public function searchNouns($query)
    {
        $db = DBConnection::getInstance()->getHandle();
        $query = " SELECT * from Nouns where name like '%" . $query . "%'";

        $results = $db->getRecords($query);
        $nouns = array();

        foreach($results as $r)
            array_push($nouns, new Noun($r["id"],$r["name"],$r["create_time"]));

        return $nouns;

    }
}