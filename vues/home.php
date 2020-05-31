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

$p->appendContent(<<<HTML

<body>

  {$p->getNavbar()}
    
    <div class="main center-align">
        <div class="row">
            <h1>Bonjour {$_POST['pseudo']} !</h1>
        </div>
    </div>

</body>
HTML
);

echo $p->toHTML();