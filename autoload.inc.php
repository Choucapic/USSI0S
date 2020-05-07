<?php

function __autoload($class_name){
    if (strtolower($class_name) == "mypdo") {
        require_once 'class/'.strtolower($class_name) . '.include.php';
    } else {
        require_once 'class/'.strtolower($class_name) . '.class.php';
    }
}