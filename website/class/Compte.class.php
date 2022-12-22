<?php

if (file_exists('../autoload.include.php')) {
    require_once('../autoload.include.php');
}else if(file_exists('autoload.include.php')){
    require_once('autoload.include.php');
}

require_once('MyPDO.template.php') ;

class AuthenticationException extends Exception { }

class NotInSessionException extends Exception { }

/**
* Class Utilisateur liant la Platform à la BD.
*/
class Compte extends Entity {
    /**
     * Identifiant unique du User dans la base de données
     * @var string $id
     */
    public $idCompte = null ;

    /**
     * Nom
     * @var string $nomUtilisateur
     */
    protected $nom  = null ;


    /**
     * Numéro de téléphone
     * @var string $tel
     */
    protected $tel     = null ;
    
    /**
    * Adresse E-mail
    * @var string $mail
    */
    protected $mail     = null ;


    /**
     * adresse postale
     * @var String $adresse
     */
    protected $adresse = null;

    /**
     * Clé de session.
     */
    const session_key = "__client__" ;

    /**
     * Constructeur privé pour éviter de l'instancier.
     */
    private function __construct() {

    }

    protected static function createFromId(int $id){
        // voir createFromSession ou createFromAuth512
    }
    
    protected static function getAll() : array{
        return void;
    }
    
    public function persist(): bool{
        // voir saveIntoSession
    }


    /**
     * retourne les informations de l'utilisateur
     *
     * @return string|null code HTML.
     */
    public function profile() : ?string {
           /* // Affichage
            return <<<HTML
    <div><span>Login    </span> : <span>{$this->login}    </span></div>
    <div><span>Téléphone</span> : <span>{$this->phone}    </span></div>
    <div><span>E-mail   </span> : <span>{$this->mail}    </span></div>
    <div><span>Date de naissance</span> : <span>{$this->dateNaissance}    </span></div>*/
           return null;
HTML;
    }

    /**
     * formulaire de connexion
     * @param string $action URL (action) vers la cible du formulaire
     * @param string $submitText texte du bouton d'envoi
     *
     * @return string code HTML du formulaire
     */
    static public function loginForm(string $action, string $submitText = 'OK') : string {
        return <<<HTML
  <div  id="page-login-index" class="path-login chrome dir-ltr lang-fr yui-skin-sam yui3-skin-sam pagelayout-login course-1 context-1 notloggedin page-login-v2 layout-full page-dark">
    <div class="d-flex flex-row wrap bg-white justify-content-center position-sticky ">
        
        <div class="p-2 row-xs-12 col-lg-3">
            <div>
                <form class="w-p100 pt-5 form-align center-block" name='auth' action='$action' method='POST' autocomplete='off' id="login">
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
                        <a class="float-center" href="/login/forgot_password.php">Mot de passe oublié ?</a>
                        <a class="float-center" href="inscription.php">Pas encore inscrit ?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary input-form" id="loginbtn">Connexion</button>

                </form> 
            </div>
        </div>
    </div>
</div>
HTML;
    }

    /**
     * Validation de la connexion de l'utilisateur
     * @param array $data tableau contenant les clés 'login' et 'pass' associées au login et au mot de passe
     * @throws AuthenticationException si l'authentification échoue
     *
     * @return User utilisateur authentifié
     */
    public static function createFromAuth(array $data) : self {
        if (!isset($data['login']) || !isset($data['pass'])) {
            throw new AuthenticationException("Les paramètres Login et mot de passe sont vide.") ;
        }

        // Préparation de la requête
         $stmt = myPDO::getInstance()->prepare(<<<SQL
    SELECT *
    FROM client
    WHERE mail    = :login
    AND   sha512pass = :pass
SQL
    ) ;

        $stmt->execute(array(
            ':login' => $data['login'],
            ':pass'  => $data['pass'])) ;
        // Fetch des valeurs retournées.
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__) ;
        if (($client = $stmt->fetch()) !== false) {
            Session::start() ;
            $_SESSION[self::session_key]['connected'] = true ;
            return $client ;
        }
        else {
            throw new AuthenticationException("Couple Login/ mot de passe incorrect") ;
        }
    }

