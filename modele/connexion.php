<?php
    class Connexion {
        private $db;
        private $db_admin = ["login" => "admin", "mdp" => "admin123"];

        function __construct() 
        {
            $db_config["SGBD"]     = "mysql";
            $db_config["HOST"]     = "host";
            $db_config["DB_NAME"]  = "dbname";
            $db_config["USER"]     = "user";
            $db_config["PASSWORD"] = "password";
            
            try
            {
                $this->db = new PDO($db_config['SGBD'] .':host='. $db_config['HOST'] .';dbname='. $db_config['DB_NAME'],
                                    $db_config['USER'],	$db_config['PASSWORD'],
                                    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                                    					
                unset($db_config);
            }

            catch(Exception $exception)
            {
                die($exception->getMessage()) ;
            }
        }

        function execSQL(string $req, array $valeurs = []) : array | null 
        {
            try
            {	
                $sql=$this->db->prepare($req); 
                $sql->execute($valeurs);
            }

            catch(Exception $exception)
            {
                die($exception->getMessage()) ;
            }
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        function estAdmin(string $login, string $mdp): bool
        {
            return ($login === $this->db_admin['login'] && $mdp === $this->db_admin['mdp']);
        }
    }	

?>
