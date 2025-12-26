<?php
require_once '../Database.php';
require_once 'user.php';

class Admin extends User
{

    public function __construct(int $id, string $nom, string $email, string $role, string $motpasse, string $pays, int $approuveGuide, int $statutCompte)
    {
        parent::__construct($id, $nom, $email, $role, $motpasse, $pays);
    }

    
}
?>