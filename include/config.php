<?php

$config = array(  
    "db" => array(  
        "db1" => array(  
            "dbname" => "serwer1309748_04",
            "username" => "serwer1309748_04",
            "password" => "9!c3Q9",
            "dbhost" => "serwer1309748.home.pl"
        ),  
        "db2" => array(  
            "dbname" => "database2",  
            "username" => "dbUser",  
            "password" => "pa$$",  
            "dbhost" => "localhost"  
        )  
    ),  
    "urls" => array(  
        "baseUrl" => "http://example.com"  
    ),  
    "paths" => array(  
        "resources" => "/path/to/resources",  
        "images" => array(  
            "content" => $_SERVER["DOCUMENT_ROOT"] . "/images/content",  
            "layout" => $_SERVER["DOCUMENT_ROOT"] . "/images/layout"  
        )  
    )  
);  


?>

