<?php

session_start();
$p = new WebPage("Home");
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
  });   
JS
);

$p->appendContent(<<<HTML

<body>

  {$p->getNavbar()}
    
    <div class="main center-align">
        <div class="row">
            <h3>Collection de {$pseudo}</h3> 
        </div>
    </div>

</body>
HTML
);

echo $p->toHTML();