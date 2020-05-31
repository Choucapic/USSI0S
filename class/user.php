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
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * @param mixed $pseudo
     */
    public function setPseudo($pseudo): void
    {
        $this->pseudo = $pseudo;
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
    public function setEmail($email): void
    {
        $this->email = $email;
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
    public function setPassword($password): void
    {
        $this->password = $password;
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
     * @param mixed $profile_banner
     */
    public function setProfileBanner($profile_banner): void
    {
        $this->profile_banner = $profile_banner;
    }

    /**
     * @return mixed
     */
    public function getProfileIcon()
    {
        return $this->profile_icon;
    }

    /**
     * @param mixed $profile_icon
     */
    public function setProfileIcon($profile_icon): void
    {
        $this->profile_icon = $profile_icon;
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
                    INSERT INTO user (pseudo, email, password)
                    VALUES (?, ?, ?);
SQL
        );
        return $stmt->execute(array($pseudoR, $emailR, $pass));
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
                $_SESSION['id'] = $user->getId();
                $_SESSION['pseudo'] = $user->getPseudo();
                $_SESSION['mail'] = $user->getEmail();
                $_SESSION['experience'] = $user->getExperience();
                $_SESSION['banner'] = $user->getProfileBanner();
                $_SESSION['icon'] = $user->getProfileIcon();
                return true;
            } else {
                throw new Exception("mot de passe invalide");
            }
        }
    }

}