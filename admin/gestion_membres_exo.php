<?php
require_once '../inc/init.php';

/*exercice: Vous créez toute la page de gestion des membres back-office:
1- Seul un administrateur doit avoir accès à cette page, les autres membres sont redirigé vers la page de connexion.

2-Afficher dans cette page, tous les membres inscrits sur le site sous forme de table HTML, avec toutes les infos sauf son mdp. Vous ajoutez à ce tableau une colonne "action".

3- Afficher le nombre de membres inscrits

4- Dans la colonne "action", ajouter un lien pour pouvoir supprimer un membre, sauf lui-même qui est connecté

5- Dans la colonne "action", ajouter un lien pour pouvoir modifier le statut des membres en admin pour les membres ou en membre pour les admins. Le membre connecté ne peut pas modifier son propre statut
*/

// 1- Vérifier que le membre est bien administrateur:
    if(!estAdmin()){
        header('location:../connexion.php');
        exit;
    }

// 5 - Modifier le statut du membre:
    var_dump($_POST['updateStatut']);
    if(isset($_GET['id_membre']) && ($_GET['id_membre'] != $_SESSION['membre']['id_membre'])){
        if(isset($_POST['updateStatut'])){
            $resultat = executeRequete("REPLACE INTO membre (statut) VALUES (:statut)", array(':statut' => $_POST['updateStatut']));
            
            if($resultat->rowCount() == 1){ // si le delete retourne une ligne c'est que la variable requete a bien marché
                $contenu .= '<div class="alert alert-success">Le statut a bien été mis  àjour</div>';
            }else{ // si le delete retourne 0 ligne c'est que le produit n'est pas en BDD
                $contenu .= '<div class="alert alert-danger">Vous ne pouvez pas modifier votre statut...</div>';
                }
        }
        
    }
    
    
// 4 - Supprimer les membres:

if(isset($_GET['id_membre']) && ($_GET['id_membre'] != $_SESSION['membre']['id_membre'])){ // si existe id_membre dans l'url c'est qu'on a demandé la suppression et si l'id_membre et différent de l'id $_SESSION
   // var_dump($_GET['id_membre']);
   // var_dump($_SESSION['membre']['id_membre']);
    
    $resultat = executeRequete("DELETE FROM membre WHERE id_membre = :id_membre", array(':id_membre' => $_GET['id_membre']));

 debug($resultat->rowCount()); //on obtient 1 lors de la suppression d'une ligne
if($resultat->rowCount() == 1){ // si le delete retourne une ligne c'est que la variable requete a bien marché
    $contenu .= '<div class="alert alert-success">Le membre a bien été supprimé</div>';
}else{ // si le delete retourne 0 ligne c'est que le produit n'est pas en BDD
    $contenu .= '<div class="alert alert-danger">Vous ne pouvez pas supprimé ce compte</div>';
    }
}

if(isset($_GET['statut'])){// si existe statut dans l'url c'est qu'on a demandé modification. On selectionne ce produit en BDD pour remplir le formulaire de modification
    $resultat = executeRequete("SELECT * FROM membre WHERE statut = :statut", array('statut' =>$_GET['statut']));
    //debug($resultat); objet PDOStatement
    $statut_actuel = $resultat->fetch(PDO::FETCH_ASSOC); // on fetch les données du produit en cours de modification sans boucle car il est unique par identifiant
//debug($statut_actuel['statut']);
}
    
// 2- Afficher tous les membres inscrits sur le site
    $resultat = executeRequete("SELECT * FROM membre"); // objet PDOStatement
$contenu .= '<p class="mt-3">Nombre de membres : ' . $resultat->rowCount() . '</p>';
$contenu .= '<div class="table-responsive">';
    $contenu .= '<table class="table">';
       
        $contenu .= '<tr>';
            $contenu .= '<th>id</th>';
            $contenu .= '<th>pseudo</th>';
            $contenu .= '<th>mot de passe</th>';
            $contenu .= '<th>nom</th>';
            $contenu .= '<th>prenom</th>';
            $contenu .= '<th>email</th>';
            $contenu .= '<th>civilite</th>';
            $contenu .= '<th>ville</th>';
            $contenu .= '<th>code postal</th>';
            $contenu .= '<th>adresse</th>';
            $contenu .= '<th>action</th>';
        $contenu .= '</tr>';

    
    // debug($resultat);
    
    while ($membre = $resultat->fetch(PDO::FETCH_ASSOC)){// cette condition donne 1 tableau par membre
        $contenu .= '<tr>';
            foreach($membre as $indice => $information){ // récupere les valeurs de chaque $membre
                if($indice == 'mdp'){ // quand je suis sur l'indice mot de passe j'affiche NC
                    $contenu .= '<td>'.'NC'.'</td>';
                }else{
                    $contenu .= '<td>' . $information .'</td>';
                }
            }
             // On ajoute les liens "action":
        $contenu .= '<td>';
        $contenu .= '<div><form method="POST" action="gestion_membres.php">
                    <select name="updateStatut" style="width:80px" id="selection" class="form-control font-italic">
                    <option>Mofifier...</option>
                    <option name="updateStatut" value="0">0</option>
                    <option name="updtaeStatut" value="1">1</option>
                    </select>
                    <div><input type="submit" value="Modifier" class="btn btn-outline-info"></div>
                    </form>
                    </div>';
        $contenu .= '<div><a href="?id_membre='.$membre['id_membre'].'" onclick="return(confirm(\'Etes-vous certain de supprimer ce produit ?\'))">supprimer</a></div>';
        // confirm() retourne true quand on valide et false quand on annule - return false bloque le lien et donc ne declenche pas d'action de suppression
        $contenu .= '</td>';
        $contenu .= '</tr>';
    }
    $contenu .= '</table>';
$contenu .= '</div>';


require_once '../inc/header.php';
?>
<h1 class="mt-4 mb-4">Gestion des membres</h1>
   

<?php
echo $contenu;


require_once '../inc/footer.php';