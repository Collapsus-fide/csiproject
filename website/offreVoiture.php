<?php
include_once "templates/imports.php";
include_once "class/OffreVoiture.class.php";
include_once "class/PropositionAchat.class.php";


$id = $_POST['id'];
if (isset($_POST["supprimer"])){
    OffreVoiture::supprimer(intval($_POST["idOffre"]));
    header('Location: index.php');
}
if(isset($_POST["modifier"])){
    OffreVoiture::modifier(intval($_POST["idOffre"]),$_POST["commentaire"],$_POST["mileage"], $_POST["prix"]);
    header('Location: index.php');
}
$offre = OffreVoiture::createFromImmat($id);
$page->appendContent(<<<HTML
<table>
  <thead>
    <tr>
        <th colspan="9">car offer</th>
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
        $propositions = PropositionAchat::getPropByIdOffre($offre->getIdoffrevoiture());
        if (count($propositions)==0) {
            $page->appendContent(<<<HTML

    <td><form action="offreVoiture.php" method="post">
      <input type="hidden" name="idOffre" id="id" value="{$offre->getIdoffrevoiture()}">
      <input type="hidden" name="id" id="id" value="{$offre->getImmatriculation()}">
    <input class="btn" type="submit" name="supprimer" value="delete" />
</form>
</td>
<td><form action="offreVoiture.php" method="post">
<label>
Immatriculation 
<input type="text" name="commentaire" value="{$offre->getCommentairePrix()}">
</label>
<label>
mileage
<input type="number" name="mileage" value="{$offre->getMileageVehicule()}">
</label>
<label>
price
<input type="number" name="prix" value="{$offre->getPrixVente()}">

</label>
      
      <input type="hidden" name="id" id="id" value="{$offre->getIdoffrevoiture()}">
    <input class="btn" type="submit" name="modifier" value="change" />
</form>
</td>
}
HTML
            );
        }
$page->appendContent(<<<HTML
<table>
  <thead>
    <tr>
        <th colspan="9">offers</th>
    </tr>
    <tr>
        <th scope="col">Date</th>
        <th scope="col">value</th>
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
    <input class="btn" type="submit" name="refuser" value="decline" />
</form>
</td>
<td><form action="proposition.php" method="post">
      <input type="hidden" name="id" id="id" value="{$proposition->getIdPropositionAchat()}">
      <input type="hidden" name="prixFinal" id="prixFinal" value={$proposition->getMontant()}>
    <input class="btn" type="submit" name="accepter" value="accept" />
HTML
          );
      }elseif($proposition->getStatus()== "accepter") {

          $page->appendContent(<<<HTML
                <td>accepted</td>
HTML);

      }elseif($proposition->getStatus()== "refuser") {

          $page->appendContent(<<<HTML
                <td>refused</td>
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
    <p>send purchase proposal</p>
<form name="proposition" method="post" action="proposition.php">
<label>
    proposed amount
    <input name="montant" type="number" max="{$offre->getPrixVente()}" step="0.01">
    <input type="hidden" name="offre" value="{$offre->getIdoffrevoiture()}"
</label>
<input class="btn" type="submit" name="Envoyer" value="send" />
</form>
HTML
    );
}

echo $page->toHTML();