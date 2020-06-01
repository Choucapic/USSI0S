<?php

require_once "autoload.inc.php";

$setCode = $_GET['set'];
$card = true;
$dataArray = array();

$stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT name, keyruneCode as code
                                    FROM sets
                                    WHERE sets.code = "{$setCode}"
                                    ORDER BY name ASC;
SQL
);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
$stmt->execute();

while (($set = $stmt->fetch()) != false) {
    $data["name"] = $set["name"];
    $data["code"] = $set["code"];
    array_push($dataArray, $data);
}

echo json_encode($dataArray);