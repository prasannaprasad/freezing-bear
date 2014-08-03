<?php
/**
 * Created by JetBrains PhpStorm.
 * User: pvenkatesh
 * Date: 8/3/14
 * Time: 9:05 AM
 * To change this template use File | Settings | File Templates.
 */

class StampCloud
{
    public $fb_user_id;
    public $stamp_count;

    public function getJSON()
    {
        return json_encode($this);
    }

    public function __construct($fb_user_id)
    {
        $this->fb_user_id = $fb_user_id;

    }

    public function addNounCount($noun_name,$count)
    {
        $this->stamp_count[$noun_name] = $count;
    }
}