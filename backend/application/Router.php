<?php

include('FreezingBearException.php');

class Router
{
    private $registry;
    private $path;
    private $args = array();
    public $file;
    public $controller;
    public $action;
    function __construct($registry)
    {
        $this->registry = $registry;
    }

    function setPath($path)
    {
        if (is_dir($path) == false)
        {
            throw new SpotlightException ('Invalid controller path: ' .  $path, 1412 , __FILE__, __LINE__);
        }

        $this->path = $path;

        error_log("### in router path: $path \n");
    }

    public function loader()
    {
        error_log("## In loader ########");
        $this->getController();

        $class = $this->controller . 'Controller';
        error_log("File: $this->file \n Class: $class \n Action: $this->action \n");
        #error_log("Trying readability on $this->file");
        if (is_readable($this->file) == false)
        {
            throw new SpotlightException ("$this->file not readable", 1110, __FILE__, __LINE__);
        }
        include ($this->file);

        $controller = new $class($this->registry);

        if (is_callable(array($controller, $this->action)) == false)
        {
            throw new SpotlightException ("Action $this->action  not available", 1111,__FILE__,__LINE__);
        }
        else
        {
            $action = $this->action;
        }

        $controller->$action();
    }

    private function getController()
    {
        error_log('In getController:');
        $uri_path = $_SERVER['REQUEST_URI'];
        $request_method = $_SERVER['REQUEST_METHOD'];

        $this->registry->uri_path = $uri_path;
        $this->registry->request_method = $request_method;
        $uri_components = explode('/',$uri_path);
        $this->registry->uri_components = $uri_components;

        error_log(print_r($uri_components,true));

        if($request_method == 'POST')
        {
            $this->registry->request_payload = @file_get_contents('php://input');
            error_log("Request payload:" . $this->registry->request_payload);
        }
        else if($request_method == 'GET')
        {
            $this->registry->query_params = $_GET;
        }

        $uri_components[2] = preg_replace("/\?.*/","",$uri_components[2]);

        switch($uri_components[2])
        {
            case "user":
                $uri_components[4] = preg_replace("/\?.*/","",$uri_components[4]);
                if($request_method == 'POST' && $uri_components[4] == "favorites" && $uri_components["5"] == "delete")
                {
                    $this->controller = 'PlaceFavorite';
                    $this->action = 'deleteFavorite';
                }
                else if($request_method == 'GET' && $uri_components[4] == "favorites")
                {
                    $this->controller = 'PlaceFavorite';
                    $this->action = 'getFavorites';
                }
                else if($request_method == 'GET' && $uri_components[4] == "badges")
                {
                    $this->controller = 'Badge';
                    $this->action = 'getUserBadgesForTopicPlace';
                }
                else if($request_method == 'GET')
                {
                    $this->controller = 'User';
                    $this->action = 'getUser';
                }
                else if($request_method == 'POST' && $uri_components[4] == "favorites")
                {
                    $this->controller = 'PlaceFavorite';
                    $this->action = 'createFavorite';
                }
                else if($request_method == 'POST')
                {
                    $this->controller = 'User';
                    $this->action = 'createUser';
                }
                else
                    throw new SpotlightException("Unsupported request method on user", 1001,__FILE__,__LINE__);
                break;

            case "topics":
                $this->controller = "Topic";
                if($request_method == 'GET')
                    $this->action = 'searchTopics';
                else throw new SpotlightException("Unsupported request method on Topics", 1011,__FILE__,__LINE__);
                break;

            case "topic":
                $this->controller = "Topic";
                if($request_method == 'POST')
                    $this->action = 'createTopic';

                else throw new SpotlightException("Unsupported request method on Topic", 1012,__FILE__,__LINE__);

                break;

            case "item":
                $this->controller = "Item";
                if($request_method == 'POST' && isset($uri_components[3]))
                    $this->action = 'mapItem';
                elseif($request_method == 'POST')
                    $this->action = 'createItem';
                elseif($request_method == 'GET')
                    $this->action = 'getItem';

                else throw new SpotlightException("Unsupported request method on Item", 1013,__FILE__,__LINE__);

                break;

            case "items":
                $this->controller = "Item";
                if($request_method == 'GET' && isset($uri_components[3]))
                    $this->action = 'getItems';
                elseif($request_method == 'GET')
                    $this->action = 'searchItems';
                else throw new SpotlightException("Unsupported request method on Item", 1013,__FILE__,__LINE__);
                break;

            case "place":
                $uri_components[4] = preg_replace("/\?.*/","",$uri_components[4]);
                if($uri_components[4] == "topic" && $request_method == 'GET')
                {
                    $this->controller = "Item";
                    $this->action = "getItemsForTopicPlace";
                }
                else if($uri_components[4] == "topics" && $request_method == 'GET')
                {
                    $this->controller = "Topic";
                    $this->action = "getTopicsWithItems";
                }
                else throw new SpotlightException("Unsupported request method on Place", 1014,__FILE__,__LINE__);
                break;

            case "places":
                $this->controller = "Place";
                if($request_method == 'GET')
                    $this->action = 'searchPlaces';
                else throw new SpotlightException("Unsupported request method on Places", 1042,__FILE__,__LINE__);
                break;

            case "comment":
                $this->controller = "Comment";
                if($request_method == 'POST')
                    $this->action = 'createComment';
                else if($request_method == 'GET')
                    $this->action = 'getCommentsForTopic';
                else
                    throw new SpotlightException("Unsupported request method on Comment", 1015, __FILE__, __LINE__);
                break;

            case "photo":
                $this->controller = "ItemPhoto";
                if($request_method == 'POST')
                    $this->action = 'addItemPhoto';
                else if($request_method == 'GET')
                    $this->action = 'getItemPhoto';
                else
                    throw new SpotlightException("Unsupported request method on Photo", 1015, __FILE__, __LINE__);
                break;

            case "badge":
                $this->controller = "Badge";
                if($request_method == 'POST' and !isset($uri_components["3"]))
                    $this->action = 'addBadge';
                elseif($uri_components["3"] == "delete")
                    $this->action = 'removeBadge';
                elseif($request_method == 'GET')
                    $this->action = 'getUserBadgesForTopicPlace';
                else
                    throw new SpotlightException("Unsupported request method on SpotRanking.", 1015, __FILE__, __LINE__);
                break;


        }


        $this->file = $this->path .'/'. $this->controller . 'Controller.class.php';

    }



}

