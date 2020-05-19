<?php
require_once 'inc/init.php';
$commandes = '';


// 1- On redirige le membre NON connecté vers la page de connexion :
if (!estConnecte()) {
	header('location:connexion.php'); // le membre non connecté ne doit pas avoir accès à la page profil. On le redirige donc vers le formulaire de connexion
	exit; // et on quitte le script
}

// 2- exercice : vous affichez le profil du membre connecté avec les informations suivantes : email, adresse, code postal et ville.
// debug($_SESSION);


// Exercice : afficher l'historique des commandes du membre dans une liste <ul><li> (1 <li> par commande). Vous y mettez l'id_commande, la date de la commande et son état. S'il n'y a pas de commande, on affiche "aucune commande en cours".

$resultat = executeRequete("SELECT id_commande, date_enregistrement, etat FROM commande WHERE id_membre = :id_membre", array(':id_membre' => $_SESSION['membre']['id_membre']));


if ($resultat->rowCount() == 0) {
	$commandes = '<p>Aucune commande en cours</p>';
} else {
	$commandes .= '<ul>';
		while ($commande = $resultat->fetch(PDO::FETCH_ASSOC)) {
			// debug($commande);
			$date = new DateTime($commande['date_enregistrement']);
			$date = $date->format('d/m/Y');

			$commandes .= '<li>Commande n°' . $commande['id_commande'] . ' du ' . $date . ' est actuellement ' . $commande['etat'] . '</li>';

		}
	$commandes .= '</ul>';
}


//---------------
// Exercice : vous complétez le href du lien "supprimer mon compte". Lors de la suppression vous demandez la confirmation au membre en JavaScript. Puis vous supprimez le compte en BDD, supprimez la session, et redirigez le membre vers la page d'inscription.php.
debug($_GET);
if(isset($_GET['action']) && $_GET['action'] == 'suppression'){
	executeRequete("DELETE FROM membre WHERE id_membre = :id_membre", array(':id_membre' => $_SESSION['membre']['id_membre']));
	//debug($_SESSION);
	session_destroy();
	header('location:inscription.php');
	exit;
}

require_once 'inc/header.php';
?>
<h1 class="mt-4">Profil</h1>

<h2>Bonjour <?php echo $_SESSION['membre']['prenom'] . ' ' . $_SESSION['membre']['nom']; ?> !</h2>

<?php 
if (estAdmin()) {
	echo '<p>Vous êtes un administrateur.</p>';
}
?>
<hr>
<h3> Vos coordonnées </h3>

<p>Email : <?php echo $_SESSION['membre']['email'];  ?></p>
<p>Adresse : <?php echo $_SESSION['membre']['adresse'];  ?></p>
<p>Code postal : <?php echo $_SESSION['membre']['code_postal'];  ?></p>
<p>Ville : <?php echo $_SESSION['membre']['ville'];  ?></p>




<hr>
<h3> Historique de vos commandes </h3>
<?php
echo $commandes;
?>

<hr>
<h3> Supprimer mon compte </h3>
<p>
	<a href="?action=suppression" onclick="return confirm('Etes-vous certain de vouloir supprimer votre profil ?')">Supprimer mon compte définitivement</a>
</p>


<?php
require_once 'inc/footer.php';