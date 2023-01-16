<?php
include_once "templates/imports.php";
include_once "class/OffreVoiture.class.php";
include_once "class/PropositionAchat.class.php";


$id = $_POST['id'];
$offre = OffreVoiture::createFromId($id);
$page->appendContent(<<<HTML
<table>
  <thead>
    <tr>
        <th colspan="9">car offer</th>
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
        <th scope="col">Category</th>

    </tr>
  </thead>
<tbody>
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

    </tr>
</tbody>
</table>
HTML
);
if ($type){
    if ($user->idcompte == $offre->getIdgarage()) {
        include_once "class/OffreVoiture.class.php";
        $propositions = PropositionAchat::getPropByIdOffre($offre->getImmatriculation());

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
HTML
            );
      if ($proposition->getStatus()== "soumis") {
          $page->appendContent(<<<HTML

    <td><form action="proposition.php" method="post">
      <input type="hidden" name="id" id="id" value="{$proposition->getIdPropositionAchat()}">
    <input class="btn" type="submit" name="refuser" value="refuser" />
</form>
</td>
<td><form action="proposition.php" method="post">
      <input type="hidden" name="id" id="id" value="{$proposition->getIdPropositionAchat()}">
    <input class="btn" type="submit" name="accepter" value="accepter" />
HTML
          );
      }elseif($proposition->getStatus()== "accepter") {

          $page->appendContent(<<<HTML
                <td>accepté</td>
HTML);

      }elseif($proposition->getStatus()== "refuser") {

          $page->appendContent(<<<HTML
                <td>refusé</td>
HTML);

      }
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
}elseif(Compte::isConnected()){
    $page->appendContent(<<<HTML
    <p>Envoyer une proposition d'achat</p>
<form name="proposition" method="post" action="proposition.php">
<label>
    Montant proposé.
    <input name="montant" type="number" min="{$offre->getPrixVente()}" step="0.01">
    <input type="hidden" name="offre" value="{$offre->getImmatriculation()}"
</label>
<input class="btn" type="submit" name="Envoyer" value="Envoyer" />
</form>
HTML
    );
}

echo $page->toHTML();