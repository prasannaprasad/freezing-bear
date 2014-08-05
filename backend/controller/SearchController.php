<?php

require_once __SITE_PATH."/Facebook_extractor/FBDataExtractor/Utils/Utils.php";
require_once __SITE_PATH."/application/WebServiceException.php";
require_once __SITE_PATH."/model/dao/NounDao.php";
require_once __SITE_PATH."/model/dao/UserDao.php";

use FBDataExtractor\Utils\Utils;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchController
 *
 * @author gbaskaran
 */
Class SearchController extends BaseController{
    
    public function searchInMixedMode(){
        $query = Utils::getValueSafelyArr($this->registry->query_params,'query');
        $queryType = Utils::getValueSafelyArr($this->registry->query_params,'query_type');
        $fb_id = Utils::getValueSafelyArr($this->registry->query_params,'fb_id');
        if($query == '' || $fb_id == ''){
            throw new WebServiceException("Can't search without query param or fb_id",2000,__FILE__,__LINE__);
        }
        $noundDao = new NounDao();
        $nouns = $noundDao->searchNouns($query);
        $userDao = new UserDao();
        $users = $userDao->searchUsers($query,$fb_id);
        $searchResults = array();
        $searchResults['nouns'] = $nouns;
        $searchResults['users'] = $users;
        return $this->registry->data = json_encode($searchResults);
        
    }
}
