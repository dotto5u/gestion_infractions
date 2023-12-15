<?php
    include_once "../modele/delit.class.php";

    class DelitByInfraction
    {
        private $id_inf;
        private $delit;

        function __construct(string $id_inf = "", Delit $delit = null) 
        {
			$this->id_inf = $id_inf;
            $this->delit = $delit;
		}

        function getIdInf(): string
        {
            return $this->id_inf;
        }

        function setIdInf(string $value): void
        {
            $this->id_inf = $value;
        }

        function getDelit(): Delit
        {
            return $this->delit;
        }

        function setDelit(Delit $value): void
        {
            $this->delit = $value;
        }
    }
?>