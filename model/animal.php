<?php
require_once '../Database.php';

class Animal
{
    private $id;
    private $nom;
    private $espece;
    private $alimentation;
    private $image;
    private $paysorigine;
    private $descriptioncourte;
    private $nb_consultations;
    private $id_habitat;

    public function __construct(int $id, string $nom, string $espece, string $alimentation, string $image, string $paysorigine, string $descriptioncourte, int $nb_consultations, int $id_habitat)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->espece = $espece;
        $this->alimentation = $alimentation;
        $this->image = $image;
        $this->paysorigine = $paysorigine;
        $this->descriptioncourte = $descriptioncourte;
        $this->nb_consultations = $nb_consultations;
        $this->id_habitat = $id_habitat;
    }

    public function addAnimal(): void
    {
        Database::request("INSERT INTO `animaux` (`nom`, `espece`, `alimentation`, `image`, `paysorigine`, `descriptioncourte`, `nb_consultations`, `id_habitat`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", [$this->nom, $this->espece, $this->alimentation, $this->image, $this->paysorigine, $this->descriptioncourte, $this->nb_consultations, $this->id_habitat]);
    }
    public function editAnimal($nom, $espece, $alimentation, $image, $paysorigine, $descriptioncourte, $nb_consultations, $id_habitat): void
    {
        Database::request("UPDATE `animals` SET nom = ?, espece = ?, alimentation = ?, image = ?, paysorigine = ?, descriptioncourte = ?, nb_consultations = ?, id_habitat = ? WHERE id = ?;", [$nom, $espece, $alimentation, $image, $paysorigine, $descriptioncourte, $nb_consultations, $id_habitat, $this->id]);
        $this->nom = $nom;
        $this->espece = $espece;
        $this->alimentation = $alimentation;
        $this->image = $image;
        $this->paysorigine = $paysorigine;
        $this->descriptioncourte = $descriptioncourte;
        $this->nb_consultations = $nb_consultations;
        $this->id_habitat = $id_habitat;
    }
    public function deleteAnimal(): void
    {
        Database::request("DELETE FROM `animaux` WHERE id = ?;", [$this->id]);
    }
    public function showAnimal(): array
    {
        return Database::request("SELECT * FROM `animaux`;");
    }
    
    // getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getEspece(): string
    {
        return $this->espece;
    }

    public function getAlimentation(): string
    {
        return $this->alimentation;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getPaysOrigine(): string
    {
        return $this->paysorigine;
    }

    public function getDescriptionCourte(): string
    {
        return $this->descriptioncourte;
    }

    public function getNbConsultations(): int
    {
        return $this->nb_consultations;
    }

    public function getIdHabitat(): int
    {
        return $this->id_habitat;
    }

    // setters
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    public function setEspece(string $espece): void
    {
        $this->espece = $espece;
    }

    public function setAlimentation(string $alimentation): void
    {
        $this->alimentation = $alimentation;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    public function setPaysOrigine(string $paysorigine): void
    {
        $this->paysorigine = $paysorigine;
    }

    public function setDescriptionCourte(string $descriptioncourte): void
    {
        $this->descriptioncourte = $descriptioncourte;
    }

    public function setNbConsultations(int $nb_consultations): void
    {
        $this->nb_consultations = $nb_consultations;
    }

    public function setIdHabitat(int $id_habitat): void
    {
        $this->id_habitat = $id_habitat;
    }
}
?>