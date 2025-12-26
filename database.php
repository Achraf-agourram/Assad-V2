<?php

class Database
{
    private static ?PDO $existence = null;

    public static function connect(): PDO
    {
        if(self::$existence === null)
        {
            self::$existence = new PDO(
                "mysql:host=localhost;dbname=zoo_assad;charset=utf8",
                "root",
                "",
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
                ]
            );
        }
        return self::$existence;
    }

    public static function request(string $command, array $values = []): array
    {
        $db = self::connect();
        $pre = $db->prepare($command);
        $pre->execute($values);

        return $pre->fetchAll();
    }
}

?>