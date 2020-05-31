<?php

function __autoload($class_name){
    if (strtolower($class_name) == "mypdo") {
        include strtolower($class_name) . '.inc.php';
    } else {
        include strtolower($class_name) . '.class.php';
    }
}