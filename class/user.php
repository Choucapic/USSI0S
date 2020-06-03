<?php
session_start();
require_once 'autoload.inc.php';

class user
{
    private $id;

    private $pseudo;

    private $email;

    private $password;

    private $experience;

    private $profile_banner;

    private $profile_icon;

    private $verified;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getVerified()
    {
        return $this->verified;
    }

    /**
     * @param mixed $verified
     */
    public function setVerified($verified): void
    {
        $this->verified = $verified;
    }

    /**
     * @return mixed
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo, $pass)
    {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT * 
                                    FROM user
                                    WHERE id = ?;
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute(array($_SESSION['id']));
        $user = $stmt -> fetch();
        if(!$user){
            throw new Exception("utilisateur inexistant");
        } else {
            if (password_verify($pass, $user->getPassword())) {
                $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    UPDATE user
                                    SET pseudo = ?
                                    WHERE id = ?;
SQL
                );
                if ($stmt->execute(array($pseudo, $_SESSION['id']))) {
                    $_SESSION['pseudo'] = $pseudo;
                    return true;
                } else {
                    throw new Exception("Erreur dans la modification du pseudo");
                }
            } else {
                throw new Exception("mot de passe invalide");
            }
        }
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email, $pass)
    {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT * 
                                    FROM user
                                    WHERE id = ?;
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute(array($_SESSION['id']));
        $user = $stmt -> fetch();
        if(!$user){
            throw new Exception("utilisateur inexistant");
        } else {
            if (password_verify($pass, $user->getPassword())) {
                $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    UPDATE user
                                    SET email = ?
                                    WHERE id = ?;
SQL
                );
                if ($stmt->execute(array($email, $_SESSION['id']))) {
                    $_SESSION['mail'] = $email;
                    return true;
                } else {
                    throw new Exception("Erreur dans la modification de l'email");
                }
            } else {
                throw new Exception("mot de passe invalide");
            }
        }
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($old, $new)
    {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT * 
                                    FROM user
                                    WHERE id = ?;
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute(array($_SESSION['id']));
        $user = $stmt -> fetch();
        if(!$user){
            throw new Exception("utilisateur inexistant");
        } else {
            if (password_verify($old, $user->getPassword())) {
                $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    UPDATE user
                                    SET password = ?
                                    WHERE id = ?;
SQL
                );
                if ($stmt->execute(array(password_hash($new, PASSWORD_DEFAULT), $_SESSION['id']))) {
                    return true;
                } else {
                    throw new Exception("Erreur dans la modification du mot de passe");
                }
            } else {
                throw new Exception("mot de passe invalide");
            }
        }
    }

    /**
     * @return mixed
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * @param mixed $experience
     */
    public function setExperience($experience): void
    {
        $this->experience = $experience;
    }

    /**
     * @return mixed
     */
    public function getProfileBanner()
    {
        return $this->profile_banner;
    }

