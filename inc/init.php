<?php
// Ce fichier est inclus dans tous les scripts pour notamznt initialiser la connexion à la BDD, l'acces aux sessions, définir les variables,
//et inclure le fichier functions.php

// Connexion à la BDD
$pdo = new PDO('mysql:host=localhost;dbname=site', // driver mysql, nom du serveur, suivi du nom de la BDD
                'root', // login BDD
                'root', // mot de passe de la BDD
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, // pour afficher les erreurs sql dans le navigateur
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' // définit le jeu de caractère des échanges avec la BDD
                ));

// Session

session_start(); // crée un fichier appelé session sur le serveur dans lequel on stock des données: celles du membre ou de sonpanier.
                // Si la session existe deja on y accede directement à l'aide de l'identifiant reçu dans le poste de l'internaute.

// Constante qui contient le chemin du site
define('RACINE_SITE', '/PHP/09-site/'); // ici on indique le dossier dans lequel se trouve le site a partir de localhost. S'il n'est dans aucun dossier, on met un / tout seul
//Permet de créer des chemins absolus à partir de localhost utilisés notament dans le header.php qui est inclus dans des pages qui se trouvent dans différents
//sous-dossiers du site. Par conséquent, les chemins relatifs vers les sources changent selon ces sous-dossiers, ce qui n'est pas le cas en chemin absolu.
/*
chemin relatif:
    ../dossier_cible/fichier.php = on part du dossier courant, et on remonte dans le dossier cible et on accède au fichier

    dossier_cible/fichier.php = onpart du dossier courant et on entre directement dans le dossier cible qui se trouve au meme endroit

chemin absolu:
    /dossier_cible/fichier.php = on part de la racine du serveur sui est localhost dans notre cas, peu importe l'endroit ou on se trouve.
    //le / du debut indique qu'il s'agit du chemin absolu
*/

// Initialisation variables d'affichage:
$contenu = '';
$contenu_gauche = '';
$contenu_droite = ''; // on y mettra du HTML à afficher

//Inclusion:
require_once 'functions.php';