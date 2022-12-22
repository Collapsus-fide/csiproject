<?php


class Client extends User
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
}

