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
        return new Noun($result["id"],$result["name"], $result["create_time"],$result["freebase_id"],$result["image_urls"],$result["type"]);
    }

    public function getNounByName($noun_name,$type)
    {
        /*
        $query = "Select * from Nouns where name='" . $noun_name . "'";
        $result = $db->getSingleRecord($query);
        return new Noun($result["id"],$result["name"], $result["create_time"]);
        */
        $db = DBConnection::getInstance()->getHandle();
        $prepared_query = "Select id,name,create_time,freebase_id,type,image_urls from Nouns where name= ? and type = ?";
        $stmt = $db->getPreparedStatement($prepared_query);
        $stmt->bind_param("ss",$noun_name,$type);
        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $id = $name = $create_time = $freebase_id = $type = $image_urls  = '';
        $stmt->bind_result($id,$name,$create_time,$freebase_id,$type,$image_urls);
        $stmt->fetch();
        return new Noun($id,$name,$create_time,$freebase_id,$image_urls,$type);
    }

    public function addNoun($name,$type,$graceful = false)
    {
        $db = DBConnection::getInstance()->getHandle();

        if($graceful)
            $prepared_query = " INSERT IGNORE  into Nouns(name,create_time,type) values (?,?,?)";
        else
            $prepared_query = " INSERT  into Nouns(name,create_time,type) values (?,?,?)";
        $create_time = date("Y-m-d H:i:s");

        $stmt = $db->getPreparedStatement($prepared_query);
        $stmt->bind_param("sss",$name,$create_time,$type);

        error_log("Executing $prepared_query with params $name,$create_time,$type");

        if(!($status = $stmt->execute()))
            throw new WebServiceException("Unable to execute query  " ,3017,__FILE__,__LINE__);
        $id = $stmt->insert_id;
        $stmt->close();

        if($graceful && $id == 0 ) // row previously existed, fetch id
            return $this->getNounByName($name,$type);

        error_log("Noun $name inserted with $id and status $status");
        return new Noun($id,$name,$create_time,"","",$type);
    }

    public function searchNouns($query)
    {
        $db = DBConnection::getInstance()->getHandle();
        $query = " SELECT * from Nouns where name like '%" . $query . "%' LIMIT 10";

        $results = $db->getRecords($query);
        $nouns = array();

        foreach($results as $r)
            array_push($nouns, new Noun($r["id"],$r["name"],$r["create_time"],$r["freebase_id"],$r["image_urls"],$r["type"]));

        error_log("Fetched total " . count($nouns));
        return $nouns;

    }
}