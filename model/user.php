<?php
require_once '../Database.php';

class User
{
    protected $id;
    protected $nom;
    protected $email;
    protected $role;
    protected $motpasse;
    protected $pays;

    public function __construct(int $id, string $nom, string $email, string $role, string $motpasse, string $pays)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->role = $role;
        $this->motpasse = $motpasse;
        $this->pays = $pays;
    }

    public function login(): bool
    {
        $user = Database::request("SELECT * FROM utilisateurs WHERE email = ?", [$this->email]);
        if($user)
        {
            $_SESSION['loggedAccount'] = $user[0]['id'];
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        $_SESSION['loggedAccount'] = null;
    }

    public function register(): void
    {
        Database::request("INSERT INTO `utilisateurs`(`nom`, `email`, `role`, `motpasse_hash`, `pays`, `statut_compte`) VALUES (?, ?, ?, ?, ?, ?);", [$this->nom, $this->email, $this->role, $this->motpasse, $this->pays, $this->statutCompte]);
    }
    
    public function searchUser(): array
    {
        return Database::request("SELECT * FROM utilisateurs WHERE nom = ?", [$this->nom]);
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setName(string $nom): void
    {
        $this->nom = $nom;
    }
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
    public function setRole(string $role): void
    {
        $this->role = $role;
    }
    public function setPassword(string $motpasse): void
    {
        $this->motpasse = $motpasse;
    }
    public function setCountry(string $pays): void
    {
        $this->pays = $pays;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->nom;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getPassword(): string
    {
        return $this->motpasse;
    }
    public function getCountry(): string
    {
        return $this->pays;
    }
}



?>