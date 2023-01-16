<?php
include_once "templates/imports.php";
include_once "class/OffreVoiture.class.php";
include_once "class/PropositionAchat.class.php";

if ($type){

    $offres = OffreVoiture::getOffresByIdGarage($user->idcompte);
    $page->appendContent(<<<HTML

<form action="creerOffre.php" name="nouvelleOffre" style="margin-top: 100px">
<input class="btn btn-primary"type="submit" name="Voir" value="Nouvelle Offre">
</form>
 <table>
  <thead>
    <tr>
        <th colspan="4">car offers</th>
    </tr>
    <tr>
        <th scope="col">Mise en vente</th>
        <th scope="col">Price</th>
        <th scope="col">Brand</th>
        <th scope="col">Model</th>
        <th scope="col">Year</th>
        <th scope="col">Transmission</th>
        <th scope="col">Mileage</th>
        <th scope="col">Fuel Type</th>
        <th scope="col">Estimated Price</th>
        <th scope="col">Category</th>
        <th scope="col">status</th>
        
        <th scope="col"></th>
    </tr>
  </thead>
<tbody>
HTML
    );
    foreach ($offres as $offre){
        $page->appendContent(<<<HTML
<tr>
      <td>{$offre->getDateDepot()}</td>
      <td>{$offre->getPrixVente()}</td>
      <td>{$offre->getMarqueVehicule()}</td>
      <td>{$offre->getModelVehicule()}</td>
      <td>{$offre->getAnneeVehicule()}</td>
      <td>{$offre->getTypeTransmission()}</td>
      <td>{$offre->getMileageVehicule()}</td>
      <td>{$offre->getTypeCarburant()}</td>
      <td>{$offre->getPrixPredit()}</td>
      <td>{$offre->getCategorie()}</td>
       <td>{$offre->getStatus()}</td>
      <td><form action="offreVoiture.php" method="post">
      <input type="hidden" name="id" id="id" value="{$offre->getImmatriculation()}">
    <input class="btn" type="submit" name="upvote" value="Voir" />
</form>
</td>

    </tr>
HTML
        );
    }
    $page->appendContent(<<<HTML
</tbody>
</table>
HTML
    );



}else{
    $propositions = PropositionAchat::getPropByIdClient($user->idcompte);
    $page->appendContent(<<<HTML
<table>
  <thead>
    <tr>
        <th colspan="9">proposition d'achat</th>
    </tr>
    <tr>
        <th scope="col">Date</th>
        <th scope="col">montant</th>
        <th scope="col"></th>
        <th scope="col"></th>

    </tr>
  </thead>
<tbody>
HTML
    );

    foreach ($propositions as $proposition){
        $page->appendContent(<<<HTML
<tr>
      <td>{$proposition->getDateProposition()}</td>
      <td>{$proposition->getMontant()}</td>
        <td>{$proposition->getStatus()}</td>
HTML
        );

        $page->appendContent(<<<HTML
</form>
</td>

    </tr>
HTML
        );
    }
    $page->appendContent(<<<HTML
</tbody>
</table>
HTML
    );
}

echo $page->toHTML();
