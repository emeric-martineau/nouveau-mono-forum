<?php
    // Host de la base de donn�es
    $host = "localhost";
    // nom d'utilisateur ou login pour la base de donn�es
    $user = "root";
    // mot de passe pour la base de donn�es
    $pw = "";
    // nom de la base de donn�es
    $db = "forum" ;
    // pr�fixe des noms de table
    $prefixe_de_table = "faq_" ;

    $nb_post_par_page = 1 ;
    // Nombre de jour sans lecture avant suppression d'un question
    // si supperieur � 0 suppression. Sinon pas de suppression des messages
    $nb_jour_avant_suppression = -1 ;
    // Nom du forum
    $titre_forum = "La foire aux questions" ;
    // Url du forum. NE PAS OUBLIER LE / FINAL !!!
    $url_site = "http://localhost/nfaq-1.4/" ;
    $can_send_email = true ;
    // Nom du fichier affichant le forum
    $SCRIPTNAME = "forum.php" ;
    $nb_rep_pr_msg_hot = 25 ;
    $theme = 'hivernal-skin' ;
?>