<?php

session_start();
$p = new WebPage("My Collection");
$pseudo = htmlspecialchars($_SESSION['pseudo']);

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
    $('select').formSelect();
    
    $("#cardName").autocomplete({
        source: "class/autocompleteImageSearch.php",
        minLength: 3,
        select: function( event, ui ) {
            $("#addCardButton").removeClass("disabled");
            $( "#cardName-id" ).val( ui.item.uuid );
            whatExtension(ui.item.printings, ui.item.uuid);
        }
    }).autocomplete().data("uiAutocomplete")._renderItem = function(ul, item) { 
         var script = document.createElement("script");
         var size = "200px";
         var url = "https://api.scryfall.com/cards/" + item.image + "?format=image";
         script.innerHTML = '$("#' + item.uuid + '").tooltip({html:"<img width='+ size +' src='+ url +'>"});';
         return $("<li>") 
         .append(item.label + '<i class="ss ss-van" data-position="right" id="' + item.uuid + '"></i>').append(script)
         .appendTo(ul); };
  });   

    function whatExtension(printings, uuid) {
        var arr = printings.split(",");
        $("#extension").html('<option value="" disabled selected>Extension</option>');
        for(var i = 0; i < arr.length; i++) {
          $.ajax({
        url: "class/populateExtensions.php",
        method:"GET",
        data:'set='+ arr[i],
        dataType:"json",
        })
        .done(function(response) {
            $("#extension").append('<option value="' + response[0].name +'">' + response[0].name + ' <i class="ss ss-2x ss-' + response[0].code.toLowerCase() +'"></i> </option>');
            $('select').formSelect();
        });
        }
        
        $("#extension").change(function() {
            $.ajax({
        url: "class/getFoilParameters.php",
        method:"GET",
        data:'uuid='+ uuid +'&setName=' + $("#extension").val(),
        dataType:"json",
        })
        .done(function(response) {
            var textFoil = "";
            if (response[0]["hasFoil"] == "1" && response[0]["hasNonFoil"] == "1") {
                textFoil = "Cette carte est disponible en : Foil, Non Foil";
                $(".switch").prop('hidden', false);
            } else if (response[0]["hasFoil"] == "1") {
                textFoil = "Cette carte est disponible en : Foil";
                $("#isFoil").prop('checked', true);
                $(".switch").prop('hidden', true);
            } else if (response[0]["hasNonFoil"] == "1") {
                textFoil = "Cette carte est disponible en : Non Foil";
                $("#isFoil").prop('checked', false);
                $(".switch").prop('hidden', true);
            }
            $("#foilParameters").html(textFoil);
        });
    });
    }
JS
);

$p->appendContent(<<<HTML

<body>

  {$p->getNavbar()}
    
    <div class="main center-align">
        <div class="row">
            <h3>Collection de {$pseudo}</h3> 
            <div class="col l6 s12"><p>Nombre de cartes dans la collection : {$cardsNumber}</p></div>
            <div class="col l6 s12"><p>Prix de la collection : {$collectionPrice}€</p></div>
        </div>
        <div class="container">
        <div class="row">
        <ul class="collapsible col s12" data-collapsible="accordion">
    <li>
      <div class="collapsible-header waves-effect"><p>Ajout de carte</p></div>
      <div class="collapsible-body">
      <form id="AddCard" name="AddCard" action="index.php?route=addCard" method="post">
      <div class="row">
                  <div class="input-field col l6 s12">
                     <input id="cardName" type="text" required>
                       <label for="cardName">Nom de la carte</label>
                       <input type="hidden" name="cardName" id="cardName-id">
                  </div>
                  <div class="input-field col l6 s12">
                    <select id="extension" name="extension" class="validate">
                        <option value="" disabled selected>Extension</option>
                    </select>
    <label>Extension</label>
  </div>
                 </div>
                 <div class="row">
                 <div class="col l6 s12">
                 <p id="foilParameters"></p>
                    <div class="switch">
                     <label>
                          Non foil
                          <input type="checkbox" id="isFoil" name="isFoil" value="Yes">
                          <span class="lever"></span>
                          Foil
                        </label>
                     </div>
                 </div>
                  <div class="input-field col l6 s12">
                     <input id="quantity" name="quantity" type="number" class="validate" min="1" step="1" required>
                       <label for="quantity">Quantité</label>
                  </div>
                 </div>
                 <div class ="row">
        <button class="btn waves-effect green disabled" id="addCardButton" type="submit">Ajouter
          <i class="ss ss-past"></i> 
        </button>
      </div>
      </form>
      </div>
    </li>
  </ul>
  </div>
  
     <table>
        <thead>
          <tr>
              <th class="center-align">Carte</th>
              <th class="center-align">Extension</th>
              <th class="center-align">Foil ?</th>
              <th class="center-align">Quantité</th>
              <th class="center-align">Prix Unitaire</th>
              <th class="center-align">Commandes</th>
          </tr>
        </thead>

        <tbody>
         {$tableCollection}
        </tbody>
      </table>
    </div>
    </div>

</body>
HTML
);

echo $p->toHTML();