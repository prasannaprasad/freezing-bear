<?php
class FreezingBearException extends Exception
{
    function __construct($message, $code = 0,$file,$line )
    {
        parent::__construct($message,$code);
        error_log("On $file:$line:  Exception raised with $message and $code");
    }
}
