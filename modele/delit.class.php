<?php
    class Delit
    {
        private $id_delit;
        private $nature;
        private $tarif;

        function __construct(string $id_delit = "", string $nature = "", string $tarif = "") 
        {
			$this->id_delit = $id_delit;
            $this->nature = $nature;
            $this->tarif = $tarif;
		}

        function getIdDelit(): string
        {
            return $this->id_delit;
        }

        function setIdDelit(string $value): void
        {
            $this->id_delit = $value;
        }

        function getNature(): string
        {
            return $this->nature;
        }

        function setNature(string $value): void
        {
            $this->nature = $value;
        }

        function getTarif(): string
        {
            return $this->tarif;
        }

        function setTarif(string $value): void
        {
            $this->tarif = $value;
        }
    }
?>