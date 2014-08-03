<?php
include_once('BaseContainer.php');
/**
 * Created by JetBrains PhpStorm.
 * User: pvenkatesh
 * Date: 8/3/14
 * Time: 11:59 AM
 * To change this template use File | Settings | File Templates.
 */

class Stamp extends BaseContainer
{
    public $id;
    public $by_user_id;
    public $to_user_id;
    public $noun_id;
    public $noun_name;
    public $verb_id;
    public $verb_name;
    public $create_time;

    public function __construct($id,$by_user_id,$to_user_id,$noun_id,$noun_name,$verb_id,$verb_name,$create_time)
    {
        $this->id = $id;
        $this->by_user_id = $by_user_id;
        $this->to_user_id = $to_user_id;
        $this->noun_id = $noun_id;
        $this->noun_name = $noun_name;
        $this->verb_id = $verb_id;
        $this->verb_name = $verb_name;
        $this->create_time = $create_time;
    }


}