<?php
require_once '../Database.php';
require_once 'user.php';

class Guide extends User
{
    private $approuveGuide;
    private $statutCompte;

    public function __construct(int $id, string $nom, string $email, string $role, string $motpasse, string $pays, int $approuveGuide, int $statutCompte)
    {
        parent::__construct($id, $nom, $email, $role, $motpasse, $pays);
        $this->approuveGuide = $approuveGuide;
        $this->statutCompte  = $statutCompte;
    }

    public function approuveGuide(): void
    {
        Database::request("UPDATE `utilisateurs` SET role_approuve = 1 WHERE email = ?;", [$this->email]);
        $this->approuveGuide = 1;
    }
    public function disableGuide() : void
    {
        Database::request("UPDATE `utilisateurs` SET statut_compte = 0 WHERE email = ?;", [$this->email]);
        $this->statutCompte = 0;
    }
    public function enableGuide() : void
    {
        Database::request("UPDATE `utilisateurs` SET statut_compte = 1 WHERE email = ?;", [$this->email]);
        $this->statutCompte = 1;
    }

    public function getApprouve(): int
    {
        return $this->approuveGuide;
    }
    public function getStatut(): int
    {
        return $this->statutCompte;
    }

    public function setApprouve(int $approuve): void
    {
        $this->approuveGuide = $approuve;
    }
    public function setStatut(int $statut): void
    {
        $this->statutCompte = $statut;
    }
}
?>