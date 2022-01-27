<?php 

class Conexion extends PDO
{
    private $hostDB = 'localhost';
    private $nombreDB = 'webservice';
    private $usuarioDB = 'root';
    private $passDB = '';

    public function __construct()
    {
        try {
            parent::__construct('mysql:host=' . $this->hostDB . ';dbname=' . $this->nombreDB . ';charset=utf8', $this->usuarioDB, $this->passDB, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            exit;
        }
    }
    
}