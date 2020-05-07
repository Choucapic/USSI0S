<?php

require_once 'autoload.inc.php';

$p = new WebPage("Index");

$p->appendContent(<<<HTML

<h1> Site en construction </h1>
HTML
);

echo $p->toHTML();