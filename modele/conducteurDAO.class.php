<?php
    include_once "../modele/connexion.php";
    include_once "../modele/conducteur.class.php";

    class ConducteurDAO
    {
        private $bd;
        private $select;

        function __construct()
        {
            $this->bd = new Connexion();
            $this->select = "SELECT num_permis, date_permis, nom, prenom, mot_de_passe FROM CONDUCTEUR";
        }

        function insert(Conducteur $conducteur): void 
        {
            $this->bd->execSQL("INSERT INTO CONDUCTEUR (num_permis, date_permis, nom, prenom, mot_de_passe) VALUES (:num_permis, :date_permis, :nom, :prenom, :mdp)"
							, [":num_permis" => $conducteur->getNumPermis(), ":date_permis" => $conducteur->getDatePermis(), ":nom" => $conducteur->getNom()
                             , ":prenom" => $conducteur->getPrenom(), ":mdp" => $conducteur->getMdp()]);
		}

		function delete(string $numPermis): void	
        {
            $this->bd->execSQL("DELETE FROM CONDUCTEUR WHERE num_permis = :num_permis", [":num_permis" => $numPermis]);
		}

		function update(Conducteur $conducteur): void
		{
			$this->bd->execSQL("UPDATE CONDUCTEUR SET date_permis = :date_permis, nom = :nom, prenom = :prenom, mot_de_passe = :mdp WHERE num_permis = :num_permis"
							, [":date_permis" => $conducteur->getDatePermis(), ":nom" => $conducteur->getNom() ,":prenom" => $conducteur->getPrenom()
                             , ":mdp" => $conducteur->getMdp(), ":num_permis" => $conducteur->getNumPermis()]);									
		}

        private function loadQuery(array $result): array	
        {
			$conducteurs = [];

			foreach($result as $row)
			{
				$conducteur = new Conducteur();
				$conducteur->setNumPermis($row["num_permis"]);
				$conducteur->setDatePermis($row["date_permis"]);
				$conducteur->setNom($row["nom"]);
                $conducteur->setPrenom($row["prenom"]);
                $conducteur->setMdp($row["mot_de_passe"]);
				$conducteurs[] = $conducteur; 
			}
			return $conducteurs;
		}

        function getAll(): array
        {
            return $this->loadQuery($this->bd->execSQL($this->select));
        }

        function getByNumPermis(string $numPermis): Conducteur
        {
            $conducteur = new Conducteur();
            $conducteurs = $this->loadQuery($this->bd->execSQL($this->select." WHERE num_permis = :num_permis", [":num_permis" => $numPermis]));

            if (count($conducteurs) > 0)
            {
                $conducteur = $conducteurs[0];
            }
            return $conducteur;
        }

        function existe(string $numPermis): bool
        {
            $result = $this->loadQuery($this->bd->execSQL("SELECT * FROM CONDUCTEUR WHERE num_permis = :num_permis", [":num_permis" => $numPermis]));

            return ($result != []);
        }
    }
?>