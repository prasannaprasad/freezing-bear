

<?php

define ('__SITE_PATH', '/Users/gbaskaran/projects/freezing-bear/backend/');

require_once( '../vendor/facebook/php-sdk-v4/autoload.php' );
require_once( 'FBUser.php');
require_once( 'FacebookConnect.php');
require_once( 'Utils/Utils.php');
require_once( '../../model/dao/UserDao.php');

use FBDataExtractor\FacebookConnect;
use FBDataExtractor\Utils\Utils;


$appid = '1445531622391571';
$app_secret ='42a95574a805e77f0480f95e7772787e';
$access_token = 'CAAUitA7dPxMBAEBL7zuKgLZBCIjXnY4mlPlLhIRzg2xfDE5gkftXllC9C6Q9E4UmJOqZBjZC6gK9Ssi0FJEHulsvCJqyZBfxuXdTCgnYKiGZCf8IZBy8xTfrx9c0qjmocGjUZA4OmULpj4szOUtFfOZBoPFw8b3aE9kTIxxnMvknccHuNC9BuBu3RHzfyH8uQwe0mStZAFbfPehRHj50qxV4e';

try{
    $fbConnect = new FacebookConnect($appid, $app_secret, $access_token);
    $fbUser = $fbConnect->getFBUser();
    
    printDebugInfo($fbUser);
    
    $userDao = new UserDao();
    $userDao->addUser($fbUser->getId(), $fbUser->getFirstName(), $fbUser->getLastName(), 
                       $fbUser->getProfilePic(), 
                       $fbUser->getGender(), $fbUser->getHomeTown(), $fbUser->getRelationshipStatus(), 
                       $fbUser->getBirthday(), $fbUser->getEmail(), $fbUser->getName(), $fbUser->getLocation(), $fbUser->getTimezone());
    
}catch(Exception $ex){
    error_log("Exception in getting user details for ".$access_token);
}


 function printDebugInfo($fbUser){
    echo $fbUser;
    echo "\nprinting user likes";
    echo Utils::printTwoDimenArray($fbUser->getLikes());
    $friends = $fbUser->getFriendList();
    if(is_array($friends) && count($friends) != 0){
        foreach($friends as $friend){
            echo "\nprinting Friend";
            echo $friend;
            echo "\nprinting Friend Likes";
            echo Utils::printTwoDimenArray($friend->getLikes());
        }
    }
}

  

?>


