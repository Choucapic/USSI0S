<?php

require_once 'autoload.inc.php';

class collection
{
    private $idUser;

    private $UUIDCard;

    private $number;

    private $isFoil;

    /**
     * @return mixed
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @param mixed $idUser
     */
    public function setIdUser($idUser): void
    {
        $this->idUser = $idUser;
    }

    /**
     * @return mixed
     */
    public function getUUIDCard()
    {
        return $this->UUIDCard;
    }

    /**
     * @param mixed $UUIDCard
     */
    public function setUUIDCard($UUIDCard): void
    {
        $this->UUIDCard = $UUIDCard;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getIsFoil()
    {
        return $this->isFoil;
    }

    /**
     * @param mixed $isFoil
     */
    public function setIsFoil($isFoil): void
    {
        $this->isFoil = $isFoil;
    }

    public static function addCard($uuidC, $setName, $isFoil, $quantity) {
        $quantity = intval($quantity);
        if (!(is_int($quantity) and $quantity > 0)) throw new Exception("La quantité doit être un nombre entier positif");
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT cards.uuid as uuid, hasFoil, hasNonFoil
                                    FROM cards, sets
                                    WHERE cards.setCode = sets.code AND sets.name = ?
                                    AND cards.name = (SELECT name FROM cards WHERE uuid = ?);
SQL
        );
        $stmt->execute(array($setName, $uuidC));
        if (!($uuid = $stmt -> fetch())) {
            throw new Exception("Erreur dans la récupération de la carte");
        } else {
            $isFoil = ($isFoil == "Yes") ? 1 : 0;
            if (!(($isFoil == 1 && $uuid['hasFoil'] == 1) || ($isFoil == 0 && $uuid['hasNonFoil']) == 1)) {
                throw new Exception("Le paramètre foil de la carte que vous essayez d'insérer n'est pas valide");
            }
            $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    INSERT INTO collection (idUser, UUIDCard, number, isFoil)
                                    values (?, ?, ?, ?);
SQL
            );
            if ($stmt->execute(array($_SESSION['id'], $uuid['uuid'], $quantity, $isFoil))) {
                return true;
            } else {
                throw new Exception("Erreur dans l'ajout dans la collection");
            }
        }
    }

    public static function getCardsNumber() {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT SUM(number) as nb
                                    FROM collection
                                    WHERE idUser = ?;
SQL
        );
        $stmt->execute(array($_SESSION['id']));
        if (!($number = $stmt -> fetch())) {
            return 0;
        } else {
            return $number['nb'];
        }
    }

    public static function getCollectionPrice() {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT SUM(number*price) as price
                                    FROM collection, prices
                                    WHERE collection.UUIDCard = prices.uuid
                                    AND collection.isFoil = 1
                                    AND type = "paperFoil"
                                    AND idUser = ?;
SQL
        );
        $stmt->execute(array($_SESSION['id']));
        if ($price = $stmt -> fetch()) {
            $priceFoil = $price['price'];
        } else {
            $priceFoil = 0;
        }
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT SUM(number*price) as price
                                    FROM collection, prices
                                    WHERE collection.UUIDCard = prices.uuid
                                    AND collection.isFoil = 0
                                    AND type = "paper"
                                    AND idUser = ?;
