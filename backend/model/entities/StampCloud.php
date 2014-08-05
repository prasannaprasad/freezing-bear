<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pvenkatesh
 * Date: 8/3/14
 * Time: 9:05 AM
 * To change this template use File | Settings | File Templates.
 */
include_once ('BaseContainer.php');

class StampCloud extends BaseContainer
{
    public $fb_user_id;
    public $stamp_details;


    public function __construct($fb_user_id)
    {
        $this->fb_user_id = $fb_user_id;

    }

    public function addNoun($noun_name,$obj)
    {
        $this->stamp_details[$noun_name] = $obj;
    }


}