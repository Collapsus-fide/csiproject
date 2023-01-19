<?php
include_once "templates/imports.php";

if(isset($_POST["inscription"])){
    Compte::signUpRequestClient($_POST["username"],$_POST["mdp"],$_POST["email"],$_POST["adresse"],$_POST["tel"],$_POST["prenom"],$_POST["nom"]);
}else{
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
    <input type="text" name="adresse">
</label>
 <label>
phone
    <input type="text" name="tel">
</label>
<label>
lastname
    <input type="text" name="nom">
</label>
   <label>
firstname
    <input type="text" name="prenom">
</label>
<input type="submit" name="inscription" value="sign up">
</form>
HTML
    );
}

echo $page->toHTML();
