<?php
include_once('BaseContainer.php');

/**
 * Created by JetBrains PhpStorm.
 * User: pvenkatesh
 * Date: 8/3/14
 * Time: 10:27 AM
 * To change this template use File | Settings | File Templates.
 */

class Noun extends BaseContainer
{
    public $id;
    public $name;
    public $create_time;
    public $freebase_id;
    public $image_urls = array();
    public $type;

    public function __construct($id,$name,$create_time,$freebase_id,$image_urls,$type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->create_time = $create_time;
        $this->freebase_id = $freebase_id;
        $this->type = $type;
        if($image_urls != "")
            $this->image_urls = explode(",",$image_urls);
    }

}