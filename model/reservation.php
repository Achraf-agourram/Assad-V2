<?php
require_once '../Database.php';

class Reservation
{
    private $id;
    private $nbpersonnes;
    private $datereservation;
    private $id_visite;
    private $id_utilisateur;

    public function __construct(int $id, int $nbpersonnes, string $datereservation, int $id_visite, int $id_utilisateur) {
        $this->id = $id;
        $this->nbpersonnes = $nbpersonnes;
        $this->datereservation = $datereservation;
        $this->id_visite = $id_visite;
        $this->id_utilisateur = $id_utilisateur;
    }

    public function addReservation(): void
    {
        Database::request("INSERT INTO reservations (nbpersonnes, id_visite, id_utilisateur) VALUES (?, ?, ?);", [$this->nbpersonnes, $this->id_visite, $this->id_utilisateur]);
    }
    public function getReservations(): array
    {
        return Database::request("SELECT * FROM `visitesguidees`");
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getNbPersonnes(): int
    {
        return $this->nbpersonnes;
    }

    public function getDateReservation(): string
    {
        return $this->datereservation;
    }

    public function getIdVisite(): int
    {
        return $this->id_visite;
    }

    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }

    public function setNbPersonnes(int $nbpersonnes): void
    {
        $this->nbpersonnes = $nbpersonnes;
    }

    public function setDateReservation(string $datereservation): void
    {
        $this->datereservation = $datereservation;
    }

    public function setIdVisite(int $id_visite): void
    {
        $this->id_visite = $id_visite;
    }

    public function setIdUtilisateur(int $id_utilisateur): void
    {
        $this->id_utilisateur = $id_utilisateur;
    }
}

?>