<?php

function __autoload($class_name){
    if (strtolower($class_name) == "mypdo") {
        require_once strtolower($class_name) . '.inc.php';
    } else {
        require_once 'class/'.strtolower($class_name) . '.class.php';
    }
}