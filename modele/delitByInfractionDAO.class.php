<?php
    include_once "../modele/connexion.php";
    include_once "../modele/delitByInfraction.class.php";
    include_once "../modele/delitDAO.class.php";

    class DelitByInfractionDAO
    {
        private $bd;
        private $select;

        function __construct()
        {
            $this->bd = new Connexion();
            $this->select = "SELECT id_inf, id_delit FROM COMPREND";
        }

        function insert(DelitByInfraction $delitByInfraction): void 
        {
            $this->bd->execSQL("INSERT INTO COMPREND (id_inf, id_delit) VALUES (:id_inf, :id_delit)"
							, [":id_inf" => $delitByInfraction->getIdInf(), ":id_delit" => $delitByInfraction->getDelit()->getIdDelit()]);
		}

		function delete(string $idInf, string $idDelit): void	
        {
            $this->bd->execSQL("DELETE FROM COMPREND WHERE id_inf = :id_inf AND id_delit = :id_delit", [":id_inf" => $idInf, ":id_delit" => $idDelit]);
		}

        function deleteByIdInf(string $idInf): void	
        {
            $this->bd->execSQL("DELETE FROM COMPREND WHERE id_inf = :id_inf", [":id_inf" => $idInf]);
		}

        private function loadQuery(array $result): array	
        {
			$delitsByInfraction = [];
            $delitDAO = new DelitDAO();

			foreach($result as $row)
			{
				$delit = $delitDAO->getByIdDelit($row["id_delit"]);

                $delitsByInfraction[] = new DelitByInfraction($row["id_inf"], $delit);
			}
			return $delitsByInfraction;
		}

        function getAll(): array
        {
            return $this->loadQuery($this->bd->execSQL($this->select));
        }

        function getByIdInf(string $idInf): array
        {
            
            return $this->loadQuery($this->bd->execSQL($this->select." WHERE id_inf = :id_inf", [":id_inf" => $idInf]));
        }

        function getByIdInfByIdDelit(string $idInf, string $idDelit): DelitByInfraction
        {
            $delitByInfraction = new DelitByInfraction();
            $delitsByInfraction = $this->loadQuery($this->bd->execSQL($this->select." WHERE id_inf = :id_inf AND id_delit = :id_delit", [":id_inf" => $idInf, ":id_delit" => $idDelit]));

            if (count($delitsByInfraction) > 0)
            {
                $delitByInfraction = $delitsByInfraction[0];
            }
            return $delitByInfraction;
        }

        function getMontantTotal(array $delitsByInfraction): string
        {
            $total = 0;

            foreach($delitsByInfraction as $delitByInfraction)
            {   
                $total += $delitByInfraction->getDelit()->getTarif();
            }
            return $total;
        }

        function existe(string $idInf, string $idDelit): bool
        {
            $result = $this->loadQuery($this->bd->execSQL("SELECT * FROM COMPREND WHERE id_inf = :id_inf AND id_delit = :id_delit", [":id_inf" => $idInf, ":id_delit" => $idDelit]));

            return ($result != []);
        }
    }
?>