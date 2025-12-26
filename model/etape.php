<?php
require_once '../Database.php';

class Etape
{
    private $id;
    private $titreetape;
    private $descriptionetape;
    private $orderetape;
    private $id_visite;

    public function __construct(int $id, string $titreetape, string $descriptionetape, int $orderetape, int $id_visite)
    {
        $this->id = $id;
        $this->titreetape = $titreetape;
        $this->descriptionetape = $descriptionetape;
        $this->orderetape = $orderetape;
        $this->id_visite = $id_visite;
    }

    public function addStep(): void
    {
        Database::request("INSERT INTO `etapesvisite`(titreetape, descriptionetape, ordreetape, id_visite) VALUES (?, ?, ?, ?);", [$this->titreetape, $this->descriptionetape, $this->orderetape, $this->id_visite]);
    }
    public function deleteStep(): void
    {
        Database::request("DELETE FROM `etapesvisite` WHERE id = ?", [$this->id]);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitreEtape(): string
    {
        return $this->titreetape;
    }

    public function getDescriptionEtape(): string
    {
        return $this->descriptionetape;
    }

    public function getOrderEtape(): int
    {
        return $this->orderetape;
    }

    public function getIdVisite(): int
    {
        return $this->id_visite;
    }


    public function setTitreEtape(string $titreetape): void
    {
        $this->titreetape = $titreetape;
    }

    public function setDescriptionEtape(string $descriptionetape): void
    {
        $this->descriptionetape = $descriptionetape;
    }

    public function setOrderEtape(int $orderetape): void
    {
        $this->orderetape = $orderetape;
    }

    public function setIdVisite(int $id_visite): void
    {
        $this->id_visite = $id_visite;
    }
}

?>