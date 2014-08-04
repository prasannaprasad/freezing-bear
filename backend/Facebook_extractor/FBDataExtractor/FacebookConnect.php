<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FBDataExtractor;

require_once( '../vendor/facebook/php-sdk-v4/autoload.php' );
require_once( 'FBUser.php');
require_once( 'Utils/Utils.php');



use FBDataExtractor\Utils\Utils;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRequestException;
use FBDataExtractor\FBUser;

/**
 * Description of FacebookConnect
 *
 * @author gbaskaran
 */
class FacebookConnect {

    private $appid;
    private $app_secret;
    private $access_token;
    private $fb_session;
    private $appsecret_proof;

    public function __construct($appid, $app_secret, $access_token) {
        $this->appid = $appid;
        $this->app_secret = $app_secret;
        $this->access_token = $access_token;
        $this->fb_session = $this->getFBSessionForUser($appid, $app_secret, $access_token);
        $this->appsecret_proof = hash_hmac('sha256', $access_token, $app_secret);
    }

    public function getFBUser() {
        if ($this->fb_session) {
            $userData = $this->getUserData();
            if (is_array($userData) && count($userData) != 0) {
                $fbUser = $this->getFBUserWithBasicProfileInfo($userData);
                $this->setUserLikes($fbUser, $userData);
                $this->setUserFriends($fbUser, $userData);
                return $fbUser;
            }
        }
    }

    private function setUserFriends($fbUser, $userData) {
        $friendsArray = Utils::getValueSafelyArr($userData, 'friends');
        if (is_array($friendsArray) && count($friendsArray) != 0) {
            $friends = $this->getFBEdgeData($friendsArray, array());
            $friendList = array();
            foreach ($friends as $friend) {
                $fbFriend = $this->getFBUserWithBasicProfileInfo($friend);
                $this->setUserLikes($fbFriend, $friend);
                $friendList[] = $fbFriend;
            }
            $fbUser->setFriendList($friendList);
        }
    }

    /**
     * Gets user likes
     * @param type $fbUser
     * @param type $userData
     */
    private function setUserLikes($fbUser, $userData) {
        $likesArray = Utils::getValueSafelyArr($userData, 'likes');
        if (is_array($likesArray) && count($likesArray) != 0) {
            $likes = $this->getFBEdgeData($likesArray, array('name', 'category', 'created_time'));
            $fbUser->setLikes($likes);
        }
    }

    /**
     * For edges that support paging, this function pulls all data after recursively paging
     * @param type $dataAndPaging
     * @param type $dataKeys
     * @param type $finalReturnData
     * @return type
     */
    private function getFBEdgeData($dataAndPaging, $dataKeys = array(), $finalReturnData = array()) {
        $dataArray = Utils::getValueSafelyArr($dataAndPaging, 'data');
        foreach ($dataArray as $data) {
            if (count($dataKeys) == 0) {
                $finalReturnData[] = $data;
            } else {
                $rowArray = array();
                foreach ($dataKeys as $key) {
                    $rowArray[$key] = Utils::getValueSafelyArr($data, $key);
                }
                $finalReturnData[] = $rowArray;
            }
        }
        $paging = Utils::getValueSafelyArr($dataAndPaging, 'paging');
        if (is_array($paging)) {
            $next = Utils::getValueSafelyArr($paging, 'next');
            if (!empty($next)) {
                $dataAndPaging = self::callGraphAPI(str_replace('https://graph.facebook.com/v2.0', '', $next));
                self::getFBEdgeData($dataAndPaging, $dataKeys, $finalReturnData);
            }
        }
        return $finalReturnData;
    }

