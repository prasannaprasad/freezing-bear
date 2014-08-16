<?php
include_once('BaseContainer.php');

/**
 * Created by JetBrains PhpStorm.
 * User: pvenkatesh
 * Date: 8/3/14
 * Time: 10:27 AM
 * To change this template use File | Settings | File Templates.
 */

class Verb extends BaseContainer
{
    public $id;
    public $name;
    public $create_time;
    public $form;

    public function __construct($id,$name,$create_time,$form)
    {
        $this->id = $id;
        $this->name = $name;
        $this->create_time = $create_time;
        $this->form = $form;
    }

}