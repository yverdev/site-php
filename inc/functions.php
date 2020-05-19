<?php
// debug:
function debug($variable){
    echo '<pre>';
    print_r($variable);
    echo '</pre>';
}

// Fonction qui indique si le mebre est connecté:
function estConnecte(){
    if(isset($_SESSION['membre'])){ // si existe indice dans la session c'est que l'internaute est passé par la page de connexion avec les bons identifiants
        return true; // il est connecté
    } else {
        return false;
    }
}

// Fonction qui indique si le membre est admin connecté:
function estAdmin(){
    if(estConnecte() && $_SESSION['membre']['statut'] == 1){ // si le membre est connecté alors on regarde si son statut dans la $_SESSION['membre']
        //a la valeur 1, auquel cas il est admin et connecté
        return true;
    }else{
        return false;
    }
}

// Fonction qui réalise les requêtes préparés
function executeRequete($requete, $param = array()){ // Le paramètre $requete attend de recevoir requête SQL sous forme de string
    // $param attend un array avec les marqueurs associés à leur valeur. Ce paramètre est optionnel, car on lui affecte un array vide par défaut.
    
    //On échappe les valeurs de $param car elles proviennent de l'internaute:
    foreach($param as $indice => $valeur){ // on parcourt $_POST en prenant chaque indice et chaque valeur
        $param[$indice] = htmlspecialchars($valeur, ENT_QUOTES); // on évite les injections XSS et CSS.
        // A chaque tour de boucle on prend la valeur du tableau que l'on échappe et que l'on réaffecte à son emplacement d'origine.
    }

    // Requête préparé:
    global $pdo; // on accède a la variable globale définie à l'extérieur de cette fonction dans le fichier init.php
    $resultat = $pdo->prepare($requete); // on prépare la requête envoyée à notre fonction
    $succes = $resultat->execute($param); // puis on execute la requête en lui passant le tableau qui associe les marqueurs et les valeurs
    // Excute retourn etjs un boolean (true = succes, false = echec)
    if($succes){
        return $resultat; // On retourne l'objet PDOStatement en cas de succès, car nous en avons besoin quand on fait une requête de selection
    }else{
        return false; // On retourne false en cas d'erreur sur la requête
    }
}

// Fonction qui crée le panier
function creationPanier(){
    if(!isset($_SESSION['panier'])){ // si n'existe pas l'indice panier on va le créer

        $_SESSION['panier']['id_produit'] = array();
        $_SESSION['panier']['titre'] = array();
        $_SESSION['panier']['reference'] = array();
        $_SESSION['panier']['quantite'] = array();
        $_SESSION['panier']['prix'] = array();
    }
}


// Fonction qui ajoute au panier
function ajoutProduit($id_produit, $titre, $reference, $quantite, $prix){ // reception des valeurs lors de l'appel de la fonction dans panier.php
    creationPanier(); // créer le panier si il n'existe pas
    // nous devons savoir si l'id_produit que l'on souhaite ajouter est deja présent dans le panier pour ne pas l'ajouter une nouvelle fois ce produit, mais lui ajouter la nouvelle quantite:
    $position_produit = array_search($id_produit, $_SESSION['panier']['id_produit']);
    // cette fonction predefinie retourne l'indice de l'element recherche. Ici on obtient la position du produit "id_produit" dans le tableau
    // si le produit n'y est pas array_search retourne false
    
    if($position_produit === false){ // si le produit n'est pas encore dans le panier on l'y ajoute
        $_SESSION['panier']['id_produit'][] = $id_produit; // les crochets vide permettent d'ajouter l'element a la fin du tableau
        $_SESSION['panier']['titre'][] = $titre; 
        $_SESSION['panier']['reference'][] = $reference;
        $_SESSION['panier']['quantite'][] = $quantite;
        $_SESSION['panier']['prix'][] = $prix;

    }else{ // sinon on ajoute la nouvelle quantite à la qauntite deja presente dans le panier
        if($_SESSION['panier']['quantite']['$position_produit'] + $quantite <= 5){ // si on ne dépasse pas 5 exemplaires
            $_SESSION['panier']['quantite'][$position_produit] += $quantite; // nous allons precisement à l'indice du produit deja present et lui ajoutons la nouvelle quantite 
        }else{
            $_SESSION['panier']['quantite'][$position_produit] = 5;
        }
    }
}

// Fonction qui calcul le montant total du panier:
function montantTotal(){
    $total = 0;
    for($i=0;$i < count($_SESSION['panier']['id_produit']);$i++){ //tant que $i est inferieur aux nombres de produit dans le panier on additionne le prix du produit multiplié par sa quantite
        $total += $_SESSION['panier']['quantite'][$i] * $_SESSION['panier']['prix'][$i];
        // on ajoute dans la variable $total avec l'operateur += le resultat de la multiplication de la quantite par le prix de chaque produit
    }
    return $total; // retourne le resultat  du calcul a l'endroit ou la fonction est appelée
}

// Fonction qui retire un produit du panier:
function retirerProduit($id_produit){
    // on determine la position (=indice) du produit dans le panier
    $position_produit = array_search($id_produit, $_SESSION['panier']['id_produit']); // retourne l'indice du produit dans le panier ou false s'il n'y est pas
    if ($position_produit !== false){ // si le produit est dans le panier, on peut le couper
        array_splice($_SESSION['panier']['id_produit'], $position_produit, 1);// on coupe est remplce la portion du tableau qui debute a l'indice $position_produit et sur 1 element (=1seul produit)
        array_splice($_SESSION['panier']['titre'], $position_produit, 1);
        array_splice($_SESSION['panier']['reference'], $position_produit, 1);
        array_splice($_SESSION['panier']['quantite'], $position_produit, 1);
        array_splice($_SESSION['panier']['prix'], $position_produit, 1);
    }
}

//-----------------------------------------------
// Exercice: créer une fonction qui retourne le nombre produit différents (nombre de ligne dans le panier). Puis afficher le résultat à côté du lien panier dans le menu navigation. Exemple: panier(2). En l'basence de produit, on affiche panier(0).
//debug($_SESSION['panier']['id_produit']);
function CalcItemNumber(){

   if(isset($_SESSION['panier'])){
    //$itemNumber = count($_SESSION['panier']['id_produit']); 
    $itemNumber = array_sum($_SESSION['panier']['quantite']);
    return $itemNumber;
   }
   return '0';
}
