<?php
include_once "templates/imports.php";
include_once "class/OffreVoiture.class.php";
if($type) {
    if(isset($_POST["prediction"])) {
        $prixPredit = OffreVoiture::definirPrix($_POST["immatriculation"], $_POST["marque"], $_POST["model"], $_POST["annee"], $_POST["transmission"], intval($_POST["mileage"]), $_POST["carburant"], $_POST["taxe"], intval($_POST["autonomie"]), $_POST["tailleMoteur"]);
        $page->appendContent(<<<HTML
        <form action="creerOffre.php" method="post" name="nouvelleOffre" style="margin-top: 100px" >
        <input type="hidden" name="immatriculation" value="{$_POST["immatriculation"]}">
        <input type="hidden" name="marque" value="{$_POST["marque"]}">
        <input type="hidden" name="model" value="{$_POST["model"]}">
        <input type="hidden" name="annee" value="{$_POST["annee"]}">
        <input type="hidden" name="transmission" value="{$_POST["transmission"]}">
        <input type="hidden" name="mileage" value="{$_POST["mileage"]}">
        <input type="hidden" name="immatriculation" value="{$_POST["immatriculation"]}">
        <input type="hidden" name="carburant" value="{$_POST["carburant"]}">
        <input type="hidden" name="taxe" value="{$_POST["taxe"]}">
        <input type="hidden" name="autonomie" value="{$_POST["autonomie"]}">
        <input type="hidden" name="tailleMoteur" value="{$_POST["tailleMoteur"]}">
        <input type="hidden" name="prixPredit" value="{$prixPredit}">
        <input type="hidden" name="idGarage" value="{$user->idcompte}">

<label>
price
 <input type="number" name="prixVente" value="{$prixPredit}">
</label>
<label>
comment
 <input type="text" name="commentaire" placeholder="Comment">
</label>
       
        <input class="btn btn-primary"type="submit" name="creerOffre" value="deposer offre">
</form>
HTML
        );
    }elseif(isset($_POST["creerOffre"])){
        OffreVoiture::addOffre($_POST["immatriculation"], $_POST["prixVente"],$_POST["marque"], $_POST["model"], $_POST["annee"], $_POST["transmission"], $_POST["mileage"], $_POST["carburant"], $_POST["taxe"], $_POST["autonomie"], $_POST["tailleMoteur"],$_POST["prixPredit"],$_POST["idGarage"],$_POST["commentaire"] );
    }else {
        $page->appendContent(<<<HTML


<form action="creerOffre.php" method="POST" name="nouvelleOffre" style="margin-top: 100px">
<label>
immatriculation
<input type="text" name="immatriculation">
</label>
<label>
marque
<input type="text" name="marque">
</label>
<label>
model
<input type="text" name="model">
</label>
<label>
année
<input type="number" name="annee">
</label>
<p>transmission</p>

<label> 
  <input name="transmission" type="radio" value="Automatic" checked>
  Automatique
            </label>

<label> 
  <input name="transmission" type="radio" value="Manual">
            Manuelle
</label>

<label> 
  <input name="transmission" type="radio" value="Semi-Auto">
            Semi-auto
</label>
<label> 
  <input name="transmission" type="radio" value="Other.1">
Autre
</label>
<label>
kilometrage
<input type="number" name="mileage">
</label>
<p>Carburant</p>

<label> 
  <input name="carburant" type="radio" value="Diesel" checked>
  Diesel
            </label>

<label> 
  <input name="carburant" type="radio" value="Petrol">
            Essence
</label>

<label> 
  <input name="carburant" type="radio" value="Electric">
            Electrique
</label>
<label> 
  <input name="carburant" type="radio" value="Hybrid">
Hybride
</label>
label> 
  <input name="carburant" type="radio" value="Other">
Autre
</label>
<label>
autonomie
<input type="number" name="autonomie">
</label>
<label>
taxe
<input type="number" name="taxe">
</label>
<label>
tailleMoteur
<input type="number" name="tailleMoteur">
</label>
<input class="btn btn-primary"type="submit" name="prediction" value="deposer offre">
</form>
HTML
        );
    }
}else{
    $page->appendContent(<<<HTML
<p>accès refusé</p>
HTML
    );
}
echo $page->toHTML();