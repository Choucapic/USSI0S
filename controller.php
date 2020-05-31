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
    if (User::register($pseudoR, $emailR, $passwordR)) {
        if (User::signin($emailR, $passwordR)) {
            home();
        } else {
            throw new Exception("Problème de connexion : utilisateur non trouvé");
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