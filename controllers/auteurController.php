<?php
$action=$_GET['action'];
switch($action){
    case 'list' :
        // traitement du formulaire de recherche
        $nom="";
        $prenom="";
        $nationaliteSel="Toutes";
        if(!empty($_POST['nom']) || !empty($_POST['prenom']) || !empty($_POST['nationalite'])){
            $nom= $_POST['nom'];
            $prenom= $_POST['prenom'];
            $nationaliteSel= $_POST['nationalite'];
        }
        $lesNationalites=Nationalite::findAll();
        $lesAuteurs=Auteur::findAll($nom, $prenom, $nationaliteSel);
        include('vues/auteur/listeAuteurs.php');
        break;
    case 'add' :
        $mode="Ajouter";
        $lesNationalites=Nationalite::findAll();
        include('vues/auteur/formAuteur.php');
    break;
    case 'update' :
        $mode="Modifier";
        $lesNationalites = Nationalite::findAll();
        $leAuteur=Auteur::findById($_GET['num']);
        include('vues/auteur/formAuteur.php');
        break;
    case 'delete' :
        $leAuteur= Auteur::findById($_GET['num']);
        $nb= Auteur::delete($leAuteur);
        if($nb==1){
        $_SESSION['message']=["success"=>"L'auteur a bien été supprimé"];
        }else{
            $_SESSION['message']=["danger"=>"L'auteur n'a pas été supprimé"];
        }
        header('location: index.php?uc=auteurs&action=list');
        exit();
        break;
    
    case 'validerForm' :
        $auteur= new Auteur();
        $nationalite=Nationalite::findById($_POST['nationalite']);
        $auteur->setNom($_POST['nom'])
                ->setPrenom($_POST['prenom'])
                ->setNationalite($nationalite);

        if(empty($_POST['num'])){ // cas d'une création
            $nb=Auteur::add($auteur);
            $message = "ajouté";
        }else{ //  cas d'une modif
            $auteur->setNum($_POST['num']);
            $nb=Auteur::update($auteur);
            $message = "modifié";
        }
        if($nb==1){
            $_SESSION['message']=["success"=>"L'auteur a bien été $message"];
        }else{
            $_SESSION['message']=["danger"=>"L'auteur a bien été $message"];
        }
        header('location: index.php?uc=auteurs&action=list');
        exit();
        break;
}
?>