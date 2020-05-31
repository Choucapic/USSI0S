<?php

require_once "autoload.inc.php";

$nomCarte = $_GET['term'];
$card = true;
$count = 5;
$dataArray = array();

$stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT cards.uuid as id, foreign_data.name as name
                                    FROM cards, foreign_data
                                    WHERE cards.uuid = foreign_data.uuid 
                                    AND foreign_data.language = "French"
                                    AND foreign_data.name LIKE '%{$nomCarte}%'
                                    ORDER BY foreign_data.name ASC;
SQL
);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->execute();

while (($card = $stmt->fetch()) != false && $count >= 0) {
    $data["value"] = $card["name"];
    $data["label"] = $card["name"];
    array_push($dataArray, $data);
}

echo json_encode($dataArray);