<?php

session_start();
$p = new WebPage("Home");

$pseudo = $_SESSION['pseudo'];
$mail = $_SESSION['mail'];

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

$p->appendContent(<<<HTML

<body>

  {$p->getNavbar()}
    <a href="#" data-target="slide-out" class="btn-floating btn-large waves-effect waves-light red sidenav-trigger"><i class="material-icons">add</i></a>
    
    <div class="main center-align">
        <div class="row">
            <h1>Bonjour {$pseudo} !</h1>
        </div>
    </div>

</body>
HTML
);

echo $p->toHTML();