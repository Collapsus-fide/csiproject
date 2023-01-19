<?php



class PropositionAchat
{
    protected int $idpropositionachat;
    protected float $montant;
    protected string $dateproposition;
    protected string $status;
    protected ?int $idoffrevoiture;
    protected ?int $idoffrevoiturearchivage;

    /**
     * @return int|null
     */
    public function getIdoffrevoiturearchivage(): ?int
    {
        return $this->idoffrevoiturearchivage;
    }

    /**
     * @return int
     */
    public function getIdoffrevoiture(): ?int
    {
        return $this->idoffrevoiture;
    }

    /**
     * @return int
     */
    public function getIdPropositionAchat(): int
    {
        return $this->idpropositionachat;
    }

    /**
     * @param int $idPropositionAchat
     */
    public function setIdPropositionAchat(int $idPropositionAchat): void
    {
        $this->idPropositionAchat = $idPropositionAchat;
    }

    /**
     * @return float
     */
    public function getMontant(): float
    {
        return $this->montant;
    }

    /**
     * @param float $montant
     */
    public function setMontant(float $montant): void
    {
        $this->montant = $montant;
    }

    /**
     * @return string
     */
    public function getDateProposition(): string
    {
        return $this->dateproposition;
    }

    /**
     * @param string $dateProposition
     */
    public function setDateProposition(string $dateProposition): void
    {
        $this->dateproposition = $dateProposition;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }


    public static function getPropByIdOffre(int $id){
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    SELECT *
    FROM propositionachat
    WHERE idoffrevoiture = :id
SQL
        );

        $stmt->execute([$id]);
        // Fetch des valeurs retournées.
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }

    public static function createPropFromId($id){
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    select * from propositionachat
    WHERE idpropositionachat = :id
SQL
        );
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);

        return $stmt->fetch();
    }
    public static function addProposition( $offre, $montant, $client){
        $data = [
            ':offre' => $offre,
            ':montant' => $montant,
            ':client' => $client];

        $stmt = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO propositionachat (idoffrevoiture,montant,idclient)VALUES (:offre,:montant,:client);
SQL
        );
        $stmt->execute($data);

    }

    /**
     * @throws Exception
     */
    public static function accepter($id, int $prixFinal){
        $prop= PropositionAchat::createPropFromId($id);
        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d', time());
        $offre  = OffreVoiture::createFromId($prop->getIdoffrevoiture());
        $data = [
            ':immatriculation' => $offre->getImmatriculation(),
            ':datedepot' => $offre->getDateDepot(),
            ':datedevente' => $date,
            ':prixvente' => $offre->getPrixVente(),
            ':prixpredit' => $offre->getPrixPredit(),
            ':prixfinal' => $prop->getMontant(),
            ':commentaireprix' => $offre->getCommentairePrix(),
            ':imagev' => $offre->getImageV(),
            ':marquevehicule' => $offre->getMarqueVehicule(),
            ':modelvehicule' => $offre->getModelVehicule(),
            ':anneevehicule' => $offre->getAnneeVehicule(),
            ':typetransmission' => $offre->getTypeTransmission(),
            ':mileagevehicule' => $offre->getMileageVehicule(),
            ':typecarburant' => $offre->getTypeCarburant(),
            ':taxe' => $offre->getTaxe(),
            ':autonomie' => $offre->getAutonomie(),
            ':tailleMoteur' => $offre->getTailleMoteur(),
            ':idgarage' => $offre->getIdgarage(),
            ':categ' => $offre->getCategorie()
        ];

        $stmt = myPDO::getInstance()->prepare(<<<SQL
     insert into offrevoiturearchivage (immatriculation, datedepot, datedevente, prixvente, prixpredit, prixfinal, commentaireprix, imagev, marquevehicule, modelvehicule, anneevehicule, typetransmission, mileagevehicule, typecarburant, taxe, autonomie, taillemoteur, idgarage, categorie
     )                          Values (:immatriculation, :datedepot, :datedevente, :prixvente, :prixpredit, :prixfinal, :commentaireprix, :imagev, :marquevehicule, :modelvehicule, :anneevehicule, :typetransmission, :mileagevehicule, :typecarburant, :taxe, :autonomie, :tailleMoteur, :idgarage, :categ )


SQL
        );
        $stmt->execute($data);


        $data = [
            ':id' => $offre->getIdoffrevoiture(),
            ':idArchive' => intval(MyPDO::getInstance()->lastInsertId())
        ];
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    Update propositionachat
    Set status = 'accepter', 
        idoffrevoiture = null,
        "idvoffrevoiturearchivage" = :idArchive
    WHERE idoffrevoiture = :id
SQL
        );

        $stmt->execute($data);
        $data = [
            ':id' => $id,
            ':idArchive' => intval(MyPDO::getInstance()->lastInsertId())
        ];
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    Update propositionachat
    Set status = 'refuser',
        idoffrevoiture = null,
        "idvoffrevoiturearchivage" = :idArchive
    WHERE idoffrevoiture = (select idoffrevoiture from propositionachat where idpropositionachat = :id) AND idpropositionachat != :id
SQL
        );

        $stmt->execute($data);

        $data = [
            ':id' => $id,
            ':prixFinal' => $prixFinal
        ];
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    Update offrevoiture
    Set status = 'vendu',
        "dateDeVente" = current_date,
        "prixFinal" = :prixFinal
    WHERE idoffrevoiture = (select propositionachat.idoffrevoiture from propositionachat where idpropositionachat = :id) 
SQL
        );

        $stmt->execute($data);
        $data =[
            ':id'=> $offre->getIdoffrevoiture()
        ];

        $stmt = myPDO::getInstance()->prepare(<<<SQL
     delete from offrevoiture where  idoffrevoiture = :id 
     
SQL
        );

        $stmt->execute($data);



    }

    public static function refuser($id){
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    Update propositionachat
    Set status = 'refuser'
    WHERE idpropositionachat = :id
SQL
        );

        $stmt->execute([$id]);
    }

    public static function getPropByIdClient(string $id){
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    SELECT *
    FROM propositionachat
    WHERE idclient = :id
SQL
        );

        $stmt->execute([$id]);
        // Fetch des valeurs retournées.
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetchAll();
    }
}