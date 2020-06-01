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
                    break;
                case "myprofile" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        myprofile();
                    } else {
                        header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/");
                    }
                    break;
                case "changePseudo" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        changePseudo($_POST["newPseudo"], $_POST['passPseudo']);
                    } else {
                        header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/");
                    }
                    break;
                case "changeMail" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        changeMail($_POST["newMail"], $_POST['passMail']);
                    } else {
                        header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/");
                    }
                    break;
                case "changePass" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        changePass($_POST["passOld"], $_POST['passNew']);
                    } else {
                        header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/");
                    }
                    break;
                case "changeBanner" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        changeBanner($_POST["cardNameBanner"]);
                    } else {
                        header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/");
                    }
                    break;
                case "changeIcon" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        changeIcon($_POST["cardNameIcon"]);
                    } else {
                        header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/");
                    }
                    break;
                case "deleteAccount" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        deleteAccount($_POST["passAccount"]);
                    } else {
                        header("Location: https://www.workshop.thibault-lanier.fr/USSI0S/");
                    }
                    break;
                case "verify" :
                    if (isset($_GET['code'])) {
                        if ($_GET['code'] != "") {
                            verifyUser($_GET['code']);
                        } else {
                            throw new Exception("Problème de code de vérification");
                        }
                    } else {
                        throw new Exception("Problème de code de vérification");
                    }
                    break;
                case "mycollection" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        myCollection();
                    } else {
                        welcomePage();
                    }
                    break;
                case "addCard" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        if (isset($_POST['cardName']) && isset($_POST['extension']) && isset($_POST['quantity'])) {
                            if ($_POST['cardName'] != "" && $_POST['extension'] != "" && $_POST['quantity'] != "") {
                                $isFoil = (isset($_POST['isFoil']) ? $_POST['isFoil'] : "No");
                                addCard($_POST['cardName'], $_POST['extension'], $isFoil, $_POST['quantity']);
                            } else {
                                throw new Exception("Paramètres vides pour l'ajout de carte");
                            }
                        }else {
                            throw new Exception("Paramètres manquants pour l'ajout de carte");
                        }
                    } else {
                        welcomePage();
                    }
                    break;
                case "plusCard" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        if (isset($_GET['id'])) {
                            if ($_GET['id'] != "") {
                                plusCard($_GET['id']);
                            } else {
                                throw new Exception("Paramètres vides pour l'ajout de carte");
                            }
                        }else {
                            throw new Exception("Paramètres manquants pour l'ajout de carte");
                        }
                    } else {
                        welcomePage();
                    }
                    break;
                case "minusCard" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        if (isset($_GET['id'])) {
                            if ($_GET['id'] != "") {
                                minusCard($_GET['id']);
                            } else {
                                throw new Exception("Paramètres vides pour la diminution de carte");
                            }
                        }else {
                            throw new Exception("Paramètres manquants pour la diminution de carte");
                        }
                    } else {
                        welcomePage();
                    }
                    break;
                case "deleteCard" :
                    if (isset($_SESSION["id"]) && $_SESSION["id"] != "") {
                        if (isset($_GET['id'])) {
                            if ($_GET['id'] != "") {
                                deleteCard($_GET['id']);
                            } else {
                                throw new Exception("Paramètres vides pour la suppression de carte");
                            }
                        }else {
                            throw new Exception("Paramètres manquants pour la suppression de carte");
                        }
                    } else {
                        welcomePage();
                    }
                    break;
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