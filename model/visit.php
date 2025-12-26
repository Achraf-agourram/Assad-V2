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
    public function editVisit(int $id, string $titre, string $description, string $dateheure, string $langue, int $capacite_max, int $duree, float $prix, int $id_guide) : void
    {
        Database::request("UPDATE `visitesguidees` SET titre=?, description=?, dateheure=?, langue=?, capacite_max=?, duree=?, prix=?, id_guide=? WHERE id = ?", [$titre, $description, $dateheure, $langue, $capacite_max, $duree, $prix, $id_guide, $this->id]);
    }
    public function cancelVisit(): void
    {
        Database::request("UPDATE `visitesguidees` SET statut=? WHERE id = ?", ['annulee', $this->id]);
        $this->statut = 'annulee';
    }
    public function getVisits(): array
    {
        return Database::request("SELECT * FROM `visitesguidees`");
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getDateHeure(): string
    {
        return $this->dateheure;
    }

    public function getLangue(): string
    {
        return $this->langue;
    }

    public function getCapaciteMax(): int
    {
        return $this->capacite_max;
    }

    public function getDuree(): int
    {
        return $this->duree;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function getStatut(): string
    {
        return $this->statut;
    }

    public function getIdGuide(): int
    {
        return $this->id_guide;
    }

    public function setTitre(string $titre): void
    {
        $this->titre = $titre;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setDateHeure(string $dateheure): void
    {
        $this->dateheure = $dateheure;
    }

    public function setLangue(string $langue): void
    {
        $this->langue = $langue;
    }

    public function setCapaciteMax(int $capacite_max): void
    {
        $this->capacite_max = $capacite_max;
    }

    public function setDuree(int $duree): void
    {
        $this->duree = $duree;
    }

    public function setPrix(float $prix): void
    {
        $this->prix = $prix;
    }

    public function setStatut(string $statut): void
    {
        $this->statut = $statut;
    }

    public function setIdGuide(int $id_guide): void
    {
        $this->id_guide = $id_guide;
    }
}

?>