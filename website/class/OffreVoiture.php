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

    public static function addOffre(string $immatriculation, float $prixVente, string $marqueVehicule, string $modeleVehicule, string $anneeVehicule){
        $data = [
            ':libele'=>$libele
        ];
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
INSERT INTO Produit (nom)VALUES (:libele);
SQL
        );
        $stmt->execute($data);

    }
}