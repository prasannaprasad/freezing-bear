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

    public function __construct($id,$name,$create_time)
    {
        $this->id = $id;
        $this->name = $name;
        $this->create_time = $create_time;
    }

}