/**
     * Formulaire de déconnexion de l'utilisateur
     * @param string $text bouton de déconnexion
     * @param string $action URL (action) vers la cible du formulaire
     *
     * @return string Code HTML du formulaire.
     */
    public static function logoutForm(string $action, string $text) : string {
        $text = htmlspecialchars($text, ENT_COMPAT, 'utf-8') ;
        return <<<HTML
    <form action='$action' method='POST'>
    
        <a class="nav-link" type='submit' name='logout'>$text</a>
    
    </form>
HTML;
    }
    

    /**
     * Deconnexion : arret de la session en cours.
     */
    public static function logoutIfRequested() : void {
        if (isset($_REQUEST['logout'])) {
            Session::start() ;
            unset($_SESSION[self::session_key]) ;
        }
    }

    /**
     * Verrification du statut de la session en cours.
     *
     * @return bool True si l'uttilisateur est connecté correctement.
     */
    static public function isConnected() : bool {
        Session::start() ;
        return (   isset($_SESSION[self::session_key]['connected'])
                && $_SESSION[self::session_key]['connected'])
            || (   isset($_SESSION[self::session_key]['client'])
                && $_SESSION[self::session_key]['client'] instanceof self) ;
    }

    /**
     * Sauvegarde de l'objet Utilisateur dans la session en cours.
     */
    public function saveIntoSession() : void {
        // Mise en place de la session
        Session::start() ;
        // Mémorisation de l'Utilisateur
        $_SESSION[self::session_key]['client'] = $this ;
    }

    /**
     * Lecture de l'objet User dans la session
     * @throws NotInSessionException si l'objet n'est pas dans la session
     *
     * @return User
     */
    static public function createFromSession()
    {
        Session::start() ;
        if (isset($_SESSION[self::session_key]['client'])) {
            $u = $_SESSION[self::session_key]['client'] ;

            if (is_object($u) && get_class($u) == get_class()) {
                return $u ;
            }
        }
        throw new NotInSessionException() ;
    }

    static public function randString($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /**
     * Production d'un formulaire de connexion contenant un challenge. 
     * @param string $action URL cible du formulaire
     * @param string $submitText texte du bouton d'envoi
     *
     * @return string code HTML du formulaire
     */
    static public function loginFormSHA512(string $action, string $submitText = 'OK') : string {
        $texte_par_defaut = 'login' ;
        Session::start() ;

        $_SESSION[self::session_key]['challenge'] = Self::randString(16) ;

        return <<<HTML
<script type='text/javascript' src='js/sha512.js'></script>
<script type='text/javascript'>
function crypter(f, challenge) {
    if (f.login.value.length && f.pass.value.length) {
        f.code.value = CryptoJS.SHA512(CryptoJS.SHA512(f.pass.value).toString()+challenge+CryptoJS.SHA512(f.login.value).toString()).toString() ;
        f.login.value = f.pass.value = '' ;
        return true ;
    }
    return false ;
    
}
</script>
<div  id="page-login-index" class="path-login chrome dir-ltr lang-fr yui-skin-sam yui3-skin-sam pagelayout-login course-1 context-1 notloggedin page-login-v2 layout-full page-dark">
    <div class="d-flex flex-row wrap bg-white justify-content-center position-sticky ">
        
        <div class="p-2 row-xs-12 col-lg-3">
            <div>
                <form class="w-p100 pt-5 form-align center-block" name='auth' action='$action' method='POST' onSubmit="return crypter(this, '{$_SESSION[self::session_key]['challenge']}')" autocomplete='off' id="login">
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
                        <a class="float-center" href="/login/forgot_password.php">Mot de passe oublié ?</a>
                        <a class="float-center" href="inscription.php">Pas encore inscrit ?</a>
                    </div>
                    
                    <button type="submit" class="btn btn-primary input-form" id="loginbtn">Connexion</button>

                </form> 
            </div>
        </div>
    </div>
</div>
HTML;
    }


    /**
     * Fonction de validation du couple Login/mot de passe.
     * @param array $data 
     *
     * @return User utilisateur authentifié
     */
    public static function createFromAuthSHA512(array $data)
    {
        if (!isset($data['code'])) {
            throw new AuthenticationException("pas de login/pass fournis") ;
        }

        Session::start() ;
        // Préparation :
        // firstName, lastName, login, phone, mail, DATE_FORMAT(dateNaissance, '%d %m %Y')
        $stmt = MyPDO::getInstance();
        $stmt = $stmt->prepare(<<<SQL
    SELECT *
    FROM client
    WHERE SHA2(CONCAT(sha512pass, :challenge, SHA2(client.mail, 512)), 512) = :code;
SQL
    ) ;

        $stmt->execute(array(
            ':challenge' => isset($_SESSION[self::session_key]['challenge']) ? $_SESSION[self::session_key]['challenge'] : '',
            ':code'      => $data['code'])) ;
        
        unset($_SESSION[self::session_key]['challenge']) ;

        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__) ;
        if (($utilisateur = $stmt->fetch()) !== false) {
            return $utilisateur ;
        }
        else {
            throw new AuthenticationException("Login/pass incorrect") ;
        }
    }

    public static function SignUpForm(String $action){
        $p = new WebPage("inscription");
        include "templates/imports.php";
        $p->appendCssUrl("css/inscription.css");
        $p->appendContent(<<<HTML
    <!--
    Author: Colorlib
Author URL: https://colorlib.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
<title>Creative Colorlib SignUp Form</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- Custom Theme files -->
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<!-- //Custom Theme files -->
<!-- web font -->
<link href="//fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,700,700i" rel="stylesheet">
<!-- //web font -->
</head>
<body>
	<!-- main -->
	<div class="main-w3layouts wrapper">
		<h1>Creative SignUp Form</h1>
		<div class="main-agileinfo">
			<div class="agileits-top">
				<form action="signUpRequest.php" method="post">
					<input class="text" type="text" name="Nom" placeholder="Nom" required="">
					<input class="text" type="text" name="Prenom" placeholder="Prenom" required="">
					<input class="text email" type="email" name="mail" placeholder="Email" required="">
					<input class="text" type="password" name="password" placeholder="Password" required="">
					<input class="text w3lpass" type="password" name="password" placeholder="Confirm Password" required="">
					<div class="wthree-text">
						<label class="anim">
							<input type="checkbox" class="checkbox" required="">
							<span>I Agree To The Terms & Conditions</span>
						</label>
						<div class="clear"> </div>
					</div>
					<input type="submit" value="SIGNUP">
				</form>
				<p>Already have an Account? <a href="connexion.php"> Login Now!</a></p>
			</div>
		</div>
		<!-- copyright -->
		<div class="colorlibcopy-agile">
			<p>© 2018 Colorlib Signup Form. All rights reserved | Design by <a href="https://colorlib.com/" target="_blank">Colorlib</a></p>
		</div>
		<!-- //copyright -->
		<ul class="colorlib-bubbles">
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
			<li></li>
		</ul>
	</div>
	<!-- //main -->
</body>
</html>

HTML
        );

        return $p->toHTML();


    }
public static function signUpRequest(array $data){
    $stmt = MyPDO::getInstance();
    $stmt = $stmt->prepare(<<<SQL
insert into client(mail,sha512Pass,nom,prenom, points_fidelite,nb_commande_oubliée) VALUES (:mail,:pass,:nom,:prenom,0,0);
SQL
    ) ;

    $stmt->execute(array(
        ':nom' => $_REQUEST['Nom'],
        ':prenom' => $_REQUEST['Prenom'],
        ':mail' => $_REQUEST['mail'],
        ':pass'  => $_REQUEST['password'])) ;
}
}



