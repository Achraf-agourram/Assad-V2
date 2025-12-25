<?php

class Guide extends User
{
    private $approuveGuide;
    private $statutCompte;

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