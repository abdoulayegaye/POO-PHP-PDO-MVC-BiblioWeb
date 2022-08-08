<?php ob_start();
session_start(); 
include("modeles/Continent.php");
include("modeles/Livre.php");
include("modeles/Auteur.php");
include("modeles/Genre.php");
include("modeles/Nationalite.php");
include("modeles/monPdo.php");
include("vues/header.php");
include("vues/messagesFlash.php");

$uc =empty($_GET['uc']) ? "accueil" : $_GET['uc'];

switch($uc){
case 'accueil' :
include('vues/accueil.php');
break;
case 'continents' :
include('Controllers/continentController.php');
break;
case 'nationalites':
    include('Controllers/nationaliteController.php');
    break;
case 'genres':
    include('Controllers/genreController.php');
    break;
case 'auteurs':
    include('Controllers/auteurController.php');
    break;
case 'livres':
    include('Controllers/livreController.php');
    break;
}
include("vues/footer.php");
?>