SQL
        );
        $stmt->execute(array($_SESSION['id']));
        if ($price = $stmt -> fetch()) {
            $priceNoFoil = $price['price'];
        } else {
            $priceNoFoil = 0;
        }
        return ($priceFoil+$priceNoFoil);
    }

    public static function tableCollection($offset) {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT COUNT(idUser)
                                    FROM collection
                                    WHERE idUser = ?;
SQL
        );
        $stmt->execute(array($_SESSION['id']));
        $rows = $stmt -> fetch();
        if ($rows > 0) {
            $dataArray = array();
            $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT foreign_data.name as name, cards.rarity as rarity, sets.keyruneCode as setIcon, sets.name as setName, collection.isFoil as isFoil, prices.price as price, collection.number as quantity, cards.uuid as uuid
                                    FROM collection, foreign_data, sets, prices, cards
                                    WHERE collection.idUser = ? AND collection.isFoil = 1 AND prices.type = "paperFoil" AND foreign_data.language = "French"
                                    AND cards.uuid = foreign_data.uuid AND cards.uuid = prices.uuid AND collection.UUIDCard = cards.uuid AND cards.setCode = sets.code
                                    UNION
                                    SELECT foreign_data.name as name, cards.rarity as rarity, sets.keyruneCode as setIcon, sets.name as setName, collection.isFoil as isFoil, prices.price as price, collection.number as quantity, cards.uuid as uuid
                                    FROM collection, foreign_data, sets, prices, cards
                                    WHERE collection.idUser = ? AND collection.isFoil = 0 AND prices.type = "paper" AND foreign_data.language = "French"
                                    AND cards.uuid = foreign_data.uuid AND cards.uuid = prices.uuid AND collection.UUIDCard = cards.uuid AND cards.setCode = sets.code
                                    UNION
                                    SELECT cards.name as name, cards.rarity as rarity, sets.keyruneCode as setIcon, sets.name as setName, collection.isFoil as isFoil, prices.price as price, collection.number as quantity, cards.uuid as uuid
                                    FROM collection, sets, prices, cards
                                    WHERE collection.idUser = ? AND collection.isFoil = 0 AND prices.type = "paper"
                                    AND cards.uuid = prices.uuid AND collection.UUIDCard = cards.uuid AND cards.setCode = sets.code AND cards.uuid NOT IN (SELECT uuid from foreign_data WHERE language = "French")
                                    UNION
                                    SELECT cards.name as name, cards.rarity as rarity, sets.keyruneCode as setIcon, sets.name as setName, collection.isFoil as isFoil, prices.price as price, collection.number as quantity, cards.uuid as uuid
                                    FROM collection, sets, prices, cards
                                    WHERE collection.idUser = ? AND collection.isFoil = 1 AND prices.type = "paperFoil"
                                    AND cards.uuid = prices.uuid AND collection.UUIDCard = cards.uuid AND cards.setCode = sets.code AND cards.uuid NOT IN (SELECT uuid from foreign_data WHERE language = "French")
                                    ORDER BY name ASC;
SQL
            );
            $stmt->execute(array($_SESSION['id'], $_SESSION['id'], $_SESSION['id'], $_SESSION['id']));
            while (($card = $stmt->fetch()) != false) {
                $data["uuid"] = $card["uuid"];
                $data["name"] = $card["name"];
                $data["setIcon"] = $card["setIcon"];
                $data["setName"] = $card["setName"];
                $data["isFoil"] = $card["isFoil"];
                $data["price"] = $card["price"];
                $data["rarity"] = $card["rarity"];
                $data["quantity"] = $card["quantity"];
                array_push($dataArray, $data);
            }

            asort($dataArray);
            $html = "";
            for ($i = 0; $i < count($dataArray); $i++) {

                $html .= '<tr>
                            <td>'. $dataArray[$i]["name"] . '</td>
                            <td> <i class="ss ss-2x ss-grad ss-'. $dataArray[$i]["rarity"] .' ss-' . strtolower($dataArray[$i]["setIcon"]) . '"></i> ' . $dataArray[$i]["setName"] .'</td> 
                            <td>'. (($dataArray[$i]["isFoil"] == "1") ? 'Oui' : 'Non') .'</td>
                            <td class="right-align">'. $dataArray[$i]["quantity"] .'</td>
                            <td class="right-align">'. $dataArray[$i]["price"] .' €</td>
                            <td class="right-align"> 
                                 <a href="https://www.workshop.thibault-lanier.fr/USSI0S/index.php?route=plusCard&id='. $dataArray[$i]["uuid"] .'" class="btn-floating waves-effect waves-light green"><i class="material-icons">exposure_plus_1</i></a>
                                 <a href="https://www.workshop.thibault-lanier.fr/USSI0S/index.php?route=minusCard&id='. $dataArray[$i]["uuid"] .'" class="btn-floating waves-effect waves-light red"><i class="material-icons">exposure_neg_1</i></a>
                                 <a href="https://www.workshop.thibault-lanier.fr/USSI0S/index.php?route=deleteCard&id='. $dataArray[$i]["uuid"] .'" class="btn-floating waves-effect waves-light black"><i class="material-icons">clear</i></a>
                            </td>
</tr>';
            }
            return $html;
        } else {
            return "";
        }
    }

    public static function plusCard($uuid) {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    UPDATE collection
                                    SET number = number + 1
                                    WHERE UUIDCard = ? and idUser = ?;
SQL
        );
        return $stmt->execute(array($uuid, $_SESSION['id']));
    }

    public static function minusCard($uuid) {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT number
                                    FROM collection
                                    WHERE UUIDCard = ? and idUser = ?;
SQL
        );
        $stmt->execute(array($uuid, $_SESSION['id']));
        $nombre = $stmt->fetch();
        if ($nombre['number'] == 1) {

            $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    DELETE FROM collection
                                    WHERE UUIDCard = ? and idUser = ?;
SQL
            );

            return $stmt->execute(array($uuid, $_SESSION['id']));

        } else {

            $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    UPDATE collection
                                    SET number = number - 1
                                    WHERE UUIDCard = ? and idUser = ?;
SQL
            );

            return $stmt->execute(array($uuid, $_SESSION['id']));
        }
    }

    public static function deleteCard($uuid) {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    DELETE FROM collection
                                    WHERE UUIDCard = ? and idUser = ?;
SQL
        );
        return $stmt->execute(array($uuid, $_SESSION['id']));
    }
}