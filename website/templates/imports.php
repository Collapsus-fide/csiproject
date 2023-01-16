<?php

declare(strict_types=1);
include_once "class/Compte.class.php";
include_once "class/Webpage.class.php";
$page = new WebPage('CSI');
$page->appendCssUrl("css/style.css");
$connected = false;
if (Compte::isConnected()) {
    $connected = true;
    //header("Location: http://{$_SERVER['SERVER_NAME']}/".dirname($_SERVER['PHP_SELF'])."/form.php?logout") ;
    //die() ;
}

try {
    $user = Compte::createFromSession();
    $type = $user->isGarage();

} catch (NotInSessionException $e) {
} catch (Exception $e) {

}



$page->appendToHead(<<<HTML
<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title>index</title>
HTML
);

$page->appendCssUrl("bootstrap/css/bootstrap.css");
$page->appendCssUrl("fontAwesome/css/fontawesome.css");
$page->appendCssUrl('css/font-awesome.min.css');

$page->appendJsUrl("js/jquery-3.6.0.js");
$page->appendJsUrl("fontAwesome/js/fontAwesome.js");
$page->appendJsUrl("bootstrap/js/bootstrap.js");
$page->appendJsUrl("bootstrap/js/bootstrap.js");

include "navBar.php";