<?php
require_once '../Database.php';
require_once 'user.php';

class Visitor extends User
{
    private $statutCompte;

    public function __construct(int $id, string $nom, string $email, string $role, string $motpasse, string $pays, string $created_at, int $statutCompte)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->role = $role;
        $this->motpasse = $motpasse;
        $this->pays = $pays;
        $this->created_at = $created_at;
        $this->statutCompte  = $statutCompte;
    }

    public function disableVisitor() : void
    {
        Database::request("UPDATE `utilisateurs` SET statut_compte = 0 WHERE email = ?;", [$this->email]);
        $this->statutCompte = 0;
    }
    public function enableVisitor() : void
    {
        Database::request("UPDATE `utilisateurs` SET statut_compte = 1 WHERE email = ?;", [$this->email]);
        $this->statutCompte = 1;
    }

    public function getStatut(): int
    {
        return $this->statutCompte;
    }

    public function setStatut(int $statut): void
    {
        $this->statutCompte = $statut;
    }
}

?>