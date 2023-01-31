<?php
include_once "templates/imports.php";
include_once "class/OffreVoitureArchivage.class.php";
include_once "class/PropositionAchat.class.php";

if ($type) {

    $offres = OffreVoitureArchivage::getArchivesByIdGarage($user->idcompte);

    $page->appendContent(<<<HTML

<form action="creerOffre.php" name="nouvelleOffre" style="margin-top: 100px">
<input class="btn btn-primary"type="submit" name="view" value="New offer">
</form>
 <table>
  <thead>
    <tr>
        <th colspan="4">Archives</th>
    </tr>
    <tr>
        <th scope="col">date</th>
        <th scope="col">Price</th>
        <th scope="col">Brand</th>
        <th scope="col">Model</th>
        <th scope="col">Year</th>
        <th scope="col">Transmission</th>
        <th scope="col">Mileage</th>
        <th scope="col">Fuel Type</th>
        <th scope="col">Estimated Price</th>
        <th scope="col">Category</th>
        <th scope="col">Estimation - Final price</th>
        
        <th scope="col"></th>
    </tr>
  </thead>
<tbody>
HTML
    );
    foreach ($offres as $offre) {
        $bilan = $offre->getPrixPredit()-$offre->getPrixFinal();
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
HTML
        );
        if ($offre->getPrixFinal()){
            $page->appendContent(<<<HTML
<td>{$bilan}</td>
HTML
            );

        }else{
            $page->appendContent(<<<HTML
<td>Offre expirée</td>
HTML
            );
        }
        $page->appendContent(<<<HTML
      <td><form action="offreVoiture.php" method="post">
      <input type="hidden" name="id" id="id" value="{$offre->getImmatriculation()}">
    <input class="btn" type="submit" name="upvote" value="view" />
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
