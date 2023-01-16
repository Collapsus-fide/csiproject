<?php



class OffreVoiture
{
    protected int $id;


    protected string $immatriculation;
    protected string $dateDepot;
    protected ?string $dateDeVente;
    protected float $prixVente;
    protected float $prixPredit;
    protected string $commentairePrix;
    public string $status;
    public string $categorie;

    /**
     * @return string
     */
    public function getCategorie(): string
    {
        return $this->categorie;
    }

    /**
     * @param string $categorie
     */
    public function setCategorie(string $categorie): void
    {
        $this->categorie = $categorie;
    }
    protected string $imageV;
    protected string $dateExpiration;
    protected string $marqueVehicule;
    protected string $modelVehicule;
    protected int $anneeVehicule;
    protected ?float $prixFinal;
    protected string $typeTransmission;
    protected int $mileageVehicule;
    protected string $typeCarburant;
    protected int $idgarage;

    /**
     * @return int
     */
    public function getIdgarage(): int
    {
        return $this->idgarage;
    }

    /**
     * @param int $idgarage
     */
    public function setIdgarage(int $idgarage): void
    {
        $this->idgarage = $idgarage;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getImmatriculation(): string
    {
        return $this->immatriculation;
    }

    /**
     * @param string $immatriculation
     */
    public function setImmatriculation(string $immatriculation): void
    {
        $this->immatriculation = $immatriculation;
    }
    /**
     * @return string
     */
    public function getDateDepot(): String
    {
        return $this->dateDepot;
    }

    /**
     * @param DateTime $datedepot
     */
    public function setDateDepot(String $dateDepot): void
    {
        $this->dateDepot = $dateDepot;
    }

    /**
     * @return string
     */
    public function getDateDeVente(): string
    {
        return $this->dateDeVente;
    }

    /**
     * @param string $dateDeVente
     */
    public function setDateDeVente(string $dateDeVente): void
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
     * @return DateTime
     */
    public function getDateExpiration(): DateTime
    {
        return $this->dateExpiration;
    }

    /**
     * @param DateTime $dateExpiration
     */
    public function setDateExpiration(DateTime $dateExpiration): void
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
    public function setTypeCarburant( string $typeCarburant): void
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

    public static function createFromId( $id)
    {
        $stmt = myPDO::getInstance()->prepare(<<<SQL
    SELECT *
    FROM offrevoiture
    WHERE immatriculation = :id
SQL
        );

        $stmt->execute([$id]);
        // Fetch des valeurs retournées.
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        return $stmt->fetch();
    }

    public static function getAll(): array
    {
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM OffreVoiture
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, OffreVoiture::class);
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

    public static function getOffresByIdGarage($id)
    {
        $data = [
            'id' => $id
        ];
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM OffreVoiture
            where idgarage = :id;
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute($data);

        return $stmt->fetchAll();
    }

    public static function addOffre(string $immatriculation, float $prixVente, string $marqueVehicule, string $modeleVehicule, string $anneeVehicule, string $typeTransmission, int $mileageVehicule, string $typeCarburant, $taxe, int $autonomie, $tailleMoteur, $prixPredit, $idGarage)
    {

        //todo appel ia pour categ
        $categ = '1';
        $data = [
            ':immatriculation' => $immatriculation,
            ':prixVente' => $prixVente,
            ':marqueVehicule' => $marqueVehicule,
            ':modeleVehicule' => $modeleVehicule,
            ':anneeVehicule' => $anneeVehicule,
            ':typeTransmission' => $typeTransmission,
            ':mileageVehicule' => $mileageVehicule,
            ':typeCarburant' => $typeCarburant,
            ':taxe' => $taxe,
            ':autonomie' => $autonomie,
            ':tailleMoteur' => $tailleMoteur,
            ':prixPredit' => $prixPredit,
            ':idGarage' => $idGarage,
            ':categ' => $categ

        ];
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO offrevoiture (immatriculation,"prixVente","marqueVehicule","modelVehicule","anneeVehicule","typeTransmission","mileageVehicule","typeCarburant",taxe,autonomie,"tailleMoteur", "prixPredit",idgarage,categorie)VALUES (:immatriculation,:prixVente,:marqueVehicule,:modeleVehicule,:anneeVehicule,:typeTransmission,:mileageVehicule,:typeCarburant,:taxe,:autonomie,:tailleMoteur, :prixPredit, :idGarage, :categ);
SQL
        );
        $stmt->execute($data);

    }
    public static function getOffresDispo(){
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
            SELECT immatriculation,"dateDepot","marqueVehicule","modelVehicule","anneeVehicule","typeCarburant","typeTransmission","mileageVehicule",autonomie,"tailleMoteur","prixVente",categorie
            FROM OffreVoiture 
            Where status = 'disponible'
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, "OffreVoiture");
        $stmt->execute();

        return $stmt->fetchAll();
    }
public static function definirPrix(string $immatriculation, string $marqueVehicule, string $modeleVehicule, string $anneeVehicule, string $typeTransmission, int $mileageVehicule, string $typeCarburant, $taxe, int $autonomie, $tailleMoteur){

        return 100;
}
}