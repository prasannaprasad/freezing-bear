

<?php

define('__SITE_PATH', '/Users/gbaskaran/projects/freezing-bear/backend/');

require_once __SITE_PATH . 'Facebook_extractor/vendor/facebook/php-sdk-v4/' . 'autoload.php';
require_once __SITE_PATH . 'Facebook_extractor/FBDataExtractor/' . 'FBUser.php';
require_once __SITE_PATH . 'Facebook_extractor/FBDataExtractor/' . 'FacebookConnect.php';
require_once __SITE_PATH . 'Facebook_extractor/FBDataExtractor/Utils/' . 'Utils.php';
require_once __SITE_PATH . 'model/dao/' . 'UserDao.php';
require_once __SITE_PATH . 'model/dao/' . 'UserFriendsDao.php';
require_once __SITE_PATH . 'model/dao/' . 'StampDao.php';

use FBDataExtractor\FacebookConnect;
use FBDataExtractor\Utils\Utils;

$appid = '1445531622391571';
$app_secret = '42a95574a805e77f0480f95e7772787e';
$access_token = 'CAAUitA7dPxMBAIBYLwFxiOzywKB4d0c6LBHIpxxEKq4ZA4KNs9ZCRsEoeDfA3OPNyw04Ktj6huZCzHoLDeLkNcGQlrclOTVmRkpF5WmFLAe131zGWWz8k1U01zsuTWZAMIEdnnXBvyKPWZAuT1VI9jzlaPR8Wcek3X4PA6iCVt2qDhw8q31VQVBIoNFR8zdDcWNLNOA2t9Jr16MWLM2HY';

try {
    $fbConnect = new FacebookConnect($appid, $app_secret, $access_token);
    $fbUser = $fbConnect->getFBUser();

    printDebugInfo($fbUser);
    persistUser($fbUser);
} catch (Exception $ex) {
    error_log("Exception in getting user details for " . $access_token);
}

function persistUser($fbUser) {
    persistUserBasicInfo($fbUser);
    persistFriends($fbUser);
}


function persistStamps($fbUser){
    $stampDao = new StampDao();
    // live location
    if($fbUser->getLocation() != NULL && $fbUser->getLocation() != ''){
        $stampDao->addStamp("facebook", $fbUser->getId(), 'live', $fbUser->getLocation());
    }
    // live homeTown
    if($fbUser->getHomeTown() != NULL && $fbUser->getHomeTown() != ''){
        $stampDao->addStamp("facebook", $fbUser->getId(), 'born', $fbUser->getHomeTown());
    }
    // work workHistory
    $workArray = $fbUser->getWorkHistory();
    if(is_array($workArray) && count($workArray) != 0 ){
        foreach($workArray as $work){
            $stampDao->addStamp("facebook", $fbUser->getId(), 'work', $work);
        }
    }
    
    //study eductionHistory
    $eduArray = $fbUser->getEducationHistory();
    if(is_array($eduArray) && count($eduArray) != 0 ){
        foreach($eduArray as $edu){
            $stampDao->addStamp("facebook", $fbUser->getId(), 'study', $edu);
        }
    }
    
    // like likes
    $likes = $fbUser->getLikes();
    if(is_array($likes) && count($likes) != 0){
        foreach($likes as $like){
            $stampDao->addStamp("facebook", $fbUser->getId(), 'like', $like['name'], $like['created_time']);
        }
    }
    
}

function persistFriends($fbUser){
    $friendList = $fbUser->getFriendList();
    $fromId = $fbUser->getId();
    $userFriendsDao = new UserFriendsDao();
    foreach($friendList as $friend){
        persistUserBasicInfo($friend);
        $toId = $friend->getId();
        echo "Adding toId =".$toId.' and fromID = '.$fromId;
        $userFriendsDao->addFriend($fromId, $toId);
    }
    
}
function persistUserBasicInfo($fbUser){
    $userDao = new UserDao();
    $userDao->addUser($fbUser->getId(), $fbUser->getFirstName(), $fbUser->getLastName(), $fbUser->getProfilePic(), $fbUser->getGender(), $fbUser->getHomeTown(), $fbUser->getRelationshipStatus(), $fbUser->getBirthday(), $fbUser->getEmail(), $fbUser->getName(), $fbUser->getLocation(), $fbUser->getTimezone());
    persistStamps($fbUser);    
}


/**
 * Prints the user to stdout for debug purposes
 * @param type $fbUser
 */
function printDebugInfo($fbUser) {
    echo $fbUser;
    echo "\nprinting user likes";
    echo Utils::printTwoDimenArray($fbUser->getLikes());
    $friends = $fbUser->getFriendList();
    if (is_array($friends) && count($friends) != 0) {
        foreach ($friends as $friend) {
            echo "\nprinting Friend";
            echo $friend;
            echo "\nprinting Friend Likes";
            echo Utils::printTwoDimenArray($friend->getLikes());
        }
    }
}
?>