    /**
     * Gets user's basic profile info
     * @param type $userData
     * @return \FBUser
     */
    private function getFBUserWithBasicProfileInfo($userData) {
        $fbUser = new FBUser($userData['id']);
        $fbUser->setFirstName(Utils::getValueSafelyArr($userData, 'first_name'));
        $fbUser->setLastName(Utils::getValueSafelyArr($userData, 'last_name'));
        $fbUser->setName(Utils::getValueSafelyArr($userData, 'name'));
        $fbUser->setBirthday(Utils::getValueSafelyArr($userData, 'birthday'));
        $fbUser->setGender(Utils::getValueSafelyArr($userData, 'gender'));
        $fbUser->setEmail(Utils::getValueSafelyArr($userData, 'email'));
        $fbUser->setTimezone(Utils::getValueSafelyArr($userData, 'timezone'));
        $fbUser->setRelationshipStatus(Utils::getValueSafelyArr($userData, 'relationship_status'));
        $locationArray = Utils::getValueSafelyArr($userData, 'location');
        if (is_array($locationArray) && count($locationArray) != 0) {
            $fbUser->setLocation(Utils::getValueSafelyArr($locationArray, 'name'));
        }
        $hometownArray = Utils::getValueSafelyArr($userData, 'hometown');
        if (is_array($hometownArray) && count($hometownArray) != 0) {
            $fbUser->setHomeTown(Utils::getValueSafelyArr($locationArray, 'name'));
        }
        
        $profilePicArray = Utils::getValueSafelyArr($userData, 'picture');
        $profileData = Utils::getValueSafelyArr($profilePicArray, 'data');
        $fbUser->setProfilePic(Utils::getValueSafelyArr($profileData, 'url'));

        $this->populateEducation($fbUser, $userData);
        $this->populateWork($fbUser, $userData);
        return $fbUser;
    }

    /**
     * Extracts work details
     * @param type $fbUser
     * @param type $userData
     */
    private function populateWork($fbUser, $userData) {
        $workArray = Utils::getValueSafelyArr($userData, 'work');
        if (is_array($workArray) && count($workArray) != 0) {
            $userWorkHistory = array();
            foreach ($workArray as $work) {
                $employer = Utils::getValueSafelyArr($work, 'employer');
                $employer_name = Utils::getValueSafelyArr($employer, 'name');
                $userWorkHistory[] = $employer_name;
            }
            $fbUser->setWorkHistory($userWorkHistory);
        }
    }

    /**
     * Extracts education details
     * @param type $fbUser
     * @param type $userData
     */
    private function populateEducation($fbUser, $userData) {
        $eduArray = Utils::getValueSafelyArr($userData, 'education');
        if (is_array($eduArray) && count($eduArray) != 0) {
            $userEduHistory = array();
            foreach ($eduArray as $edu) {
                $education_institute = Utils::getValueSafelyArr($edu, 'school');
                $school_name = Utils::getValueSafelyArr($education_institute, 'name');
                $userEduHistory[] = $school_name;
            }
            $fbUser->setEducationHistory($userEduHistory);
        }
    }

    /**
     * Makes the social graph API call to get all user data in one shot
     * @param type $appsecret_proof
     * @param type $session
     * @return type Array
     */
    private function getUserData() {
        $URL = '/me?fields=first_name,last_name,name,birthday,gender,location,email,timezone,work,education,relationship_status,hometown,picture,'
                . 'likes.fields(name,category,created_time),'
                . 'friends.fields(first_name,last_name,name,birthday,gender,location,email,timezone,work,education,'
                . 'likes.fields(name,category,created_time))';
        return self::callGraphAPI($URL);
    }

    /**
     * Uses Facebook PHP API to make social graph call
     * @param type $url
     * @return type
     */
    private function callGraphAPI($url) {
        try {
            echo "Calling " . $url . "\n";
            $params['appsecret_proof'] = $this->appsecret_proof;
            $facebook_request = new FacebookRequest($this->fb_session, 'GET', $url, $params);
            $facebook_response = $facebook_request->execute();
            $data = $facebook_response->getRawResponse();
            echo "Dumping JSON got from callGraphAPI call\n";
            echo $data;
            $dataArray = json_decode($data, TRUE);
            return $dataArray;
        } catch (FacebookRequestException $e) {
            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();
            throw $e;
        }
    }

    /**
     * Creates and returns user's Facebook session
     * @param type $appid
     * @param type $app_secret
     * @param type $access_token
     * @return \Facebook\FacebookSession
     */
    private function getFBSessionForUser() {
        try {
            FacebookSession::setDefaultApplication($this->appid, $this->app_secret);
            $session = new FacebookSession($this->access_token);
            echo "Session created successfully\n";
            return $session;
        } catch (FacebookRequestException $e) {
            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();
            throw $e;
        } catch (Exception $e) {
            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();
            throw $e;
        }
    }

}
