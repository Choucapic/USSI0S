<?php

require_once "autoload.inc.php";

$setName = $_GET['setName'];
$uuid = $_GET['uuid'];
$dataArray = array();

$stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT hasFoil, hasNonFoil
                                    FROM cards, sets
                                    WHERE cards.setCode = sets.code 
                                    AND sets.name = ?
                                    AND cards.name = (SELECT name FROM cards WHERE uuid = ?);
SQL
);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->execute(array($setName, $uuid));

if (($card = $stmt->fetch()) != false) {
    $data["hasFoil"] = $card["hasFoil"];
    $data["hasNonFoil"] = $card["hasNonFoil"];
    array_push($dataArray, $data);
}

echo json_encode($dataArray);