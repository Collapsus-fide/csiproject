<?php
include_once "templates/imports.php";

$page->appendContent(<<<HTML
<div class="container">
        <h1 style="text-align:center;">Bienvenue</h1>
    </div>
HTML
);
echo $page->toHTML();
