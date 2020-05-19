<?php
require_once '../inc/init.php';
// 1- Vérifier que le membre est bien administrateur:
    if(!estAdmin()){
        header('location:../connexion.php');
        exit;
    }
   
    // 4- Enregistrement du produit en BDD
    //debug($_POST);
    if(!empty($_POST)){ // si le formulaire de création de produit a été envoyé
        // ici il faudrait mettre controle sur le formulaire dans un site aboutit

        $photo_bdd = ''; // par défault champ photo est vide en BDD

        // 9 - Suite de la modification de la photo:
            if (isset($_POST['photo_actuelle'])){
                $photo_bdd = $_POST['actuelle']; //quand on est en modification, on remet le chemin de la photo qui est dans le formulaire en BDD 
            }
        // Traitement de la photo produit:
        // debug($_FILES); // $_FILES est une superglobale généré par le type="file" du champ "phot" du formulaire. Le premier indice ['photo'] de $_FILES correspondant au "name" de cet input. A cet indice, se trouve un sous-tbleau avec notament l'indice "name" qui contient le nom du fichier en cours de téléchargement, l'indice['type'] qui contient le type du fichier (exemple image/png), l'indice "size" qui contient la taille du fichier.
        if(!empty($_FILES['photo']['name'])){ // s'il y a un nom de fichier dans $_FILES, c'est que nous sommes en train de télécharger un fichier
            $nom_photo = 'ref' . $_POST['reference'] . '_' . $_FILES['photo']['name']; // on ajoute au nom du fichier qui est en cours de téléchargement la référence de notre produit afin de créer un nom de fichier photo unique
            $photo_bdd = 'photos/' . $nom_photo; // cette variable contient le chemin relatif du fichier photo qui est insérer en BDD un peu plus bas, et qui correspond au fichier photo physique que l'on sauvegarde sur notre serveur juste après. Cettvaleur sera utilisé dans les attributs src des balises images.
            copy($_FILES['photo']['tmp_name'], '../' . $photo_bdd); //on sauvegarde le fichier en cours de téléchargement temporairement stocké à l'adresse $_FILES['photo']['tmp_name'] vers l'endroit défini  par la variable $photo_bdd, autrement dit dans notre dossier "photos/" du site

        }

        // Insertion du produit en BDD
        $requete = executeRequete("REPLACE INTO produit VALUES (:id_produit, :reference, :categorie, :titre, :description, :couleur, :taille, :public, :photo, :prix, :stock)", array(
            ':id_produit' => $_POST['id_produit'], // vaut 0 par défaut pour que le replace fasse une insertion. Dans le cas ou l'id existe en BDD, le replace fera un update du produit
            ':reference' => $_POST['reference'],
            ':categorie' => $_POST['categorie'],
            ':titre' => $_POST['titre'],
            ':description' => $_POST['description'],
            ':couleur' => $_POST['couleur'],
            ':taille' => $_POST['taille'],
            ':public' => $_POST['public'],
            ':photo' => $photo_bdd,
            ':prix' => $_POST['prix'],
            ':stock' => $_POST['stock'],
        ));

        if($requete){ // si la vriable a reçu un objet PDOStatement, implicitement evalué à true, c'est que la requete a marché.
            $contenu .= '<div class="alert alert-success">Le produit a été enregistré.</div>';
        }else{ // sinon on a reçu false, il ya une erreur
            $contenu .= '<div class="alert alert-danger">Une erreur est survenue...</div>';
        }

    } // fin if(!empty($_POST))

    // 8- Modification d'un produit : remplissage du formulaire
    // debug($_GET);
    if(isset($_GET['id_produit'])){// si existe id_produit dans l'url c'est qu'on a demandé modification. On selectionne ce produit en BDD pour remplir le formulaire de modification
        $resultat = executeRequete("SELECT * FROM produit WHERE id_produit = :id_produit", array(':id_produit' =>$_GET['id_produit']));
        //debug($resultat); objet PDOStatement
        $produit_actuel = $resultat->fetch(PDO::FETCH_ASSOC); // on fetch les données du produit en cours de modification sans boucle car il est unique par identifiant
    }
    //debug($produit_actuel);
    
require_once '../inc/header.php';
// 2- Onglets de navigation
?>
<h1 class="mt-4 mb-4">Gestion Boutique</h1>

