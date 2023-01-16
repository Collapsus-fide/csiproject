<?php
include_once "templates/imports.php";

if(isset($_POST["inscription"])){
    Compte::signUpRequestClient();
}else{
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
    <input type="text" name="adresse">
</label>
 <label>
telephone
    <input type="text" name="tel">
</label>
<label>
Nom
    <input type="text" name="nom">
</label>
   <label>
prenom
    <input type="text" name="prenom">
</label>
<input type="submit" name="inscription" value="inscription">
</form>
HTML
    );
}

echo $page->toHTML();
