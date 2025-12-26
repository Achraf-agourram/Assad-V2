<?php
require_once '../Database.php';

class Habitat
{
    private $id;
    private $nom;
    private $typeClimat;
    private $description;
    private $zonezoo;

    public function __construct(int $id, string $nom, string $typeClimat, string $description, string $zonezoo)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->typeClimat = $typeClimat;
        $this->description = $description;
        $this->zonezoo = $zonezoo;
    }

    public function addHabitat(): void
    {
        Database::request("INSERT INTO `habitats` (h_name, typeclimat, description, zonezoo) VALUES (?, ?, ?, ?);", [$this->nom, $this->typeClimat, $this->description, $this->zonezoo]);
    }
    public function editHabitat($nom, $typeClimat, $description, $zonezoo): void
    {
        Database::request("UPDATE `habitats` SET h_name = ?, typeclimat = ?, description = ?, zonezoo = ? WHERE id = ?;", [$nom, $typeClimat, $description, $zonezoo, $this->id]);
        $this->nom;
        $this->typeClimat;
        $this->description;
        $this->zonezoo;
    }
    public function deleteHabitat(): void
    {
        Database::request("DELETE FROM `habitats` WHERE id = ?;", [$this->id]);
    }
    
    // getter
    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->nom;
    }
    public function getClimatType(): string
    {
        return $this->typeClimat;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getZoneZoo(): string
    {
        return $this->zonezoo;
    }

    //setter
    public function setName(string $name): void
    {
        $this->nom = $name;
    }
    public function setClimatType(string $typeClimat): void
    {
        $this->typeClimat = $typeClimat;
    }
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
    public function setZoneZoo(string $zonezoo): void
    {
        $this->zonezoo = $zonezoo;
    }
}
?>