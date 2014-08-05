<?php

include_once('model/dao/UserDao.php');
include_once('model/dao/UserFriendsDao.php');
include_once('model/dao/StampCloudDao.php');
include_once('application/WebServiceException.php');

Class UserController Extends BaseController
{

    private function extractUserid()
    {
        $uri_components = $this->registry->uri_components;
        if(!isset($uri_components[4]))
            throw new WebServiceException(":User id not passed",1212,__FILE__,__LINE__);
        else
            return $uri_components[4];
    }

    public function getUser()
    {
            $user_id = $this->extractUserid();

            error_log("Fetching data for $user_id");

            $userDao = new UserDao();
            $user = $userDao->getUserById($user_id);

            $this->registry->data = $user->getJSON();

    }

    public function getUserFriends()
    {
        $user_id = $this->extractUserid();
        error_log("Fetching friends for $user_id");

        $userFriendsDao = new UserFriendsDao();
        $mini_users = $userFriendsDao->getUserFriends($user_id);

        $this->registry->data = json_encode($mini_users);
    }

    public function getUserStampCloud()
    {
        $user_id = $this->extractUserid();
        error_log("Fetching stampcloud for $user_id");
        $stamp_cloud_dao = new StampCloudDao();
        $stamp_cloud = $stamp_cloud_dao->getUserStampCloud($user_id);

        $this->registry->data = $stamp_cloud->getJSON();

    }

}
