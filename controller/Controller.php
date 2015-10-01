<?php
include_once("model/Model.php");

class Controller {
	public $model;
	
	public function __construct()  
    {  
        $this->model = new Model();

    }
	public function invoke()
	{
		if (isset($_GET['action']))
		{
                    if($_GET['action']== 'livre'){
                        $livre = $this->model->getLivre($_GET['livre']);
			include 'view/InfoLivre.php';
                    }
                    if($_GET['action']== 'livres'){
                        $livres = $this->model->getListeLivres();
			include 'view/ListeLivres.php';
                    }		
		}
                
                if(isset($_GET['action'])){
           
                if($_GET['action'] == 'rechercher'){   
                       
                        $livre = $this->model->rechercherLivre($_GET['search']);
                        include 'view/InfoLivre.php';
                       
                    }
                    }
                
                if(isset($_GET['action']))
                {
                    if($_GET['action'] == 'ajouterLivre')
                    {
                        include 'view/NouveauLivre.php';
                        //$livre = $this->model->addLivre($_GET['titre'], $_GET['auteur'], $_GET['description']);
                       
                    }
                }
                      
                if(isset($_GET['action']))
                {
                    if($_GET['action'] == 'validerAjout')
                    {
                        $livre = $this->model->addLivre($_GET['titre'], $_GET['auteur'], $_GET['description']);
                        $livres = $this->model->getListeLivres();
                       include 'view/ListeLivres.php';
                    }
                }
                
                if (isset($_GET['action']))
		{
                    if($_GET['action']== 'Supprimer'){
                        
                        $id = $_GET['livre'];
                        $livre = $this->model->deleteLivre($id);
                        $livres = $this->model->getListeLivres();
			include 'view/ListeLivres.php';
                    }
                }
                
		else
		{
			$livres = $this->model->getListeLivres();
			include 'view/ListeLivres.php';
                       
		}
	}
}

?>