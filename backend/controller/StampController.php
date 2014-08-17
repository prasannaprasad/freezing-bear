<?php

include_once('model/dao/StampDao.php');

include_once('application/WebServiceException.php');

Class StampController Extends BaseController
{

    public function addStamp()
    {

        $request_body = $this->registry->request_payload;
        $json_data = json_decode($request_body);

        if(!isset($json_data->by_user_id) || $json_data->by_user_id == "" ||
           !isset($json_data->to_user_id) || $json_data->to_user_id == "" ||
           !isset($json_data->verb_name) || $json_data->verb_name == "" ||
            !isset($json_data->verb_form_name) || $json_data->verb_form_name == "" ||
            !isset($json_data->noun_type) || $json_data->noun_type == "" ||
            !isset($json_data->noun_name) || $json_data->noun_name == "")
            throw new WebServiceException(":Mandatory params in payload missing",1218,__FILE__,__LINE__);

        $stampDao = new StampDao();
        $stamp  = $stampDao->addStamp($json_data->by_user_id,$json_data->to_user_id,$json_data->verb_name,$json_data->verb_form_name,$json_data->noun_name,$json_data->noun_type);

        $this->registry->data = $stamp->getJSON();

    }


}
