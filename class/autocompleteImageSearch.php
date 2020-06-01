<?php

require_once "autoload.inc.php";

$nomCarte = $_GET['term'];
$card = true;
$dataArray = array();

$stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT cards.uuid as uuid, foreign_data.name as name, scryfallId as image, printings
                                    FROM cards, foreign_data
                                    WHERE cards.uuid = foreign_data.uuid 
                                    AND foreign_data.language = "French"
                                    AND foreign_data.name LIKE '%{$nomCarte}%'
                                    ORDER BY foreign_data.name ASC
                                    LIMIT 0,8; 
SQL
);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->execute();

while (($card = $stmt->fetch()) != false) {
    $data["value"] = $card["name"];
    $data["label"] = $card["name"];
    $data["uuid"] = $card["uuid"];
    $data["image"] = $card["image"];
    $data["printings"] = $card["printings"];
    array_push($dataArray, $data);
}

echo json_encode($dataArray);