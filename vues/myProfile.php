<?php

session_start();
$p = new WebPage("My profile");

<<<<<<< HEAD
$pseudo = htmlspecialchars($_SESSION['pseudo']);

=======
>>>>>>> b81656bb6c2b21e1c93b2dfdbce1cb070a8ca50d
$p->appendCss(<<<CSS
    .main {
      padding-left: 300px;
    }

    @media only screen and (max-width : 992px) {
      .main {
        padding-left: 0;
      }
    }
          
CSS
);

$p->appendJs(<<<JS
$(document).ready(function(){
    $('.sidenav').sidenav();
    $('.collapsible').collapsible({
      accordion : true
    });
    
     $("#cardNameBanner").autocomplete({
        source: "class/autocompleteImageSearch.php",
        minLength: 3,
        select: function( event, ui ) {
            $("#bannerButton").removeClass("disabled");
        }
<<<<<<< HEAD
    }).autocomplete().data("uiAutocomplete")._renderItem = function(ul, item) { 
         var script = document.createElement("script");
         var size = "200px";
         var url = "https://api.scryfall.com/cards/" + item.image + "?format=image";
         script.innerHTML = '$("#' + item.uuid + '").tooltip({html:"<img width='+ size +' src='+ url +'>"});';
         return $("<li>") 
         .append(item.label + '<i class="ss ss-van" data-position="right" id="' + item.uuid + '"></i>').append(script)
         .appendTo(ul); };
=======
    });
>>>>>>> b81656bb6c2b21e1c93b2dfdbce1cb070a8ca50d
     
     $("#cardNameIcon").autocomplete({
        source: "class/autocompleteImageSearch.php",
        minLength: 3,
        select: function( event, ui ) {
            $("#iconButton").removeClass("disabled");
        }
<<<<<<< HEAD
        }).autocomplete().data("uiAutocomplete")._renderItem = function(ul, item) { 
         var script = document.createElement("script");
         var size = "200px";
         var url = "https://api.scryfall.com/cards/" + item.image + "?format=image";
         script.innerHTML = '$("#' + item.uuid + '").tooltip({html:"<img width='+ size +' src='+ url +'>"});';
         return $("<li>") 
         .append(item.label + '<i class="ss ss-van" data-position="right" id="' + item.uuid + '"></i>').append(script)
         .appendTo(ul); };
=======
        });
>>>>>>> b81656bb6c2b21e1c93b2dfdbce1cb070a8ca50d
  });

 $("form#ChangePseudo input").change(function() {
        if ($("#newPseudo").val() != "" && $("#passPseudo").val() != "") {
            if ($("#pseudoButton").hasClass("disabled")) $("#pseudoButton").removeClass("disabled");
         } else {
             if (!$("#pseudoButton").hasClass("disabled")) $("#pseudoButton").addClass("disabled");
         }
    });
 
  $("form#ChangeMail input").change(function() {
        if ($("#newMail").val() != "" && $("#passMail").val() != "") {
            if ($("#mailButton").hasClass("disabled")) $("#mailButton").removeClass("disabled");
         } else {
             if (!$("#mailButton").hasClass("disabled")) $("#mailButton").addClass("disabled");
         }
    });
  
  $("form#ChangePass input").change(function() {
   if ($("#passNew").val() != "" && $("#passNewVerif").val() != "") {
             if ($("#passNew").val() != $("#passNewVerif").val()) {
                 if (!$("#passButton").hasClass("disabled")) $("#passButton").addClass("disabled");
                 $("#VerifPass").html("Les deux mots de passe doivent correspondre");
             } else {
                 $("#VerifPass").html("");
                 if ($("#passOld").val() != "") {
                     if ($("#passButton").hasClass("disabled")) $("#passButton").removeClass("disabled");
                 } else {
                     if (!$("#passButton").hasClass("disabled")) $("#passButton").addClass("disabled");
                 }
             }
   } else {
      if (!$("#passButton").hasClass("disabled")) $("#passButton").addClass("disabled"); 
   }
   });
  
   $("form#DeleteAccount input").change(function() {
       if ($("#passAccount").val() != "") {
           if ($("#deleteButton").hasClass("disabled")) $("#deleteButton").removeClass("disabled");
       } else {
           if (!$("#deleteButton").hasClass("disabled")) $("#deleteButton").addClass("disabled");
       }
       });
JS
);

$banner = ($_SESSION['banner'] == '') ? "46184f97-d5c9-4a98-9fd9-e19057ce9b7e" : $_SESSION['banner'];
$icon = ($_SESSION['icon'] == '') ? "736b07e8-4eb4-492d-a323-3125823ae090" : $_SESSION['icon'];

