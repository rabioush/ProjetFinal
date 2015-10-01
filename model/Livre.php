<?php


class Livre {
        public $_id;
	public $_titre;
	public $_auteur;
	public $_description;
	
	/**
     * créé l'objet livre / constructeur
     * @param int $id id du livre
     * @param string $titre titre du livre
     * @param string $auteur l'auteur du livre
     * @param string $description description du livre
     
     */
	public function __construct($id,$titre, $auteur, $description)  
    {  
            $this->_id=$id;
        $this->_titre = $titre;
	    $this->_auteur = $auteur;
	    $this->_description = $description;
    } 
        /**
     * récupère l'id du livre
     
     */
		public function getId(){
            
            return $this->_id;
        }
		  /**
     * récupère le titre du livre
     
     */
        public function getTitre(){
            return $this->_titre;
        }
			  /**
     * récupère l'auteur du livre
     
     */
        public function getAuteur(){
            return $this->_auteur;
        }
		  /**
     * récupère la description du livre
     
     */
        public function getDescription(){
            return $this->_description;
        }
}

?>