    /**
     * @param mixed $cardName
     */
    public function setProfileBanner($cardName)
    {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT scryfallId
                                    FROM cards, foreign_data
                                    WHERE cards.uuid = foreign_data.uuid
                                    AND foreign_data.name = ?;
SQL
        );
        $stmt->execute(array($cardName));
        if ($row = $stmt->fetch()) {
            $stmt = myPDO::getInstance()->prepare(<<<SQL
                        UPDATE user 
                        SET profile_banner = ?
                        WHERE id = ?
SQL
);
            if ($stmt->execute(array($row['scryfallId'], $_SESSION['id']))) {
                $_SESSION['banner'] = $row['scryfallId'];
                return true;
            } else {
                throw new Exception("Erreur dans la sauvegarde de la bannière");
            }
        } else {
            throw new Exception("Image introuvable");
        }
    }

    /**
     * @return mixed
     */
    public function getProfileIcon()
    {
        return $this->profile_icon;
    }

    /**
     * @param mixed $cardName
     */
    public function setProfileIcon($cardName)
    {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT scryfallId
                                    FROM cards, foreign_data
                                    WHERE cards.uuid = foreign_data.uuid
                                    AND foreign_data.name = ?;
SQL
        );
        $stmt->execute(array($cardName));
        if ($row = $stmt->fetch()) {
            $stmt = myPDO::getInstance()->prepare(<<<SQL
                        UPDATE user 
                        SET profile_icon = ?
                        WHERE id = ?
SQL
            );
            if ($stmt->execute(array($row['scryfallId'], $_SESSION['id']))) {
                $_SESSION['icon'] = $row['scryfallId'];
                return true;
            } else {
                throw new Exception("Erreur dans la sauvegarde de l'icone");
            }
        } else {
            throw new Exception("Image introuvable");
        }
    }

    public static function createFromID($id) {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT * 
                                    FROM user
                                    WHERE id = ?;
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute(array($id));
        $user = $stmt -> fetch();
        if(!$user){
            return false;
        } else {
            return $user;
        }
    }

    public static function register($pseudoR, $emailR, $passwordR) {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT * 
                                    FROM user
                                    WHERE email = ? OR pseudo = ?;
SQL
        );
        $stmt->execute(array($emailR, $pseudoR));
        $user = $stmt -> fetch();
        if (!$user) {
            $pass = password_hash($passwordR, PASSWORD_DEFAULT);
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                    INSERT INTO user (pseudo, email, password, verified)
                    VALUES (?, ?, ?, ?);
SQL
        );
        $verify = str_shuffle(md5($pseudoR));
        if ($stmt->execute(array($pseudoR, $emailR, $pass, $verify))) {
            $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT * 
                                    FROM user
                                    WHERE email = ?;
SQL
            );
            $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
            $stmt->execute(array($emailR));
            if ($user = $stmt -> fetch()) {
                return $user;
            } else {
                throw new Exception("Problème d'insertion de l'utilisateur");
            }
        } else {
            throw new Exception("Problème d'insertion de l'utilisateur");
        }
        } else {
            throw new Exception("Adresse mail et/ou pseudo déjà utilisés");
        }
}

    public static function signin($emailS, $passwordS) {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT * 
                                    FROM user
                                    WHERE email = ?;
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute(array($emailS));
        $user = $stmt -> fetch();
        if(!$user){
            return false;
        } else {
            if (password_verify($passwordS, $user->getPassword())) {
                if ($user->getVerified() == "1") {
                    $_SESSION['id'] = $user->getId();
                    $_SESSION['pseudo'] = $user->getPseudo();
                    $_SESSION['mail'] = $user->getEmail();
                    $_SESSION['experience'] = $user->getExperience();
                    $_SESSION['banner'] = $user->getProfileBanner();
                    $_SESSION['icon'] = $user->getProfileIcon();
                    return true;
                } else {
                    throw new Exception("Compte non vérifié");
                }
            } else {
                throw new Exception("mot de passe invalide");
            }
        }
    }

    public static function delete($id, $pass) {
        if ($id == null) $id = $_SESSION['id'];
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT * 
                                    FROM user
                                    WHERE id = ?;
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute(array($id));
        $user = $stmt -> fetch();
        if(!$user){
            throw new Exception("utilisateur inexistant");
        } else {
            if (password_verify($pass, $user->getPassword())) {
                $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    DELETE
                                    FROM user
                                    WHERE id = ?;
SQL
                );
                if ($stmt->execute(array($id))) {
                    return true;
                } else {
                    throw new Exception("Erreur dans la suppression du compte");
                }
            } else {
                throw new Exception("Mauvais mot de passe");
            }
        }
    }

    public static function getImageName($image) {
        if (isset($_SESSION[$image]) && $_SESSION[$image] != "") {
            $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    SELECT foreign_data.name as name
                                    FROM cards, foreign_data
                                    WHERE cards.uuid = foreign_data.uuid
                                    AND cards.scryfallId = ?
                                    AND language = "French";
SQL
            );
            $stmt->execute(array($_SESSION[$image]));
            if ($name = $stmt->fetch()) {
                return $name['name'];
            } else {
                throw new Exception("Problème lors de la récupération du nom de l'image");
            }
        } else {
            if ($image == "banner") {
                return "Abondance";
            } else {
                return "Jace, architecte des pensées";
            }
        }
    }

    public static function verify($code) {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
                                    UPDATE user
                                    SET verified = '1'
                                    WHERE verified = ?;
SQL
        );
        return $stmt->execute(array($code));
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
            if (!(($isFoil == 1 && $uuid['hasFoil'] == 1) || ($isFoil == 1 && $uuid['hasNonFoil']) == 1)) {
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
                                    UPDATE collection
                                    SET number = number - 1
                                    WHERE UUIDCard = ? and idUser = ?;
SQL
        );
        return $stmt->execute(array($uuid, $_SESSION['id']));
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