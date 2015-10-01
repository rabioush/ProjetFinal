<?php header('Content-type: text/html; charset=UTF-8'); ?>
<html>
    <head>
        <link rel="stylesheet" href="css/bootstrap.min.css">
         <script src="https://code.jquery.com/jquery.js"></script>
<!-- Optional theme -->

<link rel="stylesheet" href="css/style.css">
    </head>

<body>
    
      <nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="./index.php">NRA Corp.</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
          
          
      </ul>
        
      <form class="navbar-form navbar-left" role="search" method="get" action="./index.php">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Rechercher un livre..." name="search">
        </div>
          
        <input type="hidden" name="action" value="rechercher"/>
        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search" ></span></button>
        
      </form>
      <ul class="nav navbar-nav navbar-right">
       
          <li><a href="index.php?action=ajouterLivre"><span class="glyphicon glyphicon-plus"></span> Ajouter un Livre</a></li>
        
      </ul>
        
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
    
    <div id="divDroite">
    
    <h3>Ajouter un livre</h3>
    
    <form method="get" action="./index.php">
  <div class="form-group">
    <label for="titre">Titre : </label>
    <input type="titre" class="form-control" name="titre" placeholder="titre">
  </div>
  <div class="form-group">
    <label for="auteur">Auteur : </label>
    <input type="auteur" class="form-control" name="auteur" placeholder="auteur">
  </div>
  <div class="form-group">
    <label for="description">Description : </label>
    <input type="description" class="form-control" name="description" placeholder="description">
  </div>
        <input type="hidden" name="action" value="validerAjout"/>
  <button type="submit" class="btn btn-default" href="">Valider</button>
</form>   
    
    </div>
    
</body>
<script src="js/bootstrap.min.js"></script>
</html>