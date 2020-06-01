<?php

session_start();
require_once 'class/webpage.class.php';

function signinUser($emailS, $passwordS) {
    include 'class/user.php';
    if (User::signin($emailS, $passwordS)) {
        home();
    } else {
        throw new Exception("Problème de connexion : utilisateur non trouvé");
    }
}

function registerUser($pseudoR, $emailR, $passwordR) {
    include 'class/user.php';
    $verify = (User::register($pseudoR, $emailR, $passwordR));
    if ($verify != null) {
        require_once 'class/PHPMailer.class.php';
        $pseudo = htmlspecialchars($pseudoR);
        $mail = new PHPMailer();
        $mail->addAddress($emailR);
        $mail->isHTML(true);
        $mail->Subject = 'Vérification de votre compte';
        $mail->Body = <<<HTML
<h2>Bonjour {$pseudo} !</h2>
<p>Afin de vérifier votre compte, veuillez cliquer </p> <a href="https://www.workshop.thibault-lanier.fr/USSI0S/index.php?route=verify&code={$verify}">ici</a>
<p>Bisous</p>
HTML
        ;
        if(!$mail->send()) {
            throw new Exception("Problème de mail : " .$mail->ErrorInfo);
        } else {
            $verifSent = 'verificationSent';
            require_once 'vues/welcome.php';
        }
    } else {
        throw new Exception("Problème d'inscription : problème lors de l'alimentation de la base de données");
    }
}

function disconnection() {
    session_unset();
    session_destroy();
    header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/");
}

function welcomePage() {
    require_once 'vues/welcome.php';
}

function home() {
    require_once 'vues/home.php';
}

function myprofile() {
    require_once 'class/user.php';
    $bannerName = user::getImageName('banner');
    $iconName = user::getImageName('icon');
    require_once "vues/myProfile.php";
}

function changePseudo($pseudo, $pass) {
    require_once "class/user.php";
    $user = user::createFromID($_SESSION['id']);
    $user->setPseudo($pseudo, $pass);
    require_once 'vues/home.php';
}

function changeMail($mail, $pass) {
    require_once "class/user.php";
    $user = user::createFromID($_SESSION['id']);
    $user->setEmail($mail, $pass);
    require_once 'vues/home.php';
}

function changePass($old, $new) {
    require_once "class/user.php";
    $user = user::createFromID($_SESSION['id']);
    $user->setPassword($old, $new);
    require_once 'vues/home.php';
}

function changeBanner($cardName) {
    require_once "class/user.php";
    $user = user::createFromID($_SESSION['id']);
    $user->setProfileBanner($cardName);
    require_once 'vues/home.php';
}

function changeIcon($cardName) {
    require_once "class/user.php";
    $user = user::createFromID($_SESSION['id']);
    $user->setProfileIcon($cardName);
    require_once 'vues/home.php';
}

function deleteAccount($pass) {
    require_once "class/user.php";
    $user = user::createFromID($_SESSION['id']);
    $user->delete($pass);
    session_unset();
    session_destroy();
    header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/");
}

function verifyUser($code) {
    require_once "class/user.php";
    if (user::verify($code)) {
        $verifDone = 'verificationDone';
        require_once 'vues/welcome.php';
    } else {
        throw new Exception("Problème lors de la vérification");
    }
}

function myCollection() {
    require_once "vues/myCollection.php";
}