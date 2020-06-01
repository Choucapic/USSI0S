<?php

session_start();
$p = new WebPage("Home");

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
  });   
JS
);

$pseudo = htmlspecialchars($_SESSION['pseudo']);

$p->appendContent(<<<HTML

<body>

  {$p->getNavbar()}
    
    <div class="main center-align">
        <div class="row">
            <h1>Bonjour {$pseudo} !</h1>
            <h3>Choisissez votre rubrique dans le menu à gauche</h3>
        </div>
    </div>

</body>
HTML
);

echo $p->toHTML();