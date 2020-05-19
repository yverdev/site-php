<!doctype html>
<html lang="fr">

<head>
  <title>Ma Boutique</title>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
  </script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
  </script>

</head>

<body>
  <header>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="container">
        <!-- la marque -->
        <a href="<?php echo RACINE_SITE . 'index.php'; ?>" class="navbar-brand">MA BOUTIQUE</a>
        <!-- Menu -->
        <div class="collapse navbar-collapse" id="nav1">
          <ul class="navbar-nav ml-auto">
            <?php
                  echo '<li><a class="nav-link" href="'.RACINE_SITE.'index.php">Boutique</a></li>';
                  
                  if(estConnecte()){ //si le membre est connecté
                    echo '<li><a class="nav-link" href="'.RACINE_SITE.'profil.php">Profil</a></li>';
                    echo '<li><a class="nav-link" href="'.RACINE_SITE.'connexion.php?action=deconnexion">Se déconnecter</a></li>';
                  }else{ // membre n'est pas connecté
                    echo '<li><a class="nav-link" href="'.RACINE_SITE.'inscription.php">Inscription</a></li>';
                    echo '<li><a class="nav-link" href="'.RACINE_SITE.'connexion.php">Connexion</a></li>';
                  }

                  echo '<li><a class="nav-link" href="'.RACINE_SITE.'panier.php">Panier ('.calcItemNumber().')</a></li>';
                  
                  
                  if(estAdmin()){ // si le membre est admin
                    echo '<li><a class="nav-link" href="'.RACINE_SITE.'admin/gestion_boutique.php">Gestion de la boutique</a></li>';
                    echo '<li><a class="nav-link" href="'.RACINE_SITE.'admin/gestion_membres.php">Gestion des membres</a></li>';
                  }

            ?>
          </ul>
        </div> <!-- Fin de menu -->

      </div> <!-- Fin div container -->
    </nav>
  </header>

  <!-- Contenu de la page -->
  <div class="container" style="min-height: 80vh">
    <div class="row">
      <div class="col-12">
        <!-- ici le contenu spécifique de chaque page -->