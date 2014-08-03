<?php

include_once('model/dao/VerbDao.php');

include_once('application/WebServiceException.php');

Class VerbController Extends BaseController
{

    public function addVerb()
    {

        $request_body = $this->registry->request_payload;
        $json_data = json_decode($request_body);

        if(!isset($json_data->name) || $json_data->name == "")
            throw new WebServiceException(":Mandatory params in payload missing",1218,__FILE__,__LINE__);

        $verbDao = new VerbDao();
        $verb = $verbDao->addVerb($json_data->name, true);

        $this->registry->data = $verb->getJSON();

    }


}
