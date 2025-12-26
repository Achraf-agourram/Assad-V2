<?php
require_once '../Database.php';

class Visit
{
    private $id;
    private $titre;
    private $description;
    private $dateheure;
    private $langue;
    private $capacite_max;
    private $duree;
    private $prix;
    private $statut;
    private $id_guide;

    public function __construct(int $id, string $titre, string $description, string $dateheure, string $langue, int $capacite_max, int $duree, float $prix, string $statut, int $id_guide)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->description = $description;
        $this->dateheure = $dateheure;
        $this->langue = $langue;
        $this->capacite_max = $capacite_max;
        $this->duree = $duree;
        $this->prix = $prix;
        $this->statut = $statut;
        $this->id_guide = $id_guide;
    }

    public function addVisit(int $id_guide): void
    {
        Database::request("INSERT INTO `visitesguidees` (titre, description, dateheure, langue, capacite_max, duree, prix, statut, id_guide) (?, ?, ?, ?, ?, ?, ?, ?, ?);", [$this->titre, $this->description, $this->dateheure, $this->langue, $this->capacite_max, $this->duree, $this->prix, $this->statut, $id_guide]);
    }
    public function deleteVisit(): void
    {
        Database::request("DELETE FROM `visitesguidees` WHERE id = ?;", [$this->id]);
    }
    public function editVisit() : void
    {
        
    }
}

?>