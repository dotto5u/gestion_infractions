<?php
    class Vehicule
    {
        private $num_immat;
        private $date_immat;
        private $modele;
        private $marque;
        private $num_permis;

        function __construct(string $num_immat = "", string $date_immat = "", string $modele = "", string $marque = "", string $num_permis = "") 
        {
			$this->num_immat = $num_immat;
            $this->date_immat = $date_immat;
            $this->modele = $modele;
            $this->marque = $marque;
            $this->num_permis = $num_permis;
		}

        function getNumImmat(): string
        {
            return $this->num_immat;
        }

        function setNumImmat(string $value): void
        {
            $this->num_immat = $value;
        }

        function getDateImmat(): string
        {
            return $this->date_immat;
        }

        function setDateImmat(string $value): void
        {
            $this->date_immat = $value;
        }

        function getModele(): string
        {
            return $this->modele;
        }

        function setModele(string $value): void
        {
            $this->modele = $value;
        }

        function getMarque(): string
        {
            return $this->marque;
        }

        function setMarque(string $value): void
        {
            $this->marque = $value;
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