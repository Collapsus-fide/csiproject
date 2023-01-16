<?php


class Client extends Compte
{
    /**
     * Prénom
     * @var string $prenom
     */
    protected $prenom = null ;



    /**
     * Accesseur sur le prénom de l'Utilisateur
     *
     * @return string prénom
     */
    public function firstName() : string {
        return $this->firstName ;
    }

    public function isGarage()
    {
        return false;
    }
}

