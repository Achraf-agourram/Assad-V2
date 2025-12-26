<?php
require_once '../Database.php';
require_once 'visitor.php';
require_once 'guide.php';
require_once 'admin.php';

class User
{
    protected $id;
    protected $nom;
    protected $email;
    protected $role;
    protected $motpasse;
    protected $pays;
    protected $created_at;

    public static function login(string $email, string $password): ?User
    {
        $user = Database::request("SELECT * FROM utilisateurs WHERE email = ? AND motpasse_hash = ?", [$email, $password]);
        
        if($user)
        {
            $user = $user[0];
            $_SESSION['loggedAccount'] = $user->id;
            if($user->role === 'visiteur') return new Visitor($user->id, $user->nom, $user->email, $user->role, $user->motpasse_hash, $user->pays, $user->created_at, $user->statut_compte);
            else if($user->role === 'guide') return new Guide($user->id, $user->nom, $user->email, $user->role, $user->motpasse_hash, $user->pays, $user->created_at, $user->role_approuve, $user->statut_compte);
            else if($user->role === 'admin') return new Admin($user->id, $user->nom, $user->email, $user->role, $user->motpasse_hash, $user->pays, $user->created_at);
        }
        return null;
    }

    public static function findById(int $id): ?User
    {
        $user = Database::request("SELECT * FROM utilisateurs WHERE id = ?", [$id]);
        if($user) return self::login($user[0]->email, $user[0]->motpasse_hash);
        return null;
    }

    public function logout(): void
    {
        session_destroy();
    }

    public static function register(string $nom, string $email, string $role, string $motpasse, string $pays): void
    {
        Database::request("INSERT INTO `utilisateurs`(`nom`, `email`, `role`, `motpasse_hash`, `pays`) VALUES (?, ?, ?, ?, ?);", [$nom, $email, $role, $motpasse, $pays]);
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
    public function setTime(string $created_at): void
    {
        $this->created_at = $created_at;
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
    public function getTime(): string
    {
        return $this->created_at;
    }
}

?>