<ul class="nav nav-tabs">
<li><a href="gestion_boutique.php" class="nav-link ">Affichage des produits</a></li>
<li><a href="formulaire_produit.php" class="nav-link active">Formulaire produit</a></li>
</ul><br>

<?php
echo $contenu; // pour afficher les messages

// 3- Formulaire HTML
?>

<form action="" enctype="multipart/form-data" method="post"> <!-- l'attribut enctype que le formulaire envoie des données binaires (fichier) et texte (champs). Cela nous permettra de télécharger une photo du produit -->
    <div><input type="hidden" name="id_produit" value="<?php echo $produit_actuel['id_produit'] ?? 0;?>"></div> <!-- champs de type hidden nécessaire pour la modification d'un produit car on en aura besoin de son id pour la requête SQL de modification. Quand on met une valeur a 0 pour l'identifiant, le REPLACE en BDD va se comporter comme un INSERT (création du produit) sinon il va le replacer.-->
    <div>
        <div><label for="reference">Référence</div>
        <div><input type="text" name="reference" id="reference" value="<?php echo $produit_actuel['reference'] ?? '';?>"></div>
    </div>
    <div>
        <div><label for="categorie">Catégorie</div>
        <div><input type="text" name="categorie" id="categorie" value="<?php echo $produit_actuel['categorie'] ?? '';?>"></div>
    </div>
    <div>
        <div><label for="titre">Titre</div>
        <div><input type="text" name="titre" id="titre" value="<?php echo $produit_actuel['titre'] ?? '';?>"></div>
    </div>
    <div>
        <div><label for="description">Description</div>
        <div><textarea name="description" id="description"><?php echo $produit_actuel['description'] ?? '';?></textarea></div>
    </div>
    <div>
        <div><label for="couleur">Couleur</div>
        <div><input type="text" name="couleur" id="couleur" value="<?php echo $produit_actuel['couleur'] ?? '';?>"></div>
    </div>
    <div>
        <div><label>Taille</div>
        <div>
            <select name="taille">
                <option <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 'S') echo 'selected';?>>S</option>
                <option <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 'M') echo 'selected';?>>M</option>
                <option <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 'L') echo 'selected';?>>L</option>
                <option <?php if(isset($produit_actuel['taille']) && $produit_actuel['taille'] == 'XL') echo 'selected';?>>XL</option>
            </select>
        </div>
    </div>
    <div>
        <div><label>Public</label></div>
        <div>
            <input type="radio" name="public" value="m" checked> Masculin
            <input type="radio" name="public" value="f"<?php if(isset($produit_actuel['public']) && $produit_actuel['public'] == 'f') echo 'checked';?>> Féminin
            <input type="radio" name="public" value="mixte" <?php if(isset($produit_actuel['public']) && $produit_actuel['public'] == 'mixte') echo 'checked';?>> Mixte
        </div>
    </div>
    <div>
        <div><label>Photo</label></div>
        <div><input type="file" name="photo"></div> <!-- Ne pas oublier de mettre l'attribut enctype="multipart/form-data" sur la balise <form>-->
    <!-- 9- Modification de la photo-->
    <?php 
    if(isset($produit_actuel['photo'])){ // en cas de modification de produit nous affichons photo actuelle
        echo '<p>photoactuelle</p>';
        echo '<p><img src="../'.$produit_actuel['photo'].'" style="width:90px"></p>';
        // attention nous sommes dans le sous dossier admin
        echo '<input type="hidden" name="photo_actuelle" value="'.$produit_actuel['photo'].'">';
        // on met ce champ (caché pour ne pas le modifier) pour remplir $_POST lors de l'envoi du formulaire et remettre la valleur enBDD à la place d'un string vide.
    }
    ?>
    </div>
    <div>
        <div><label for="prix">Prix</div>
        <div><input type="text" name="prix" id="prix" value="<?php echo $produit_actuel['prix'] ?? 0;?>"></div>
    </div>
    <div>
        <div><label for="stock">Stock</div>
        <div><input type="text" name="stock" id="stock" value="<?php echo $produit_actuel['stock'] ?? 0;?>"></div><br>
    </div>
    <div><input type="submit" value="Enregistrer le produit" class="btn btn-outline-info"></div>


</form>


<?php
require_once '../inc/footer.php';