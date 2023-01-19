<?php
include_once "templates/imports.php";

if(isset($_POST["inscription"])){
    Compte::signUpRequestGarage();
}else {
    $page->appendContent(<<<HTML
<form action="inscriptionClient.php" method="post" name="inscription" style="margin-top: 100px">
<label>
username
    <input type="text" name="username">
</label>
   <label>
password
    <input type="password" name="mdp">
</label>
<label>
mail
    <input type="email" name="email">
</label>
   <label>
adress
    <input type="text" name="nom">
</label>
 <label>
phone
    <input type="text" name="tel">
</label>
<label>
manager's lastname
    <input type="text" name="nomGerant">
</label>
   <label>
manager's firstname    <input type="text" name="prenomGerant">
</label>
<label>
garage's name    <input type="text" name="nomGarage">
</label>
<label>
Siret
    <input type="text" name="siret">
    <input type="submit" name="inscription" value="sign up">
</label>
</form>
HTML
    );
}
echo $page->toHTML();
