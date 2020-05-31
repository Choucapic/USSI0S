<?php

class WebPage {
    /**
     * @var string Texte compris entre <head> et </head>
     */
    private $head  = null ;
    /**
     * @var string Texte compris entre <title> et </title>
     */
    private $title = null ;
    /**
     * @var string Texte compris entre <body> et </body>
     */
    private $body  = null ;

    /**
     * Constructeur
     * @param string $title Titre de la page
     */
    public function __construct($title=null) {
        $this->title = $title;
    }

    /**
     * Retourner le contenu de $this->body
     *
     * @return string
     */
    public function body() {
        return $this->body;
    }

    /**
     * Retourner le contenu de $this->head
     *
     * @return string
     */
    public function head() {
        return $this->head;
    }

    /**
     * Protéger les caractères spéciaux pouvant dégrader la page Web
     * @see http://php.net/manual/en/function.htmlentities.php
     * @param string $string La chaîne à protéger
     *
     * @return string La chaîne protégée
     */
    public static function escapeString($string) {
        return htmlentities($string,ENT_QUOTES | ENT_HTML5);
    }

    /**
     * Affecter le titre de la page
     * @param string $title Le titre
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Ajouter un contenu dans head
     * @param string $content Le contenu à ajouter
     *
     * @return void
     */
    public function appendToHead($content) {
        $this->head .= $content;
    }

    /**
     * Ajouter un contenu CSS dans head
     * @param string $css Le contenu CSS à ajouter
     *
     * @return void
     */
    public function appendCss($css) {
        $this->head .= '<style>' .$css. '</style>';
    }

    /**
     * Ajouter l'URL d'un script CSS dans head
     * @param string $url L'URL du script CSS
     *
     * @return void
     */
    public function appendCssUrl($url) {
        $this->head .= '<link rel="stylesheet" type="text/css" href="'. $url .'">';
    }

    /**
     * Ajouter un contenu JavaScript dans head
     * @param string $js Le contenu JavaScript à ajouter
     *
     * @return void
     */
    public function appendJs($js) {
        $this->head .= '<script>'.$js.'</script>';
    }

    /**
     * Ajouter l'URL d'un script JavaScript dans head
     * @param string $url L'URL du script JavaScript
     *
     * @return void
     */
    public function appendJsUrl($url) {
        $this->head .= '<script type="text/javascript" src="'. $url .'">'.'</script>';
    }

    /**
     * Ajouter un contenu dans body
     * @param string $content Le contenu à ajouter
     *
     * @return void
     */
    public function appendContent($content) {
        $this->body .= $content;
    }

    public function isConnected() {
        return (isset($_SESSION['IDPERS']));
    }

    /**
     * Produire la page Web complète
     *
     * @return string
     * @throws Exception si title n'est pas défini
     */
    public function toHTML(){
        if(is_null($this->title)){
            throw new Exception(__CLASS__ . ":title not set" );
        }

        $html = <<<HTML
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>{$this->title}</title>
    <script
  src="https://code.jquery.com/jquery-3.5.1.min.js"
  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
  crossorigin="anonymous"></script>
  
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link href="//cdn.jsdelivr.net/npm/keyrune@latest/css/keyrune.css" rel="stylesheet" type="text/css" />
    
    <link rel="stylesheet" href="css/css.css">
   {$this->head}
  </head>
        {$this->body()}    
</html>
HTML;
        return $html;
    }
}