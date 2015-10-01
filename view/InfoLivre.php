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
        
        
    <?php 

//            echo 'Titre:' . $livre->getTitre() . '<br/>';
//            echo 'Auteur:' . $livre->getAuteur() . '<br/>';
//            echo 'Description:' . $livre->getDescription() . '<br/>';
            $image = str_replace(" ","",$livre->getTitre());
									$image = str_replace("'","",$image);
                                     $image = strtolower($image);
             echo ' <div class="col-sm-6 col-md-4"> <div class="thumbnail" id="ThumbInfo"> <img src="./images/'.$image.'.jpg'.'" alt="photo de'.$image.'" class="img-responsive"><div class="caption"><h3>'.$livre->getTitre().'</h3><p>'.$livre->getAuteur().'</p> <p>'.$livre->getDescription().'</p></div></div></div>';

    ?>
        <br /><br />
        
</body>
<script src="js/bootstrap.min.js"></script>
</html>
