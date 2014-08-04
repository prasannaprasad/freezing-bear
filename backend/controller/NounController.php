<?php

include_once('model/dao/NounDao.php');

include_once('application/WebServiceException.php');

Class NounController Extends BaseController
{

    public function addNoun()
    {

        $request_body = $this->registry->request_payload;
        $json_data = json_decode($request_body);

        if(!isset($json_data->name) || $json_data->name == "")
            throw new WebServiceException(":Mandatory params in payload missing",1218,__FILE__,__LINE__);

        $nounDao = new NounDao();
        $noun = $nounDao->addNoun($json_data->name,true);

        $this->registry->data = $noun->getJSON();

    }

    public function searchNouns($query)
    {
        $query = $this->registry->query_params["query"];

        $nounDao = new NounDao();
        $nouns = $nounDao->searchNouns($query);

        $this->registry->data = json_encode($nouns);
    }


}
