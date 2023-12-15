<?php
    class Conducteur
    {
        private $num_permis;
        private $date_permis;
        private $nom;
        private $prenom;
        private $mot_de_passe;

        function __construct(string $num_permis = "", string $date_permis = "", string $nom = "", string $prenom = "", string $mot_de_passe = "") 
        {
			$this->num_permis = $num_permis;
            $this->date_permis = $date_permis;
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->mot_de_passe = $mot_de_passe;
		}

        function getNumPermis(): string
        {
            return $this->num_permis;
        }

        function setNumPermis(string $value): void
        {
            $this->num_permis = $value;
        }

        function getDatePermis(): string
        {
            return $this->date_permis;
        }

        function setDatePermis(string $value): void
        {
            $this->date_permis = $value;
        }

        function getNom(): string
        {
            return $this->nom;
        }

        function setNom(string $value): void
        {
            $this->nom = $value;
        }

        function getPrenom(): string
        {
            return $this->prenom;
        }

        function setPrenom(string $value): void
        {
            $this->prenom = $value;
        }

        function getMdp(): string
        {
            return $this->mot_de_passe;
        }

        function setMdp(string $value): void
        {
            $this->mot_de_passe = $value;
        }
    }
?>