$p->appendContent(<<<HTML
  <body>
    {$p->getNavbar()}
    
  <div class="main">
   <div class="container center-align" style="border-radius:15px; background-image: url('https://api.scryfall.com/cards/{$banner}?format=image&version=art_crop'); background-size: cover; max-width: 670px;">
   <div class="row">
      <img class="circle" src="https://api.scryfall.com/cards/{$icon}?format=image&version=art_crop" style="width:100px;height:100px; margin-top:250px; box-shadow: 3px 3px 3px 3px white;">
      </div>
</div>
<div class="container center-align">
<div class="row">
<<<<<<< HEAD
<h2 style="margin-top: 0px">Profil de {$pseudo}</h2>

  <ul class="collapsible col s12 m12 l6" data-collapsible="accordion">
    <li>
      <div class="collapsible-header waves-effect"><p>Pseudo : <b>{$pseudo}</b></p></div>
=======
<h2 style="margin-top: 0px">Profil de {$_SESSION['pseudo']}</h2>

  <ul class="collapsible col s12 m12 l6" data-collapsible="accordion">
    <li>
      <div class="collapsible-header waves-effect"><p>Pseudo : <b>{$_SESSION['pseudo']}</b></p></div>
>>>>>>> b81656bb6c2b21e1c93b2dfdbce1cb070a8ca50d
      <div class="collapsible-body">
      <p>Procéder au changement de son pseudo :</p>
      <form id="ChangePseudo" name="ChangePseudo" action="index.php?route=changePseudo" method="post">
      <div class="row">
                  <div class="input-field col s12">
                     <input id="newPseudo" name="newPseudo" type="text" class="validate">
                       <label for="newPseudo">Nouveau Pseudo</label>
                  </div>
                 </div>
                 <p class="row">
                  <div class="input-field col s12">
                     <input id="passPseudo" name="passPseudo" type="password" class="validate">
                       <label for="passPseudo">Veuillez entrer votre mot de passe</label>
      </div>
      <div class ="row">
        <button class="btn waves-effect green disabled" id="pseudoButton" type="submit">Changer
          <i class="ss ss-mmq"></i> 
        </button>
      </div>
      </form>
      </div>
    </li>
    <li>
      <div class="collapsible-header waves-effect"><p>Mail : <b>{$_SESSION['mail']}</b></p></div>
      <div class="collapsible-body">
      <p>Procéder au changement de son mail :</p>
      <form id="ChangeMail" name="ChangeMail" action="index.php?route=changeMail" method="post">
      <div class="row">
                  <div class="input-field col s12">
                     <input id="newMail" name="newMail" type="email" class="validate">
                       <label for="newMail">Nouveau Mail</label>
                  </div>
                 </div>
                 <div class="row">
                  <div class="input-field col s12">
                     <input id="passMail" name="passMail" type="password" class="validate">
                       <label for="passMail">Veuillez entrer votre mot de passe</label>
      </div>
      <div class ="row">
        <button class="btn waves-effect green disabled" id="mailButton" type="submit">Changer
          <i class="ss ss-van"></i> 
        </button>
      </div>
      </form>
</div>
    </li>
    <li>
      <div class="collapsible-header waves-effect"><p>Mot de passe</p></div>
      <div class="collapsible-body">
      <p>Procéder au changement de son mot de passe :</p>
      <form id="ChangePass" name="ChangePass" action="index.php?route=changePass" method="post">
      <div class="row">
                  <div class="input-field col s12">
                     <input id="passOld" name="passOld" type="password" class="validate">
                       <label for="passOld">Veuillez entrer votre ancien mot de passe</label>
                       </div>
      </div>
      <div class="row">
                  <div class="input-field col s12">
                     <input id="passNew" name="passNew" type="password" class="validate" required>
                       <label for="passNew">Veuillez entrer votre nouveau mot de passe</label>
                  </div>
                </div>
                <div class="row">
                  <div class="input-field col s12">
                     <input id="passNewVerif" type="password" class="validate" required>
                       <label for="passNewVerif">Veuillez vérifier votre nouveau mot de passe</label>
                  </div>
                </div>
                <p class="red-text" id="VerifPass"></p>
      <div class ="row">
        <button class="btn waves-effect green disabled" id="passButton" type="submit">Changer
          <i class="ss ss-ust"></i> 
        </button>
      </div>
      </form>
</div>
    </li>
  </ul>
        
<ul class="collapsible col s12 m12 l6" data-collapsible="accordion">
    <li>
      <div class="collapsible-header waves-effect"><p>Bannière de profil : <b>{$bannerName}</b><p></div>
      <div class="collapsible-body">
      <p>Procéder au changement de sa banière :</p>
      <form id="ChangeBanner" name="ChangeBanner" action="index.php?route=changeBanner" method="post">
      <div class="row">
                  <div class="input-field col s12">
                     <input id="cardNameBanner" name="cardNameBanner" type="text" class="validate">
                       <label for="cardNameBanner">Nom de la carte</label>
                  </div>
                 </div>
                 <div class ="row">
        <button class="btn waves-effect green disabled" id="bannerButton" type="submit">Changer
          <i class="ss ss-me2"></i> 
        </button>
      </div>
      </form>
      </div>
    </li>
    <li>
      <div class="collapsible-header waves-effect"><p>Icone de profil : <b>{$iconName}</b></p></div>
      <div class="collapsible-body">
      <p>Procéder au changement de son icone :</p>
      <form id="ChangeIcon" name="ChangeIcon" action="index.php?route=changeIcon" method="post">
      <div class="row">
                  <div class="input-field col s12">
                     <input id="cardNameIcon" name="cardNameIcon" type="text" class="validate">
                       <label for="cardNameIcon">Nom de la carte</label>
                  </div>
                 </div>
                 <div class ="row">
        <button class="btn waves-effect green disabled" id="iconButton" type="submit">Changer
          <i class="ss ss-pgru"></i> 
        </button>
      </div>
      </form>
</div>
    </li>
    <li>
      <div class="collapsible-header waves-effect"><p>Suppression de compte</p></div>
      <div class="collapsible-body">
      <p>Procéder à la suppression de son compte :</p>
      <form id="DeleteAccount" name="DeleteAccount" action="index.php?route=deleteAccount" method="post">
                 <div class="row">
                  <div class="input-field col s12">
                     <input id="passAccount" name="passAccount" type="password" class="validate">
                       <label for="passAccount">Veuillez entrer votre mot de passe</label>
      </div>
      <div class ="row">
        <button class="btn waves-effect red disabled" id="deleteButton" type="submit">Supprimer
          <i class="ss ss-pdep"></i> 
        </button>
      </div>
      </form>
</div>
    </li>
  </ul>
</div>

</div>
   </div>
  </body>
HTML
);

echo $p->toHTML();