<?php
require_once 'inc/init.php';
// variables d'affichages:
$panier = ''; // pour le panier
$suggestion= ''; // pour la suggestion de produit
$modale= ''; // la modale de confirmation d'ajout au panier

// 1- si on a demandé un produit
//debug($_GET); // il ya un indice "indice_produit"

if(isset($_GET['id_produit'])){ // si existe l'indice "id_produit" c'est qu'on a demandé led étail d'un produit
    // 2- conrole de l'existence du produit (un produit en favoris a pu être supprimé):
    $resultat = executeRequete("SELECT * FROM produit WHERE id_produit = :id_produit", array(':id_produit' => $_GET['id_produit']));
    if($resultat->rowCount() == 0){// si il n'y a pas de produit de id en bdd, on oriente le membre vers la boutique
        header('location:index.php');
        exit;
    }

    // 3- Le produit existe, je peux donc l'afficher:
    $produit = $resultat->fetch(PDO::FETCH_ASSOC); // pas de boucle car un seul produit par id_produit
    //debug($produit);
    extract($produit); // crée variable nommés au nom des indices du tableau et qui prennent la valeur correspondante
    
    // 4 -Bouton d'ajout au panier
    if($produit['stock'] > 0){ // si le stock est disponible on affiche le bouton

        // Pour le script pnaier.php, il nous faut 2 infos: id produit et la quantité ajoutée au panier
        $panier .= '<form method="post" action="panier.php" class="my-4">';
                    // id_produit
        $panier .= '<input type="hidden" name="id_produit" value="'.$id_produit.'">';             
        // on renvoie l'id_produit dans $_POST champ de type caché pour ne pas pouvoir le modifier

        // Selecteur de quantité de produit
        $panier .= '<select name="quantite" class="custom-select col-3">';
            for($i = 1; $i <= $stock && $i <= 5 ;$i ++){ // on fait 5 tours de boucle max à concurrence du stock disponible (si le stock est inférieur à 5, on s'arrête à la quantité en stock)
               $panier .= "<option> $i </option>";
            }
        $panier .= '</select>';
        // bouton submit
        $panier .= '<input type="submit" name="ajout_panier" value="ajouter au panier" class="btn btn-info col-8 offset-1">';

                    '</from>';
    }else{// sinon on affiche "rupture de stock"
        $panier .= '<p>Produit en ruptue de stock</p>';
    } 


}else{ // si l'indice "id_produit" n'existe pas, on a accede a cette page sans avoir demandé un produit. On redirige donc vers la boutique
    header('location:index.php');
    exit;
}

// 4- Affichage de la amodale de confirmation au panier:
//debug($_GET);
if(isset($_GET['statut_produit']) && $_GET['statut_produit'] == 'ajoute'){
    $modale = '<div class="modal fade text-center" id="modal-panier" role="dialog">
                 <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                        <div class="modal-header ">
                        <h4 class="modal-title">Produit aujouté au panier !</h4>
                     </div> 
                     <div class="modal-body">
                         <p><a href="panier.php">Voir le panier</a></p>
                         <p><a href="index.php">Continuer mes achats</a></p>
                        </div>
                    </div> 
                </div> 
            </div>';// fin class modal
}

// Exercice: 
// créer une suggestion produits, afficher 2 produits (photo et titre) aléatoirement appartenant à la catégorie du produit actuellement affiché dans la fiche produit. Ces produits doivent être différents du produit affiché dans la fiche. La photo est cliquable et amène au détail du produit.
// Vous utilisez la variable $suggestion pour afficher le contenu

    $resultat = executeRequete("SELECT id_produit, photo, titre FROM produit WHERE categorie = :categorie AND id_produit <> :id_produit ORDER BY RAND() LIMIT 2", array(':categorie' => $categorie, ':id_produit' => $id_produit));
    
	//debug($produit);


    while ($produit = $resultat->fetch(PDO::FETCH_ASSOC)) {
        //debug($produit);
    
        $suggestion .= '<div class="col-sm-3">';
            $suggestion .= '<a href="?id_produit=' . $produit['id_produit'] . '">';
                $suggestion .= '<img src="' . $produit['photo'] . '" alt="' . $produit['titre'] . '" class="img-fluid">';
            $suggestion .= '</a>';	
    
            $suggestion .= '<h4>' . ucfirst($produit['titre']) . '</h4>';
    
        $suggestion .= '</div>';
    }
    


require_once 'inc/header.php';
echo $modale;
?>
<!-- produit -->
<div class="row">
    <div class="col-12 mt-4">
        <h1><?php echo $titre; ?></h1>
    </div>

    <div class="col-md-8">
        <img src="<?php echo $photo; ?>" alt="<?= $titre; ?>" class="img-fluid"> <!--on peut remplacer l'ouverture php suivi d'un echo par <point interrogation =--> 
    </div>
    <div class="col-md-4">
        <h3>Description</h3>
        <p><?php echo $description; ?></p>

        <h3>Détails</h3>
        <ul>
            <li>Catégorie : <?php echo $categorie; ?></li>
            <li>Couleur : <?php echo $couleur; ?></li>
            <li>Taille : <?php echo $taille; ?></li>
        </ul>

        <h4>Prix : <?php echo number_format($prix, 2, ',', ''); ?> €</h4>
        
        <?php echo $panier; ?>

        <p>
        <a href="index.php?categorie=<?php echo $categorie; ?>">Retour vers la catégorie '<?php echo $categorie; ?>'</a>
        </p>
    </div><!--.col-md-4-->
</div><!--fermeture class row-->

<!--Exercice suggestion produits -->
<hr>
<div class="row">
    <div class="col-12">
        <h3>Suggestion de produits</h3>
    </div>
    <?php echo $suggestion; ?>
</div>

<script>
// script d'affichage de la modale d'ajout au panier
$(function(){
    $('#modal-panier').modal('show'); // pour déclencher l'affichage
});
</script>


<?php
require_once 'inc/footer.php';