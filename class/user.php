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


}