<?php
class Connexion {
    private $db;
    private $host;
    private $user;
    private $mdp;

    private $pdo;

    private function __construct ($db,$host,$user,$mdp) {
        $this->db = $db;
        $this->host = $host;
        $this->user = $user;
        $this->mdp = $mdp;
    
    }

    public static function getPDO ($db,$host,$user,$mdp) {
        $connexion = new Connexion($db,$host,$user,$mdp);

        if ($connexion->pdo != null) {
            return $connexion->pdo;
        }

        try{
            $linkpdo =new PDO('mysql:host='.$connexion->host.'; dbname='.$connexion->db,$connexion->user,$connexion->mdp);
            // Activation des erreurs PDO
             $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
             $linkpdo -> exec('SET NAMES utf8');
             $pdo = $linkpdo;
             return $pdo;
         } catch(PDOException $e) {
            echo($e->getMessage());
            return null;
         }
    }

}
?>