<?php
    class Infraction
    {
        private $id_inf;
        private $date_inf;
        private $num_immat;
        private $num_permis;

        function __construct(string $id_inf = "", string $date_inf = "", string $num_immat = "", string $num_permis = "") 
        {
			$this->id_inf = $id_inf;
            $this->date_inf = $date_inf;
            $this->num_immat = $num_immat;
            $this->num_permis = $num_permis;
		}

        function getIdInf(): string
        {
            return $this->id_inf;
        }

        function setIdInf(string $value): void
        {
            $this->id_inf = $value;
        }

        function getDateInf(): string
        {
            return $this->date_inf;
        }

        function setDateInf(string $value): void
        {
            $this->date_inf = $value;
        }

        function getNumImmat(): string
        {
            return $this->num_immat;
        }

        function setNumImmat(string $value): void
        {
            $this->num_immat = $value;
        }

        function getNumPermis(): string
        {
            return $this->num_permis;
        }

        function setNumPermis(string $value): void
        {
            $this->num_permis = $value;
        }
    }
?>