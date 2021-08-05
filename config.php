<?php
    ////////////////////////////////////////////////////////////////////////////
    // CONFIGURATION DE LA BASE DE DONNEES
    ///////////////////////////////////////////////////////////////////////////
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

    ///////////////////////////////////////////////////////////////////////////
    // CONFIGURATION DU FORUM
    ///////////////////////////////////////////////////////////////////////////
    // Nombre de question affich�e par page
    $nb_post_par_page = 1 ;
    // Nombre de jour sans lecture avant suppression d'un question
    // si supperieur � 0 suppression. Sinon pas de suppression des messages
    $nb_jour_avant_suppression = -1 ;
    // Nom du forum
    $titre_forum = "La foire aux questions" ;
    // Url du forum. NE PAS OUBLIER LE / FINAL !!!
    $url_site = "http://localhost/nfaq/" ;
    // Url de retour pour quitter le forum (le boutton QUITTER)
    $url_de_retour = "http://localhost/" ;
    // Fichier CSS
    $fichier_css = $url_site . "style.css" ;
    // Indique s'il faut afficher les r�ponse dans la liste (true ou false)
    $afficher_reponse_dans_liste = true ;
    // Indique s'il est possible de recevoir un e-mail
    $can_send_email = true ;
    // Nom du fichier affichant le forum
    $SCRIPTNAME = "forum.php" ;
    // Aficher les images dans la liste des message. true = oui, false = non
    $afficher_icone = true ;
    // Nombre de r�ponse pour qu'un message soit populaire
    $nb_rep_pr_msg_hot = 25 ;
    // Indique la taille des barres et du tableau
    $width = "76%" ;
    // Objet de l'e-mail envoy� quand il y a une r�ponse
    $subjectEmail = "Reponse au forum NFAQ 1.2.1" ;
    // Texte envoy� dans l'e-mail.
    // { ADRESSE_AUTEUR } = adresse de l'auteur de la question
    // { TEXTE } = r�ponse
    // { AUTEUR } = auteur de la r�ponse
    // { QUESTION } = question pos�e
    $texteEmail = "Bonjour,\n\nvous recevez cet e-mail car vous avez posez la question { QUESTION } sur le forum NFAQ 1.2.1.\n{ AUTEUR } vous &agrave; r&eacute;pondu :\n{ TEXTE }\n\nVous pouvez directement lui r&eacute;pondre &agrave; l'adresse { ADRESSE_AUTEUR }" ;


    ///////////////////////////////////////////////////////////////////////////
    // Gestion des smiley
    ///////////////////////////////////////////////////////////////////////////
    $smiley[] = array("Z:-)", "diable.gif", "Diablotin");
    $smiley[] = array(":-Q", "fume.gif", "Fumeur");
    $smiley[] = array(":-))", "bigsourire.gif", "Gros sourire");
    $smiley[] = array(":-D", "bigsourire.gif", "Gros sourire");
    $smiley[] = array(":)", "sourire.gif", "Sourire");
    $smiley[] = array(":(", "decu.gif", "D��u");
    $smiley[] = array(";-)", "clin.gif", "Clin d'oeuil");
    $smiley[] = array("X-(", "couteau.gif", "Couteau dans la t�te");
    $smiley[] = array(":o)", "debile.gif", "D�bile");
    $smiley[] = array(":-((", "grrr.gif", "Vraiment pas content");
    $smiley[] = array("8-)", "hallucine.gif", "J'hallucine");
    $smiley[] = array(":-?", "heu.gif", "H�site");
    $smiley[] = array(":-p", "langue.gif", "Tire la langue");
    $smiley[] = array(":-o", "oh.gif", "Oh");
    $smiley[] = array(":-@", "perdu.gif", "Perdu");
    $smiley[] = array(";-(", "pleure.gif", "Pleure");
    $smiley[] = array("B-)", "tropcool.gif", "Trop cool");
    $smiley[] = array(":-)", "sourire.gif", "Souris");
    $smiley[] = array(":-(", "decu.gif", "D��u");
    $smiley[] = array(":fuck:", "fuck.gif", "Fuck it !");
    $smiley[] = array("8o)", "clown.gif", "Le clown");

    ///////////////////////////////////////////////////////////////////////////
    // Gestion du BBCode
    ///////////////////////////////////////////////////////////////////////////
    $bbcode[] = array("[b]", "<b>", "Balise d'ouverture du texte en gras") ;
    $bbcode[] = array("[/b]", "</b>", "Balise de fermeture du texte en gras") ;
    $bbcode[] = array("[i]", "<i>", "Balise d'ouverture du texte en italique") ;
    $bbcode[] = array("[/i]", "</i>", "Balise de fermeture du texte en italique") ;
    $bbcode[] = array("[u]", "<u>", "Balise d'ouverture du texte en soulign�") ;
    $bbcode[] = array("[/u]", "</u>", "Balise de fermeture du texte en soulign�") ;
    $bbcode[] = array("[p]", "<p>", "Balise d'ouverture d'un paragraphe") ;
    $bbcode[] = array("[/p]", "</p>", "Balise de fermeture d'un paragraphe") ;
    $bbcode[] = array("[br]", "<br>", "Balise de saut de ligne") ;
    $bbcode[] = array("[espace]", "&nbsp;", "Balise ins�rant un espace obligatoire") ;
    $bbcode[] = array("[code]", "<pre>", "Balise d'ouverture d'un texte type code source") ;
    $bbcode[] = array("[/code]", "</pre>", "Balise de fermeture d'un texte type code source") ;

    ///////////////////////////////////////////////////////////////////////////
    // Gestion de la censure
    ///////////////////////////////////////////////////////////////////////////
    $censure = array("merde", "putain", "pute", "encul", "enfoir", "bite", "couille",
                     "salop", "connard", "troud", "trou du cul") ;
    $texte_a_inserer = "[i]**censure**[/i]" ;
?>