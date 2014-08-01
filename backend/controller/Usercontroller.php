<?php

include('model/dao/UserDao.php');

Class UserController Extends BaseController
{

    public function getUser()
    {

        $uri_components = $this->registry->uri_components;


        if(isset($uri_components[4]))
        {
            $user_id = $uri_components[4];
            error_log("Fetching data for $user_id");

            $userDao = new UserDao();
            $user = $userDao->getUserById($user_id);

            $this->registry->data = $user->getJSON();

        }
        else
            throw new FreezingBearException(":User id not passed",1212,__FILE__,__LINE__);
    }


}
