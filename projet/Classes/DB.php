<?php

namespace App\Classes;

use PDO;
use PDOException;

class DB
{
    private string $host = "localhost";
    private string $db = "amrq8237_disgit";
    private string $user = "amrq8237";
    private string $password = "88*NPu!!X2qu";
    private static ?PDO $dbInstance = null;

    public function __construct() {
        try{
            self::$dbInstance = new PDO("mysql:host=$this->host;dbname=$this->db;charset=utf8", $this->user, $this->password);
            //To catch error
            self::$dbInstance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            //to avoid getting 2 times the same result
            self::$dbInstance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch(PDOException $exception){
            echo $exception->getMessage();
        }

    }
    /**
     * Return PDO object connected to database
     * @return PDO|null
     */
    public static function getInstance(): ?PDO {
        if(is_null(self::$dbInstance)){
            new self();
        }
        return self::$dbInstance;
    }


    /**
     * On empeche les gens de cloner l'objet
     * pour sassurer quon a bien quune seul instance de la connexion a la db
     */
    public function __clone() {}
}