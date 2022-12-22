<?php


use Cassandra\Date;

class OffreVoiture extends Entity
{
protected Date $dateDepot;
protected Date $dateDeVente;
protected float $prixVente;
protected float $prixPredit;
protected string $commentairePrix;
protected string $status;
protected String $imageV;
protected Date $dateExpiration;
protected String $marqueVehicule;
protected string $modelVehicule;
protected int $anneeVehicule;
protected float $prixFinal;
protected string $typeTransmission;
protected int $mileageVehicule;
protected string $typeCarburant;

    /**
     * @return Date
     */
    public function getDateDepot(): Date
    {
        return $this->dateDepot;
    }

    /**
     * @param Date $dateDepot
     */
    public function setDateDepot(Date $dateDepot): void
    {
        $this->dateDepot = $dateDepot;
    }

    /**
     * @return Date
     */
    public function getDateDeVente(): Date
    {
        return $this->dateDeVente;
    }

    /**
     * @param Date $dateDeVente
     */
    public function setDateDeVente(Date $dateDeVente): void
    {
        $this->dateDeVente = $dateDeVente;
    }

    /**
     * @return float
     */
    public function getPrixVente(): float
    {
        return $this->prixVente;
    }

    /**
     * @param float $prixVente
     */
    public function setPrixVente(float $prixVente): void
    {
        $this->prixVente = $prixVente;
    }

    /**
     * @return float
     */
    public function getPrixPredit(): float
    {
        return $this->prixPredit;
    }

    /**
     * @param float $prixPredit
     */
    public function setPrixPredit(float $prixPredit): void
    {
        $this->prixPredit = $prixPredit;
    }

    /**
     * @return string
     */
    public function getCommentairePrix(): string
    {
        return $this->commentairePrix;
    }

    /**
     * @param string $commentairePrix
     */
    public function setCommentairePrix(string $commentairePrix): void
    {
        $this->commentairePrix = $commentairePrix;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return String
     */
    public function getImageV(): string
    {
        return $this->imageV;
    }

    /**
     * @param String $imageV
     */
    public function setImageV(string $imageV): void
    {
        $this->imageV = $imageV;
    }

    /**
     * @return Date
     */
    public function getDateExpiration(): Date
    {
        return $this->dateExpiration;
    }

    /**
     * @param Date $dateExpiration
     */
    public function setDateExpiration(Date $dateExpiration): void
    {
        $this->dateExpiration = $dateExpiration;
    }

    /**
     * @return String
     */
    public function getMarqueVehicule(): string
    {
        return $this->marqueVehicule;
    }

    /**
     * @param String $marqueVehicule
     */
    public function setMarqueVehicule(string $marqueVehicule): void
    {
        $this->marqueVehicule = $marqueVehicule;
    }

    /**
     * @return string
     */
    public function getModelVehicule(): string
    {
        return $this->modelVehicule;
    }

    /**
     * @param string $modelVehicule
     */
    public function setModelVehicule(string $modelVehicule): void
    {
        $this->modelVehicule = $modelVehicule;
    }

    /**
     * @return int
     */
    public function getAnneeVehicule(): int
    {
        return $this->anneeVehicule;
    }

    /**
     * @param int $anneeVehicule
     */
    public function setAnneeVehicule(int $anneeVehicule): void
    {
        $this->anneeVehicule = $anneeVehicule;
    }

    /**
     * @return float
     */
    public function getPrixFinal(): float
    {
        return $this->prixFinal;
    }

    /**
     * @param float $prixFinal
     */
    public function setPrixFinal(float $prixFinal): void
    {
        $this->prixFinal = $prixFinal;
    }

    /**
     * @return string
     */
    public function getTypeTransmission(): string
    {
        return $this->typeTransmission;
    }

    /**
     * @param string $typeTransmission
     */
    public function setTypeTransmission(string $typeTransmission): void
    {
        $this->typeTransmission = $typeTransmission;
    }

    /**
     * @return int
     */
    public function getMileageVehicule(): int
    {
        return $this->mileageVehicule;
    }

    /**
     * @param int $mileageVehicule
     */
    public function setMileageVehicule(int $mileageVehicule): void
    {
        $this->mileageVehicule = $mileageVehicule;
    }

    /**
     * @return string
     */
    public function getTypeCarburant(): string
    {
        return $this->typeCarburant;
    }

    /**
     * @param string $typeCarburant
     */
    public function setTypeCarburant(string $typeCarburant): void
    {
        $this->typeCarburant = $typeCarburant;
    }

    /**
     * @return mixed
     */
    public function getTaxe()
    {
        return $this->taxe;
    }

    /**
     * @param mixed $taxe
     */
    public function setTaxe($taxe): void
    {
        $this->taxe = $taxe;
    }

    /**
     * @return int
     */
    public function getAutonomie(): int
    {
        return $this->autonomie;
    }

    /**
     * @param int $autonomie
     */
    public function setAutonomie(int $autonomie): void
    {
        $this->autonomie = $autonomie;
    }

    /**
     * @return mixed
     */
    public function getTailleMoteur()
    {
        return $this->tailleMoteur;
    }

    /**
     * @param mixed $tailleMoteur
     */
    public function setTailleMoteur($tailleMoteur): void
    {
        $this->tailleMoteur = $tailleMoteur;
    }
protected $taxe;
protected int $autonomie;
protected $tailleMoteur;

    protected static function createFromId(int $id)
    {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    SELECT *
    FROM Produit
    WHERE immatruculation = :id
SQL
        ) ;

        $stmt->execute([$id]) ;
        // Fetch des valeurs retournées.
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__) ;
    }

    protected static function getAll(): array
    {
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM OffreVoiture
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, Produit::class);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function persist(): bool
    {
        // TODO: Implement persist() method.
    }

    public static function supprimer(int $id)
    {
        $data = [
            'id' => $id
        ];
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
        DELETE FROM OffreVoiture
        WHERE immatriculation = :id;
        
SQL
        );

        $stmt->execute($data);
    }
    public static function getByIdGarage($id)
    {
        $data = [
            'id' => $id
        ];
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM OffreVoiture
            where id_garage = :id;
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute($data);

        return $stmt->fetchAll();
    }

    public static function addOffre(string $immatriculation, float $prixVente, string $marqueVehicule, string $modeleVehicule, string $anneeVehicule,string $typeTransmission, int $mileageVehicule, string $typeCarburant, $taxe, int $autonomie, $tailleMoteur){
        $data = [
            ':immatriculation'=>$immatriculation,
            ':prixVente'=>$prixVente,
            ':marqueVehicule'=>$marqueVehicule,
            ':modeleVehicule'=>$modeleVehicule,
            ':anneeVehicule'=>$anneeVehicule,
            ':typeTransmission'=>$typeTransmission,
            ':mileageVehicule'=>$mileageVehicule,
            ':typeCarburant'=>$typeCarburant,
            ':taxe'=>$taxe,
            ':autonomie'=>$autonomie,
            ':tailleMoteur'=>$tailleMoteur

        ];
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Produit (immatriculation,prixVente,marqueVehicule,modeleVehicule,anneeVehicule,typeTransmission,mileageVehicule,typeCarburant,taxe,autonomie,tailleMoteur)VALUES (:immatriculation,:prixVente,:marqueVehicule,:modeleVehicule,:anneeVehicule,:typeTransmission,:mileageVehicule,:typeCarburant,:taxe,:autonomie,:tailleMoteur);
SQL
        );
        $stmt->execute($data);

    }
}