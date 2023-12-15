<?php
    include_once "../modele/connexion.php";
    include_once "../modele/vehicule.class.php";

    class VehiculeDAO
    {
        private $bd;
        private $select;

        function __construct()
        {
            $this->bd = new Connexion();
            $this->select = "SELECT num_immat, date_immat, modele, marque, num_permis FROM VEHICULE";
        }

        function insert(Vehicule $vehicule): void 
        {
            $this->bd->execSQL("INSERT INTO VEHICULE (num_immat, date_immat, modele, marque, num_permis) VALUES (:num_immat, :date_immat, :modele, :marque, :num_permis)"
							, [":num_immat" => $vehicule->getNumImmat(), ":date_immat" => $vehicule->getDateImmat(), ":modele" => $vehicule->getModele()
                             , ":marque" => $vehicule->getMarque(), ":num_permis" => $vehicule->getNumPermis()]);
		}

		function delete(string $numImmat): void	
        {
            $this->bd->execSQL("DELETE FROM VEHICULE WHERE num_immat = :num_immat", [":num_immat" => $numImmat]);
		}

		function update(Vehicule $vehicule): void
		{
			$this->bd->execSQL("UPDATE VEHICULE SET date_immat = :date_immat, modele = :modele, marque = :marque, num_permis = :num_permis WHERE num_immat = :num_immat"
							, [":date_immat" => $vehicule->getDateImmat(), ":modele" => $vehicule->getModele() ,":marque" => $vehicule->getMarque()
                             , ":num_permis" => $vehicule->getNumPermis(), ":num_immat" => $vehicule->getNumImmat()]);									
		}

        private function loadQuery(array $result): array	
        {
			$vehicules = [];

			foreach($result as $row)
			{
				$vehicule = new Vehicule();
				$vehicule->setNumImmat($row["num_immat"]);
				$vehicule->setDateImmat($row["date_immat"]);
				$vehicule->setModele($row["modele"]);
                $vehicule->setMarque($row["marque"]);
                $vehicule->setNumPermis($row["num_permis"]);
				$vehicules[] = $vehicule; 
			}
			return $vehicules;
		}

        function getAll(): array
        {
            return $this->loadQuery($this->bd->execSQL($this->select));
        }

        function getByNumImmat(string $numImmat): Vehicule
        {
            $vehicule = new Vehicule();
            $vehicules = $this->loadQuery($this->bd->execSQL($this->select." WHERE num_immat = :num_immat", [":num_immat" => $numImmat]));

            if (count($vehicules) > 0)
            {
                $vehicule = $vehicules[0];
            }
            return $vehicule;
        }

        function existe(string $numImmat): bool
        {
            $result = $this->loadQuery($this->bd->execSQL("SELECT * FROM VEHICULE WHERE num_immat = :num_immat", [":num_immat" => $numImmat]));

            return ($result != []);
        }
    }
?>