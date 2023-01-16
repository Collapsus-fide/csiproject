<?php



class PropositionAchat
{
    protected int $idpropositionachat;
    protected float $montant;
    protected string $dateproposition;
    protected string $status;

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


    public static function getPropByIdOffre(string $id){
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

    public static function accepter($id, $prixFinal){
        $data = [
            ':id' => $id,
            ':prixFinal' => $prixFinal
        ];
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    Update propositionachat
    Set status = 'accepter'
    WHERE idpropositionachat = :id
SQL
        );

        $stmt->execute([$id]);

        $stmt = myPDO::getInstance()->prepare(<<<SQL
    Update propositionachat
    Set status = 'refuser'
    WHERE idoffrevoiture = (select idoffrevoiture from propositionachat where idpropositionachat = :id) AND idpropositionachat != :id
SQL
        );

        $stmt->execute([$id]);


        $stmt = myPDO::getInstance()->prepare(<<<SQL
    Update offrevoiture
    Set status = 'vendu',
        "dateDeVente" = current_date,
        "prixFinal" = :prixFinal
    WHERE immatriculation = (select idoffrevoiture from propositionachat where idpropositionachat = :id) 
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