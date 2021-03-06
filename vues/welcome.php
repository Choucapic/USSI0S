<?php

session_start();
$p = new WebPage("Welcome");

$p->appendContent(<<<HTML
  <body class="valign-wrapper">
    <div class="container center-align">
        <div class="row">
            <div class="col s5 hoverable vignette">
                <form id="Login" name="Login" action="index.php?route=signin" method="post">
                <p>Connexion</p>
                <div class="row">
                  <div class="input-field col s12">
                     <input id="emailCo" name="emailCo" type="email" class="validate">
                       <label for="emailCo">Email</label>
                  </div>
                 </div>
                 <div class="row">
                  <div class="input-field col s12">
                     <input id="passCo" name="passCo" type="password" minlength="8" class="validate">
                       <label for="passCo">Mot de passe</label>
                  </div>
                 </div>
                 <div class ="row">
                    <button class="btn waves-effect red disabled" id="connexionButton" type="submit">Submit
                        <i class="ss ss-sth"></i> 
                    </button>
                 </div>
                 </form>
            </div>
            
            <div class="col s1"></div>
            
            <div class="col s5 hoverable vignette">
                <form id="Register" name="Register" action="index.php?route=register" method="post">
                <p>Inscription</p>
                <div class="row">
                  <div class="input-field col s12">
                     <input id="pseudo" name="pseudo" type="text" class="validate" required>
                       <label for="pseudo">Pseudonyme</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                     <input id="emailIns" name="emailIns" type="email" class="validate" required>
                       <label for="emailIns">Email</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                     <input id="emailInsVerif" type="email" class="validate" required>
                       <label for="emailInsVerif">Verifier l'email</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                     <input id="passIns" name="passIns" type="password" class="validate" minlength="8" required>
                       <label for="passIns">Mot de passe</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                     <input id="passInsVerif" type="password" class="validate" minlength="8" required>
                       <label for="passInsVerif">Verifier le mot de passe</label>
                  </div>
                </div>
                <div class ="row">
                    <button class="btn waves-effect red disabled" id="registerButton" type="submit" name="action">Submit
                        <i class="ss ss-sth"></i> 
                    </button>
                 </div>
                 <p class="red-text" id="VerifMail"></p>
                 <p class="red-text" id="VerifPass"></p>
                </form>
            </div>
        </div>
    </div>
  </body>
HTML
);

if (isset($verifSent)) {
    if ($verifSent != "") {
        $text = "Un mail vient de vous être envoyé afin de vérifier votre compte";
    }
}
if (isset($verifDone)) {
    if ($verifDone != "") {
        $text = "Votre compte est validé ! Vous pouvez vous connecter";
    }
}
if (isset($text)) {
        $p->appendJs(<<<JS
    alert("{$text}"); 
JS
);
    }

$p->appendJs(<<<JS
$("form#Register input").change(function() {
        var mails = false;
        var passes = false;
         if ($("#emailIns").val() != "" && $("#emailInsVerif").val() != "") {
             if ($("#emailIns").val() != $("#emailInsVerif").val()) {
                 $("#VerifMail").html("Les deux mails doivent correspondre");
             } else {
                 $("#VerifMail").html("");
                 mails = true;
             }
         }
         if ($("#passIns").val() != "" && $("#passInsVerif").val() != "") {
             if ($("#passIns").val() != $("#passInsVerif").val()) {
                 $("#VerifPass").html("Les deux mots de passe doivent correspondre");
             } else {
                 $("#VerifPass").html("");
                 passes = true;
             }
         }
         if (mails && passes && $("#pseudo").val() != "") {
            if ($("#registerButton").hasClass("disabled")) $("#registerButton").removeClass("disabled");
         } else {
             if (!$("#registerButton").hasClass("disabled")) $("#registerButton").addClass("disabled");
         }
    });
    
    $("form#Login input").change(function() {
        if ($("#emailCo").val() != "" && $("#passCo").val() != "") {
            if ($("#connexionButton").hasClass("disabled")) $("#connexionButton").removeClass("disabled");
         } else {
             if (!$("#connexionButton").hasClass("disabled")) $("#connexionButton").addClass("disabled");
         }
    });
JS
);

echo $p->toHTML();