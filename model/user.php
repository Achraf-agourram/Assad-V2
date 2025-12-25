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

    public function login(string $email): bool
    {
        $user = Database::request("SELECT * FROM utilisateurs WHERE email = ?", [$email]);
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
    
    public function searchUser(string $name): array
    {
        return Database::request("SELECT * FROM utilisateurs WHERE nom = ?", [$name]);
    }

    // setter
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
        $this->id = $pays;
    }
    //getter

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