<?php

require_once "autoload.inc.php";

$nomCarte = $_GET['term'];
$card = true;
$count = 5;
$dataArray = array();

$stmt = myPDO::getInstance()->prepare(<<<SQL
<<<<<<< HEAD
                                    SELECT cards.uuid as uuid, foreign_data.name as name, scryfallId as image
=======
                                    SELECT cards.uuid as id, foreign_data.name as name
>>>>>>> b81656bb6c2b21e1c93b2dfdbce1cb070a8ca50d
                                    FROM cards, foreign_data
                                    WHERE cards.uuid = foreign_data.uuid 
                                    AND foreign_data.language = "French"
                                    AND foreign_data.name LIKE '%{$nomCarte}%'
<<<<<<< HEAD
                                    ORDER BY foreign_data.name ASC
                                    LIMIT 0,8;
=======
                                    ORDER BY foreign_data.name ASC;
>>>>>>> b81656bb6c2b21e1c93b2dfdbce1cb070a8ca50d
SQL
);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->execute();

while (($card = $stmt->fetch()) != false && $count >= 0) {
    $data["value"] = $card["name"];
    $data["label"] = $card["name"];
<<<<<<< HEAD
    $data["uuid"] = $card["uuid"];
    $data["image"] = $card["image"];
=======
>>>>>>> b81656bb6c2b21e1c93b2dfdbce1cb070a8ca50d
    array_push($dataArray, $data);
}

echo json_encode($dataArray);