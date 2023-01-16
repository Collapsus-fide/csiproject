<?php
include_once "templates/imports.php";

if(isset($_POST["inscription"])){
    Compte::signUpRequestGarage();
}else {
    $page->appendContent(<<<HTML
<form action="inscriptionClient.php" method="post" name="inscription" style="margin-top: 100px">
<label>
Nom d'utilisateur
    <input type="text" name="username">
</label>
   <label>
   mot de passe
    <input type="password" name="mdp">
</label>
<label>
email
    <input type="email" name="email">
</label>
   <label>
adresse
    <input type="text" name="nom">
</label>
 <label>
telephone
    <input type="text" name="tel">
</label>
<label>
Nom du gerant
    <input type="text" name="nomGerant">
</label>
   <label>
prenom du gerant
    <input type="text" name="prenomGerant">
</label>
<label>
nom du garage    <input type="text" name="nomGarage">
</label>
<label>
Siret
    <input type="text" name="siret">
    <input type="submit" name="inscription" value="inscription">
</label>
</form>
HTML
    );
}
echo $page->toHTML();
