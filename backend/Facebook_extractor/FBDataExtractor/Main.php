

<?php

require_once( '../vendor/facebook/php-sdk-v4/autoload.php' );
require_once( 'FBUser.php');
require_once( 'GetFacebookUser.php');
require_once( 'Utils/Utils.php');

use FBDataExtractor\GetFacebookUser;
use FBDataExtractor\Utils\Utils;

$appid = '1445531622391571';
$app_secret ='42a95574a805e77f0480f95e7772787e';
$access_token = 'CAAUitA7dPxMBANW7QCDNlqyAkkJScyFSZCFqfG8FcOnDWq6OXe9l93MDdbprK2I8WnVmozs2O3dwIdrZAUfCVZCdvehH05G2oiP1po1DWzXK2DoKUZA9NIvr7PSNHzc6l0JRT9qhXGkO0nMYW1wUdHL9ZCHIUjZCgdwobZA8kZCnKN6OWK8srdfpS6LX2Vm7t727Ca7Y0sfIE248LYZBIRQ74aL7BNWZCoGL0ZD';

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


