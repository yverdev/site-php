<?php
require_once 'inc/init.php';
// Traitement des données du formulaire:
    //debug($_POST);
if(!empty($_POST)){ // si le formulaire a été envoyé
    // Validation du formulaire:
        if(!isset($_POST['pseudo']) || strlen($_POST['pseudo']) < 4 || strlen($_POST['pseudo']) > 20){
            // si le champ n'existe pas ou que sa longueur est trop longue ou trop courte
            $contenu .= '<div class="alert alert-danger">Le Pseudo doit contenir entre 4 et 20 caractères.</div>';
        }
        if(!isset($_POST['mdp']) || strlen($_POST['mdp']) < 4 || strlen($_POST['mdp']) > 20){
            $contenu .= '<div class="alert alert-danger">Le Mot de Passe doit contenir entre 4 et 20 caractères.</div>';
        }
        if(!isset($_POST['nom']) || strlen($_POST['nom']) < 2 || strlen($_POST['nom']) > 20){
            $contenu .= '<div class="alert alert-danger">Le Nom doit contenir entre 2 et 20 caractères.</div>';
        }
        if(!isset($_POST['prenom']) || strlen($_POST['prenom']) < 2 || strlen($_POST['prenom']) > 20){
            $contenu .= '<div class="alert alert-danger">Le Prénom doit contenir entre 2 et 20 caractères.</div>';
        }
        if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            // filter_var avec le paramétre FILTER_VALIDATE_EMAIL retourne true si $_POST['email'] est bien de format email
            $contenu .= '<div class="alert alert-danger">L\'Email n\'est pas valide.</div>';
        }
        if(!isset($_POST['civilite']) || ($_POST['civilite'] != 'm' && $_POST['civilite'] != 'f')){
            // si le champ n'existe pas ou que sa longueur est trop longue ou trop courte
            $contenu .= '<div class="alert alert-danger">La Civilité n\'est pas valide.</div>';
        }
        if(!isset($_POST['ville']) || strlen($_POST['ville']) < 1 || strlen($_POST['ville']) > 20){
            $contenu .= '<div class="alert alert-danger">La Ville doit contenir entre 1 et 20 caractères.</div>';
        }
        if(!isset($_POST['code_postal']) || !preg_match('#^[0-9]{5}$#',$_POST['code_postal'])){
            // preg_match retourne true si le code postal correspond format 5 chiffres défini par l'expression régulière
            $contenu .= '<div class="alert alert-danger">Votre Code Postal n\'est pas valide.</div>';
        }
        if(!isset($_POST['adresse']) || strlen($_POST['adresse']) < 4 || strlen($_POST['adresse']) > 50){
            $contenu .= '<div class="alert alert-danger">L\'Adresse contenir entre 4 et 50 cracatères.</div>';
        }

        // S'il n'y a plus d'erreurs dans le formulaire, on verifie que le pseudo est disponible puis on insère le membre en BDD
        if(empty($contenu)){ // si la variable est vide, c'est qu'il n'y a pas de message d'erreur sur le formulaire
            // on verifie que le pseudo est libre en BDD
            $resultat = executeRequete("SELECT * FROM membre WHERE pseudo = :pseudo", array(':pseudo' => $_POST['pseudo']));

            if($resultat->rowCount() > 0){ // si la requête retourne 1 ou plusieurs lignes c'est que le pseudo est déjà en BDD
                $contenu .= '<div class="alert alert-danger">Ce Pseudo est déjà utilisé, veuillez en choisir un autre.</div>';
            }else{ // pseudo disponible, on peut inserer le membre en BDD
                $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT); // Si nous hashons le mdp (ici avec l'agorithme bcrypt par defaut), il faudra sur la page de connexion comparer
                // le hash de la BDD avec celui du mdp fourni par l'internaute lors de la connexion
                //debug($mdp);
                $succes = executeRequete(
                    "INSERT INTO membre(pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse, statut) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse, 0)", // attention au 0 pour membre classic
                    array(
                        ':pseudo' => $_POST['pseudo'],
                        ':mdp' => $mdp,
                        ':nom' => $_POST['nom'],
                        ':prenom' => $_POST['prenom'],
                        ':email' => $_POST['email'],
                        ':civilite' => $_POST['civilite'],
                        ':ville' => $_POST['ville'],
                        ':code_postal' => $_POST['code_postal'],
                        ':adresse' => $_POST['adresse'],
                    ));

                    if($succes){ //soit false soit objet PDOStatement qui équivaut a true
                        $contenu .= '<div class="alert alert-success">Félicitation, vous êtes inscrit. Pour vous déconnecter <ahref="connexion.php">cliquez ici</a></div>';
                        //On affiche ce message si on a reçu un objet de la part de la fonction
                    }else{
                        $contenu .= '<div class="alert alert-danger">Une erreur est survenue lors de l\'enregistrement...</div>';
                    }
            }
        } // fin du if(empty($contenu))

} // fin du if(!empty($_POST))


require_once 'inc/header.php';
?>

<h1 class="mt-4">Inscription</h1>
<?php echo $contenu; ?>

<form method="post">
<div>
    <div><label for="pseudo">Pseudo</label></div>
    <div><input type="text" name="pseudo" id="pseudo" value="<?php echo $_POST['pseudo'] ?? ''; ?>"></div>
</div>
<div>
    <div><label for="mdp">Mot de passe</label></div>
    <div><input type="password" name="mdp" id="mdp" value="<?php echo $_POST['mdp'] ?? ''; ?>"></div>
</div>
<div>
    <div><label for="nom">Nom</label></div>
    <div><input type="text" name="nom" id="nom" value="<?php echo $_POST['nom'] ?? ''; ?>"></div>
</div>
<div>
    <div><label for="prenom">Prénom</label></div>
    <div><input type="text" name="prenom" id="prenom" value="<?php echo $_POST['prenom'] ?? ''; ?>"></div>
</div>
<div>
    <div><label for="email">Email</label></div>
    <div><input type="text" name="email" id="email" value="<?php echo $_POST['email'] ?? ''; ?>"></div>
</div>
<div>
<div><label>Civilité</label></div>
<div><input type="radio" name="civilite" value="m" checked>Homme</div>
<div><input type="radio" name="civilite" value="f" <?php if(isset($_POST['civilite']) && $_POST['civilite'] == 'f') echo 'checked'; ?>>Femme</div>
</div>
<div>
    <div><label for="ville">Ville</label></div>
    <div><input type="text" name="ville" id="ville" value="<?php echo $_POST['ville'] ?? ''; ?>"></div>
</div>
<div>
    <div><label for="code_postal">Code Postal</label></div>
    <div><input type="text" name="code_postal" id="code_postal" value="<?php echo $_POST['code_postal'] ?? ''; ?>"></div>
</div>
<div>
    <div><label for="adresse">Adresse</label></div>
    <div><textarea name="adresse" id="adresse" cols="30" rows="10"><?php echo $_POST['adresse'] ?? ''; ?></textarea></div>
</div>
<div>
    <input type="submit" value="S'inscrire" class="btn btn-outline-secondary">
</div>
</form>







<?php
require_once 'inc/footer.php';