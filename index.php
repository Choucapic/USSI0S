<?php

require_once 'controller.php';

ini_set('display_errors',1);

if (isset($_GET["route"])) {
    if ($_GET["route"] != "") {

    } else {
        homePage();
    }
} else {
    homePage();
}