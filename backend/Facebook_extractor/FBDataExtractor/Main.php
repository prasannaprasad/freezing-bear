

<?php

require_once( '../vendor/facebook/php-sdk-v4/autoload.php' );
require_once( 'FBUser.php');
require_once( 'GetFacebookUser.php');
require_once( 'Utils/Utils.php');

use FBDataExtractor\GetFacebookUser;
use FBDataExtractor\Utils\Utils;

$appid = '1445531622391571';
$app_secret ='42a95574a805e77f0480f95e7772787e';
$access_token = 'CAAUitA7dPxMBAOAXCWzwEmYTCUDZBfydDBAZAuaGB3yviVHJAWjZCPTwWOMZCUlYKnn9peZBnmFZCMn9jvNQAUDUG8FeV12iXuQPYOw0xDYZAuhqJfHWTEkUqbROQ6XD0C3ZBMpcoSZCkGLFXAWRD9S0u8OJKiRVIgHtRiQ0IULFKt1rFSYHMZBp0yEfRPBSeDH3fUTezojVlU1zPJuv9a5BNJ';

try{
    $fbUser = (new GetFacebookUser($appid, $app_secret, $access_token))->getFBUser();
    printDebugInfo($fbUser);
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


