<?php
include_once "templates/imports.php";
include_once "class/OffreVoiture.class.php";
$offres = OffreVoiture::getOffresDispo();
$page->appendContent(<<<HTML
 <table>
  <thead>
    <tr>
        <th colspan="4">car offers</th>
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
        <th scope="col">Category</th>
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
      <td>{$offre->getCategorie()}</td>
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


echo $page->toHTML();
