<?php
    include_once "../modele/connexion.php";
    include_once "../modele/infraction.class.php";

    class InfractionDAO
    {
        private $bd;
        private $select;

        function __construct()
        {
            $this->bd = new Connexion();
            $this->select = "SELECT id_inf, date_inf, num_immat, num_permis FROM INFRACTION";
        }

        function insert(Infraction $infraction): void 
        {
            $this->bd->execSQL("INSERT INTO INFRACTION (id_inf, date_inf, num_immat, num_permis) VALUES (:id_inf, :date_inf, :num_immat, :num_permis)"
							, [":id_inf" => $infraction->getIdInf(), ":date_inf" => $infraction->getDateInf(), ":num_immat" => $infraction->getNumImmat()
                             , ":num_permis" => $infraction->getNumPermis()]);
		}

		function delete(string $idInf): void	
        {
            $this->bd->execSQL("DELETE FROM INFRACTION WHERE id_inf = :id_inf", [":id_inf" => $idInf]);
		}

		function update(Infraction $infraction): void
		{
			$this->bd->execSQL("UPDATE INFRACTION SET date_inf = :date_inf, num_immat = :num_immat, num_permis = :num_permis WHERE id_inf = :id_inf"
							, [":date_inf" => $infraction->getDateInf(), ":num_immat" => $infraction->getNumImmat() ,":num_permis" => $infraction->getNumPermis()
                             , ":id_inf" => $infraction->getIdInf()]);									
		}

        private function loadQuery(array $result): array	
        {
			$infractions = [];

			foreach($result as $row)
			{
				$infraction = new Infraction();
				$infraction->setIdInf($row["id_inf"]);
				$infraction->setDateInf($row["date_inf"]);
				$infraction->setNumImmat($row["num_immat"]);
                $infraction->setNumPermis($row["num_permis"]);
				$infractions[] = $infraction; 
			}
			return $infractions;
		}

        function getAll(): array
        {
            return $this->loadQuery($this->bd->execSQL($this->select." ORDER BY id_inf"));
        }

        function getNewIdInf(): string
        {
            $result = $this->bd->execSQL("SELECT MAX(id_inf) AS id_inf FROM INFRACTION");

            return $result[0]["id_inf"] + 1;
        }

        function getByIdInf(string $idInf): Infraction
        {
            $infraction = new Infraction();
            $infractions = $this->loadQuery($this->bd->execSQL($this->select." WHERE id_inf = :id_inf ORDER BY id_inf", [":id_inf" => $idInf]));

            if (count($infractions) > 0)
            {
                $infraction = $infractions[0];
            }
            return $infraction;
        }

        function getByNumPermis(string $numPermis): array
        {
            return $this->loadQuery($this->bd->execSQL($this->select." WHERE num_permis = :num_permis", [":num_permis" => $numPermis]));
        }

        function existe(string $idInd): bool
        {
            $result = $this->loadQuery($this->bd->execSQL("SELECT * FROM INFRACTION WHERE id_inf = :id_inf", [":id_inf" => $idInf]));

            return ($result != []);
        }
    }
?>