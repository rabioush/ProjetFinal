<?php

include_once("model/Livre.php");
include_once("model/ConnectBDD.php");

class Model {
	
	/**
     * récupère la liste des livres depuis la base de données
	 * retourne un tableau de livre de type objet Livre
    
     */
	public function getListeLivres()
	{
            BDMySQL::connecter("localhost","bibliotheque","root","");
	
            $query = "Select * From livre";
            $livres=BDMySQL::extraireNxN($query,false);
            foreach($livres as $livre){
                $arrayLivre[]= new Livre($livre['id'],$livre['titre'], $livre['nomAuteur'], $livre['description']);
                
            }
            return $arrayLivre;
            //var_dump($livres);         
            
	}
	
	/**
     * récupère un livre spécifique depuis un id définit
	 * @param $id, l'id du livre
     
     */
	public function getLivre($id)
	{
		$listeLivres = $this->getListeLivres();
		foreach($listeLivres as $livre){
                    if($id == $livre->getId()){
                            $monLivre = $livre;
                       
                    }
                }
		return $monLivre;
	}
     

	 /**
     * recherche un livre en fonction du titre ou du nom de l'auteur
     * @param string titre ou nom d'auteur du livre
     * retourne un objet livre
     */
    public function rechercherLivre($titre)
    {
        $listeLivres = $this->getListeLivres();
        foreach($listeLivres as $livre){
                    if(strtolower($titre) == strtolower($livre->getTitre()) || strtolower($titre) == strtolower($livre->getAuteur())){
                            $monLivre = $livre;
                      
                    }
                }
        return $monLivre;
    }
        /**
     * ajoute un livre à la base de données
     * @param string $titre le titre du livre
     * @param string $auteur l'auteur du livre
     * @param string $description description du livre
    
     */
        public function addLivre($titre, $auteur, $description)
        {
            
            $titre = $_GET['titre'];
            $auteur = $_GET['auteur'];
            $description = $_GET['description'];
            if($titre != "" && $auteur != "" && $description != "")
            {
                BDMySQL::connecter("localhost","bibliotheque","root","");
                $sqlAjout = "INSERT INTO livre (titre, nomAuteur, description) VALUES('$titre', '$auteur', '$description')";
                BDMySQL::executerAction($sqlAjout);
            }
        }
        /**
     * supprime un livre de la base de données
     * @param $livre objet livre
   
     */
        public function deleteLivre($livre)
        {
                $listeLivres = $this->getListeLivres();
                BDMySQL::connecter("localhost","bibliotheque","root","");
                $sqlSupp = "DELETE FROM livre WHERE id = $livre";
                BDMySQL::executerAction($sqlSupp);
        }
}

?>