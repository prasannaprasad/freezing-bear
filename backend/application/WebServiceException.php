<?php
class FreezingBearException extends Exception
{
    function __construct($message, $code,$file,$line )
    {
        parent::__construct($message,$code);
        error_log("On $file:$line:  Exception raised with $message and $code");
    }
}
