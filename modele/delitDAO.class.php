<?php
    include_once "../modele/connexion.php";
    include_once "../modele/delit.class.php";

    class DelitDAO
    {
        private $bd;
        private $select;

        function __construct()
        {
            $this->bd = new Connexion();
            $this->select = "SELECT id_delit, nature, tarif FROM DELIT";
        }

        function insert(Delit $delit): void 
        {
            $this->bd->execSQL("INSERT INTO DELIT (id_delit, nature, tarif) VALUES (:id_delit, :nature, :tarif)"
							, [":id_delit" => $delit->getIdDelit(), ":nature" => $delit->getNature(), ":tarif" => $delit->getTarif()]);
		}

		function delete(string $idDelit): void	
        {
            $this->bd->execSQL("DELETE FROM DELIT WHERE id_delit = :id_delit", [":id_delit" => $idDelit]);
		}

		function update(Delit $delit): void
		{
			$this->bd->execSQL("UPDATE DELIT SET nature = :nature, tarif = :tarif WHERE id_delit = :id_delit"
							, [":nature" => $delit->getNature(), ":tarif" => $delit->getTarif(), ":id_delit" => $delit->getIdDelit()]);									
		}

        private function loadQuery(array $result): array	
        {
			$delits = [];

			foreach($result as $row)
			{
				$delit = new Delit();
				$delit->setIdDelit($row["id_delit"]);
				$delit->setNature($row["nature"]);
				$delit->setTarif($row["tarif"]);
				$delits[] = $delit; 
			}
			return $delits;
		}

        function getAll(): array
        {
            return $this->loadQuery($this->bd->execSQL($this->select));
        }

        function getByIdDelit(string $idDelit): Delit
        {
            $delit = new Delit();
            $delits = $this->loadQuery($this->bd->execSQL($this->select." WHERE id_delit = :id_delit", [":id_delit" => $idDelit]));

            if (count($delits) > 0)
            {
                $delit = $delits[0];
            }
            return $delit;
        }

        function existe(string $idDelit): bool
        {
            $result = $this->loadQuery($this->bd->execSQL("SELECT * FROM DELIT WHERE id_delit = :id_delit", [":id_delit" => $idDelit]));

            return ($result != []);
        }
    }
?>