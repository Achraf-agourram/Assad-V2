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
    private $h_name;

    public function __construct(int $id, string $nom, string $espece, string $alimentation, string $image, string $paysorigine, string $descriptioncourte, int $nb_consultations, string $h_name)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->espece = $espece;
        $this->alimentation = $alimentation;
        $this->image = $image;
        $this->paysorigine = $paysorigine;
        $this->descriptioncourte = $descriptioncourte;
        $this->nb_consultations = $nb_consultations;
        $this->h_name = $h_name;
    }

    public function addAnimal(): void
    {
        Database::request("INSERT INTO `animaux` (`nom`, `espece`, `alimentation`, `image`, `paysorigine`, `descriptioncourte`, `nb_consultations`, `id_habitat`) VALUES (?, ?, ?, ?, ?, ?, ?, ?);", [$this->nom, $this->espece, $this->alimentation, $this->image, $this->paysorigine, $this->descriptioncourte, $this->nb_consultations, $this->id_habitat]);
    }
    public function editAnimal(string $nom, string $espece, string $alimentation, string $image, string $paysorigine, string $descriptioncourte, int $nb_consultations, int $id_habitat): void
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
    public static function getAnimals(): array
    {
        $animals = [];
        $result = Database::request("SELECT animaux.*, habitats.h_name FROM `animaux` JOIN habitats ON animaux.id_habitat = habitats.id;");
        foreach($result as $animal) array_push($animals, new Animal($animal->id, $animal->nom, $animal->espece, $animal->alimentation, $animal->image, $animal->paysorigine, $animal->descriptioncourte, $animal->nb_consultations, $animal->h_name));
        return $animals;
    }
    

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

    public function getHabitat(): string
    {
        return $this->h_name;
    }


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

    public function setIdHabitat(int $h_name): void
    {
        $this->h_name = $h_name;
    }
}

?>