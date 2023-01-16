<?php

require_once('templates/imports.php');

Compte::logoutIfRequested();

$page = new WebPage('connexion');

try {
            $u = Compte::createFromSession();
            header('Location: form.php?logout');
    /*$u = User::createFromSession() ;

    $p->appendContent(<<<HTML
        {$u->profile()}
        <a href='page1.php'>page 1</a>
        {$u->logoutForm($_SERVER['PHP_SELF'], 'Se déconnecter')}
HTML
) ;*/

} catch (NotInSessionException $e) {

            $form = Compte::loginForm('auth.php');
    }
include "templates/imports.php";

    $page->appendCSS(<<<CSS
    form input {
        width : 4em ;
    }
CSS
    );


    $page->appendContent(<<<HTML
                <form class="w-p100 pt-5 form-align center-block" name='auth' action='auth.php' method='POST' autocomplete='off' id="login">
                    <input type="hidden" name="anchor" value="">
                    <script>document.getElementById('anchor').value = location.hash;</script>
                    
                    <div class="form-group input-lg input-form ">
                        <label for="username" class="sr-only">
                            Nom d'utilisateur
                        </label>
                        <input type="text" class="form-control" id="username" name="login" placeholder="Nom d'utilisateur">
                    </div>
                    
                    <div class="form-group input-lg input-form">
                        <label for="password" class="sr-only">Mot de passe</label>
                        <input type="password" name="pass" id="password" value="" class="form-control"placeholder="Mot de passe">
                    </div>
                    
                    <div class="form-group">
                        <input type="hidden" name="code">
                    </div>

                    <div class="fh5co-cards">
                        <a class="float-center" href="inscriptionClient.php">nouveau client ?</a>
                    </div>
                    <div class="fh5co-cards">
                        <a class="float-center" href="inscriptionGarage.php">nouveau garage ?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary input-form" id="loginbtn">Connexion</button>

                </form>
HTML
    );


echo $page->toHTML();


/*                <div class="form-group clearfix">
                    <div class="checkbox-custom checkbox-inline checkbox-primary float-left rememberpass">
                        <input type="checkbox" id="rememberusername" name="rememberusername" value="1"  />
                        <label for="rememberusername">Se souvenir du nom d'utilisateur</label>
                    </div>
                </div>
*/
