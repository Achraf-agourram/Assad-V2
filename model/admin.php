<?php
require_once '../Database.php';
require_once 'user.php';

class Admin extends User
{

    public function __construct(int $id, string $nom, string $email, string $role, string $motpasse, string $pays, string $created_at)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->email = $email;
        $this->role = $role;
        $this->motpasse = $motpasse;
        $this->pays = $pays;
        $this->created_at = $created_at;
    }

    
}
?>