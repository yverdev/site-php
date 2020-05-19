<?php
require_once 'inc/init.php';
// 1- Affichage des categories:
$resultat = executeRequete("SELECT DISTINCT categorie FROM produit");
$contenu_gauche .= '<div class="list-group mb-4">';
// lien tous les produits
$contenu_gauche .= '<a href="?categorie=all" class="list-group-item">Tous les produits</a>';
//debug($resultat); // objet PDOStatement il faut donc le fetcher


// Les autres categorie provenant de la BDD
while($categorie = $resultat->fetch(PDO::FETCH_ASSOC)){
    //debug($categorie); // il a un indice ['categorie'] dans le tableau $categorie
    $contenu_gauche .= '<a href="?categorie='.$categorie['categorie'].'" class="list-group-item">'.ucfirst($categorie['categorie']).'</a>'; // ucfirst permet de mettre une majuscule en debut de mot
    // on passe en GET dans url vers le même script que la categorie choisie est egale a la valeur du tableau $categorie['categorie']

}

$contenu_gauche .= '</div>';

// 2- Affichage des produtis de la categorie choisie:
// debug($_GET); on a l'indice "categorie"
if(isset($_GET['categorie']) && $_GET['categorie'] != 'all'){
// si existe "categorie" dans url et qu'elle est differente de "all", c'est qu'on a cliqué sur une categorie particulière existant en BDD. On selectionne donc les produits de cette categorie
    $resultat = executeRequete("SELECT id_produit, reference, titre, photo, prix, description FROM produit WHERE categorie = :categorie", array(':categorie' => $_GET['categorie']));
}else{ // si categorie n'est pas dans l'url c'est qu'on n'a pas cliqué, ou si elle est egale à all, on veut tous les produits: on selectionne donc tous les produits de la BDD
    $resultat = executeRequete("SELECT id_produit, reference, titre, photo, prix, description FROM produit"); // pas de where car on veut tous les produits
}

while($produit = $resultat->fetch(PDO::FETCH_ASSOC)){
    //debug($produit);
    $contenu_droite .= '<div class="col-sm-4 mb-4">';
        $contenu_droite .= '<div class="card">';
            // photo cliquable
            $contenu_droite .= '<a href="fiche_produit?id_produit='.$produit['id_produit'].'"><img src="'.$produit['photo'].'" class="card-img-top" alt="'.$produit['titre'].'"></a>';
            $contenu_droite .= '<div class="card-body">';
                $contenu_droite .= '<h4>'.$produit['titre'].'</h4>';
                $contenu_droite .= '<h5>'.number_format($produit['prix'], 2, ',', '').'€</h5>';
                $contenu_droite .= '<p>'.$produit['description'].'€</p>';
            $contenu_droite .= '</div>';
        $contenu_droite .= '</div>';
    $contenu_droite .= '</div>';
}

require_once 'inc/header.php';
?>
<h1 class="mt-4 mb-4">Boutique</h1>
    <div class="row">
    
        <div class="col-md-3">
            <?php echo $contenu_gauche; // pour afficher les catégories de produits?>
        </div>
    
        <div class="col-md-9">
            <div class="row">
                <?php echo $contenu_droite; //pour afficher les produits?>
            </div>
        </div>
    </div><!-- Fin .row -->




<?php
require_once 'inc/footer.php';