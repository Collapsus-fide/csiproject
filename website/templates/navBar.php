<?php
declare(strict_types=1);
$page->appendCssUrl("css/navBar.css");
$page->appendJs(<<<JS
$(function () {
    $(window).on('scroll', function () {
        if ( $(window).scrollTop() > 10 ) {
            $('.navbar').addClass('active');
        } else {
            $('.navbar').removeClass('active');
        }
    });
});
JS
);

$page->appendContent(<<<HTML
    <header class="header">
    <nav class="navbar navbar-expand-lg fixed-top py-3">
    HTML
);
if ($type){
    $page->appendContent(<<<HTML
        <div class="container"><a href="#" class="navbar-brand text-uppercase font-weight-bold">sell your cars</a>

HTML
    );
}else{
    $page->appendContent(<<<HTML
        <div class="container"><a href="#" class="navbar-brand text-uppercase font-weight-bold">buy cars</a>

HTML
    );
}
$page->appendContent(<<<HTML
            <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler navbar-toggler-right"><i class="fa fa-bars"></i></button>
            
            <div id="navbarSupportedContent" class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a href="index.php" class="nav-link text-uppercase font-weight-bold">Home <span class="sr-only">(current)</span></a></li>
                    <li class="nav-item"><a href="catalog.php" class="nav-link text-uppercase font-weight-bold">catalogue</a></li>
HTML
);
if ($connected){
    $page->appendContent(<<<HTML
    <li class="nav-item"><a href="gestion.php" class="nav-link text-uppercase font-weight-bold">Mes offres</a></li>
<li class="nav-item"><a href="connexion.php?logout" class="nav-link text-uppercase font-weight-bold">Déconnexion</a></li>
HTML

    );}else{
    $page->appendContent(<<<HTML
 <li class="nav-item"><a href="connexion.php" class="nav-link text-uppercase font-weight-bold">Connexion</a></li>
HTML
    );
}
$page->appendContent(<<<HTML
                </ul>
            </div>
        </div>
    </nav>
</header>
<div style="margin-top: 100px"></div>
HTML
);

