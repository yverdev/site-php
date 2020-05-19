<?php

require_once 'inc/init.php';
$message = ''; // pour le message de déconnexion
// 2- Déconnexion du membre
// debug($_GET); // on a mis dans le header.php un lien en get "connexion.php?acton="deconnexion"
if(isset($_GET['action']) && $_GET['action'] == 'deconnexion'){ // si on a reçu une demande de deconnexion du membre
    unset($_SESSION['membre']); // on vide la session de sa partie membre tout en conservant l'éventuel panier
    $message = '<div class="alert alert-info">Vous êtes déconnecté.</div>';
}
// 3- On vérifie que le membre n'est pas connecté s'il l'est on le redirige vers son profil:
    if(estConnecte()){
        header('location:profil.php'); // on autorise pas le membre a accéder au formulaire de connexion alors qu'il est deja connecté
        exit;
    }



//1- Traitement formulaire de connexion
//debug($_POST);
if(!empty($_POST)){ // si le formulaire est envoyé
    // Controle du formulaire:
        if(empty($_POST['pseudo']) || empty($_POST['mdp'])){ // pseudo ou mdp est vide
            $contenu .= '<div class="alert alert-danger">Les identifiants sont obligatoires.</div>';
        }
// si les champs sont remplis, on vérifie le pseudo et le mdp en BDD
    if(empty($contenu)){ // si la variable est vide s'est qu'il n'y a pas d'erreur
        $resultat = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo", array(':pseudo' => $_POST['pseudo']));
        
        if($resultat->rowCount() == 1){ // cela signifie que le pseudo existe sinon nous aurions 0
            $membre = $resultat->fetch(PDO::FETCH_ASSOC); // pas de bocule car il n'y a qu'un seul pseudo en BDD
            debug($membre); // tableau associatif
            if(password_verify($_POST['mdp'], $membre['mdp'])){ // si le hash de la BDD correspond au mdp du formulaire, password_verify retourne true
                // Connexion de l'intrnaute:
                $_SESSION['membre'] = $membre; // On a crée une session avec les infos du membre provenant de la BDD
                header('location:profil.php'); // permet de faire une redirection vers la page profil
                exit; // permet de sortir immédiatement du script
            }else{ // sinon les mdp ne correspondent pas
                $contenu .= '<div class="alert alert-danger">Erreurs sur vos identifiants.</div>';
            }
        }else{ // Le pseudo n'est pas en BDD
            $contenu .= '<div class="alert alert-danger">Erreurs sur vos identifiants.</div>';
        }



    } // fin du if(empty($contenu))
} // fin if(!empty($_POST))



require_once 'inc/header.php';
?>
<h1 class="mt-4">Connexion</h1>
<?php
echo $message; // pour le message de déconnexion
echo $contenu; // pour les autres messages
?>
<form method="post">
<div>
    <div><label for="pseudo">Pseudo</label></div>
    <div><input type="text" name="pseudo" id="pseudo"></div>
</div>
<div>
    <div><label for="mdp">Mot de Passe</label></div>
    <div><input type="password" name="mdp" id="mdp"></div>
</div>
<div><input type="submit" value="Se connecter" class="btn btn-outline-warning"></div>



</form>


<?php
require_once 'inc/footer.php';
