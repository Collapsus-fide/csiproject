<?php
include_once "templates/imports.php";
include_once "class/OffreVoiture.class.php";
include_once "class/PropositionAchat.class.php";

if ($_POST["offre"] && $_POST["montant"] && !($user->isGarage())){
    try {
        PropositionAchat::addProposition($_POST["offre"],$_POST["montant"],$user->idcompte);
        $page->appendContent(<<<HTML
<p>proposition envoyée</p>
HTML

        );
    }catch (Exception $e){
        $page->appendContent(<<<HTML
<p> offre :{$_POST["offre"]}</p>
<p> montant :{$_POST["montant"]}</p>
<p>Une erreur est survenue {$e->getMessage()}</p>
HTML

        );
    }
}elseif($_POST["id"] && ($user->isGarage())){
    if($_POST["accepter"] ){
        PropositionAchat::accepter($_POST["id"],intval($_POST["prixFinal"]) );
        header('Location: index.php');

    }elseif ($_POST["refuser"]){
        PropositionAchat::refuser($_POST["id"] );
        header('Location: index.php');
    }
}

echo $page->toHTML();