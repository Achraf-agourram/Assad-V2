<?php
require_once '../Database.php';

class Comment
{
    private $id;
    private $note;
    private $texte;
    private $datecommentaire;
    private $id_visite;
    private $id_utilisateur;

    public function __construct(int $id, int $note, string $texte, string $datecommentaire, int $id_visite, int $id_utilisateur)
    {
        $this->id = $id;
        $this->note = $note;
        $this->texte = $texte;
        $this->datecommentaire = $datecommentaire;
        $this->id_visite = $id_visite;
        $this->id_utilisateur = $id_utilisateur;
    }

    public function addComment(): void
    {
        Database::request("INSERT INTO commentaires (note, texte, id_visite, id_utilisateur) VALUES (?, ?, ?, ?);", [$this->note, $this->texte, $this->id_visite, $this->id_utilisateur]);
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getNote(): int
    {
        return $this->note;
    }

    public function getTexte(): string
    {
        return $this->texte;
    }

    public function getDateCommentaire(): string
    {
        return $this->datecommentaire;
    }

    public function getIdVisite(): int
    {
        return $this->id_visite;
    }

    public function getIdUtilisateur(): int
    {
        return $this->id_utilisateur;
    }

    public function setNote(int $note): void
    {
        $this->note = $note;
    }

    public function setTexte(string $texte): void
    {
        $this->texte = $texte;
    }

    public function setDateCommentaire(string $datecommentaire): void
    {
        $this->datecommentaire = $datecommentaire;
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