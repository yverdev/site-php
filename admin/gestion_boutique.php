<?php
require_once '../inc/init.php';
// 1- Vérifier que le membre est bien administrateur:
    if(!estAdmin()){
        header('location:../connexion.php');
        exit;
    }
// 7- Suppression produit:
// debug($_GET);
if(isset($_GET['id_produit'])){ // si existe id_produit dans l'url c'est qu'on a demandé la suppression
    $resultat = executeRequete("DELETE FROM produit WHERE id_produit = :id_produit", array(':id_produit' => $_GET['id_produit']));

// debug($resultat->rowCount()); //on obtient 1 lors de la suppression d'une ligne
if($resultat->rowCount() == 1){ // si le delete retourne une ligne c'est que la variable requete a bien marché
    $contenu .= '<div class="alert alert-success">Le produit a bien été supprimé</div>';
}else{ // si le delete retourne 0 ligne c'est que le produit n'est pas en BDD
    $contenu .= '<div class="alert alert-danger">Le produit n\'a pas pu être supprimé...</div>';
}
}



// 6- Affichage des produits dans le BO
$resultat = executeRequete("SELECT * FROM produit"); // objet PDOStatement
$contenu .= '<p class="mt-3">Nombre de produits dans la boutique : ' . $resultat->rowCount() . '</p>';
$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table">';
       
        $contenu .= '<tr>';
            $contenu .= '<th>id</th>';
            $contenu .= '<th>référence</th>';
            $contenu .= '<th>catégorie</th>';
            $contenu .= '<th>titre</th>';
            $contenu .= '<th>description</th>';
            $contenu .= '<th>couleur</th>';
            $contenu .= '<th>taille</th>';
            $contenu .= '<th>public</th>';
            $contenu .= '<th>photo</th>';
            $contenu .= '<th>prix</th>';
            $contenu .= '<th>stock</th>';
            $contenu .= '<th>action</th>';
        $contenu .= '</tr>';
    // Les ligne de produit:
    // debug($resultat);
    while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)){
        //debug($produit); // $produit est un tableau associatif avec les champs de la requete en indices. Il contient un seul produit à chaque tour de boucle
        $contenu .= '<tr>';
            foreach($produit as $indice => $information){ // cette boucle parcour un produit pour en récupérer les informations
                if($indice == 'photo'){ // quand je suis sur l'indice photo ajoute une balise img
                    $contenu .= '<td><img src="../'.$information.'" style="width:90px"></img></td>'; // ce script se trouvant dans le sous-dossier admin, il faut remonter dans le dossier parent avec ../ pour pouvoir ensuite redescendre dans le dossier photos qui contient les images.
                }else{ // sinon je ne suis pas sur le champ photo et je suis sur les autres champs
                    $contenu .= '<td>' . $information .'</td>';
                }
            }
        // On ajoute les liens "action":
        $contenu .= '<td>';
            $contenu .= '<div><a href="formulaire_produit.php?id_produit='.$produit['id_produit'].'">modifier</a></div>';
            $contenu .= '<div><a href="?id_produit='.$produit['id_produit'].'" onclick="return(confirm(\'Etes-vous certain de supprimer ce produit ?\'))">supprimer</a></div>';
            // confirm() retourne true quand on valide et false quand on annule - return false bloque le lien et donc ne declenche pas d'action de suppression
            $contenu .= '</td>';
        $contenu .= '</tr>';
    }

    $contenu .= '</table>';
$contenu .= '</div>';


require_once '../inc/header.php';
// 2- Onglets de navigation
?>
<h1 class="mt-4 mb-4">Gestion Boutique</h1>

<ul class="nav nav-tabs">
<li><a href="gestion_boutique.php" class="nav-link active">Affichage des produits</a></li>
<li><a href="formulaire_produit.php" class="nav-link">Formulaire produit</a></li>
</ul>



<?php
echo $contenu;

require_once '../inc/footer.php';