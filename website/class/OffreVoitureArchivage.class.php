<?php

class OffreVoitureArchivage
{
    private int $idoffrevoiturearchivage;
    protected string $immatriculation;
    protected string $datedepot;
    protected ?string $datedevente;
    protected float $prixvente;
    protected float $prixpredit;
    protected string $commentaireprix;
    public string $status;
    public string $categorie;
    protected float $taxe;
    protected float $autonomie;
    protected float $tailleMoteur;
    protected string $imageV;
    protected string $marquevehicule;
    protected string $modelvehicule;
    protected int $anneevehicule;
    protected ?float $prixfinal;
    protected string $typetransmission;
    protected int $mileagevehicule;
    protected string $typecarburant;
    protected int $idgarage;

    /**
     * @return int
     */
    public function getIdoffrevoiturearchivage(): int
    {
        return $this->idoffrevoiturearchivage;
    }

    /**
     * @return string
     */
    public function getImmatriculation(): string
    {
        return $this->immatriculation;
    }

    /**
     * @return string
     */
    public function getDatedepot(): string
    {
        return $this->datedepot;
    }

    /**
     * @return string|null
     */
    public function getDatedevente(): ?string
    {
        return $this->datedevente;
    }

    /**
     * @return float
     */
    public function getPrixvente(): float
    {
        return $this->prixvente;
    }

    /**
     * @return float
     */
    public function getPrixpredit(): float
    {
        return $this->prixpredit;
    }

    /**
     * @return string
     */
    public function getCommentaireprix(): string
    {
        return $this->commentaireprix;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getCategorie(): string
    {
        return $this->categorie;
    }

    /**
     * @return float
     */
    public function getTaxe(): float
    {
        return $this->taxe;
    }

    /**
     * @return float
     */
    public function getAutonomie(): float
    {
        return $this->autonomie;
    }

    /**
     * @return float
     */
    public function getTailleMoteur(): float
    {
        return $this->tailleMoteur;
    }

    /**
     * @return string
     */
    public function getImageV(): string
    {
        return $this->imageV;
    }

    /**
     * @return string
     */
    public function getMarquevehicule(): string
    {
        return $this->marquevehicule;
    }

    /**
     * @return string
     */
    public function getModelvehicule(): string
    {
        return $this->modelvehicule;
    }

    /**
     * @return int
     */
    public function getAnneevehicule(): int
    {
        return $this->anneevehicule;
    }

    /**
     * @return float|null
     */
    public function getPrixfinal(): ?float
    {
        return $this->prixfinal;
    }

    /**
     * @return string
     */
    public function getTypetransmission(): string
    {
        return $this->typetransmission;
    }

    /**
     * @return int
     */
    public function getMileagevehicule(): int
    {
        return $this->mileagevehicule;
    }

    /**
     * @return string
     */
    public function getTypeCarburant(): string
    {
        return $this->typecarburant;
    }

    /**
     * @return int
     */
    public function getIdgarage(): int
    {
        return $this->idgarage;
    }
    public static function getArchivesByIdGarage($id)
    {
        $data = [
            'id' => $id
        ];
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
            SELECT *
            FROM offrevoiturearchivage
            where idgarage = :id;
SQL
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $stmt->execute($data);

        return $stmt->fetchAll();
    }
}