<?php

session_start();
require_once 'controller.php';

ini_set('display_errors',1);

try {
    if (isset($_GET["route"])) {
        if ($_GET["route"] != "") {
            switch ($_GET["route"]) {
                case "signin" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        throw new Exception("Problème de connexion : vous êtes déjà connecté");
                    } else {
                        if (isset($_POST["emailCo"]) && isset($_POST["passCo"])) {
                            if ($_POST["emailCo"] != "" && $_POST["passCo"] != "") {
                                signinUser($_POST["emailCo"], $_POST["passCo"]);
                            } else {
                                throw new Exception("Problème de connexion : champs vides");
                            }
                        } else {
                            throw new Exception("Problème de connexion : tous les champs n'ont pas été envoyés");
                        }
                    }
                    break;

                case "register" :
                    if (isset($_POST["emailIns"]) && isset($_POST["passIns"]) && isset($_POST["pseudo"])) {
                        if ($_POST["emailIns"] != "" && $_POST["passIns"] != "" && $_POST["pseudo"] != "") {
                            registerUser($_POST["pseudo"], $_POST["emailIns"], $_POST["passIns"]);
                        } else {
                            throw new Exception("Problème d'inscription : champs vides");
                        }
                    } else {
                        throw new Exception("Problème d'inscription : tous les champs n'ont pas été envoyés");
                    }
                    break;
                case "home" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        home();
                    } else {
                        header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/");
                    }
                    break;
                case "disconnect" :
                    disconnection();
                default:
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        home();
                    } else {
                        welcomePage();
                    }
            }

        } else {
            if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/index.php?route=home");
            } else {
                welcomePage();
            }
        }
    } else {
        if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
            header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/index.php?route=home");
        } else {
            welcomePage();
        }
    }
} catch (Exception $e) {
    echo "Une erreur est survenue : " . $e->getMessage();
    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
        header("refresh:5; url=https://www.workshop.thibault-lanier.fr/USSI0S/index.php?route=home");
    } else {
        header("refresh:5; url=https://www.workshop.thibault-lanier.fr/USSI0S/");
    }
}