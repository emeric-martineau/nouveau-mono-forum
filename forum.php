<?php
/*******************************************************************************
 * Copyright (C) 2001-2004 MARTINEAU Emeric (php4php@free.fr)
 *
 * Nouvelle foire aux question.
 *
 * Version 1.2
 *
 * Voici un forum qui ne tient que sur un seul fichier. Ultra rapide, simple a 
 * mettre en place. Les urls sont cliquables, la personne qui pose une question
 * peut recevoir directement un e-mail pour chaque reponse. Les messages sont
 * nettoyer tous les X jours. Vous avez besoin d'une base Mysql. On peut déposer
 * du code php sans risque.
 *
 * Script originel : Bruno Castagné <ccrealink@aol.com>
 *                   http://www.net16annonce.com
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 ******************************************************************************

Bubule le 21/08/2003 : (version 1)
  - formatage du code source,
  - suppression de \" au profit de '
  - mise en fonction du cliquage de lien,
  - ajout de @ devant les fonctions de BDD.
  - suppression de l'envoie de message directe pour éviter les spam,
  - vérification des données venant du formulaire vers la base de données,
  - adressage des variables des formulaires par HTTP_*_VARS,
  - suppression de javascript,
  - gestion de l'affichage des boutons suivant et précédant,
  - personnalisation du nombre de message par page.

Bubule le 22/08/2003 :
  - prefixe de table de base de données,
  - amélioration du système d'auto netoyage,
  - suppression du passage par formulaire du paramètre envoi qui indiquait s'il
    fallait envoyer un e-mail ou non,
  - optimisation des tables après suppression,
  - ajout de la possibilité de configurer le titre du forum,
  - ajout de la possibiliter de configurer un adresse de retour,
  - nom du fichier forum.php en automatique,
  - configuration de l'url du forum,
  - la liste n'apparait pas lors d'un poste de nouveau message ou d'une réponse.

Bubule le 25/08/2003 :
  - affichage ou non d'un lien avec l'adresse e-mail,
  - n'envoie pas d'e-mail s'il n'y a pas d'e-mail,
  - possibilité de ne pas rendre l'e-mail public,
  - possibilité de ne pas afficher la liste des réponses dans la listes des
    question,
  - réaffichage des données lorsqu'on rempli un formulaire incorrectement,
  - paramètre la possibilité d'envoyer ou non des e-mail si la fonction mail()
    est désactivée,
  - tous l'aspect du site est configurable par CSS,
  - correction dans le html,
  - mémorisation du nom d'auteur et adresse e-mail par cokkie,
  - retour automatique à la page de la liste.

Bubule le 26/03/2003 :
  - ajout d'un bouton << Début et Fin >>,
  - ajout d'une liste pour allez directement à une page,
  - gestion des smileys,
  - install automatique.

Bubule le 14/11/2003 : (version 1.2)
  - possibilité de ne pas supprimer les messages,
  - affecte une valeur par défaut à la variable $x,
  - ajout d'icône,
  - correction d'une ENORME erreur qui autorisait l'envoie d'e-mail alors
    que la fonction est désactivé.
  - Possibilité d'effectuer une recherche avec gestion multi page,
  - possibilité de configurer la largeur du site,
  - suppression de .... dans les sujets,
  - correction d'un bug qui fait qu'il ne détectait pas toujours que la fonction
    mail() était désactivé,
  - les saut de ligne physique de le texte entré sont pris en compte et sont
    convertis en saut HTML.

Bubule le 17/11/2003 :
  - Possibilité de classer la liste des message,
  - Optimize juste les tables ou il y a eu suppressions,
  - Documenter CSS.
*/


// TODO :
// - Simplifier l'installation et la configuration
// - Possibilité d'avoir des utilisateurs, modérateurs, administrateurs,
// - Pouvoir configurer la lecture et l'écriture -> privé, publique,
// - Gestion des smileys et BBCode par base de données
// - possibilité de limité la taille de l'enregistrement,
// - possibilité de limité la taille du texte,


// Inclu le fichier de configuration
include("config.php") ;

// Gestion des cookies
if(isset($HTTP_POST_VARS['auteur']))
{
    setcookie("auteur", $HTTP_POST_VARS['auteur']);
}

if(isset($HTTP_POST_VARS['adresse']))
{
    setcookie("email", $HTTP_POST_VARS['adresse']);
}

include("fonctions.php") ;

// Numéro de la page
if (isset($HTTP_POST_VARS['x']))
{
    $x = gpcAddSlashes($HTTP_POST_VARS['x']) ;
}
else if (isset($HTTP_GET_VARS['x']))
{
    $x = $HTTP_GET_VARS['x'] ;
}
else
{
    $x = 0 ;
}

if (isset($HTTP_POST_VARS["trier"]))
{
    switch($HTTP_POST_VARS["classement"])
    {
        case 0 : $classement = "date" ;
                 break ;
        case 1 : $classement = "auteur" ;
                 break ;
        case 2 : $classement = "sujet" ;
                 break ;
        case 3 : $classement = "date_reelle" ;
                 break ;
        default : $classement = "date_reelle" ;
    }

    switch ($HTTP_POST_VARS["sens"])
    {
        case 0 : $sens = "asc" ;
                 break ;
        case 1 : $sens = "desc" ;
                 break ;
        default : $sens = "desc" ;
    }
}
else if  (isset($HTTP_GET_VARS["classement"]) || isset($HTTP_GET_VARS["sens"]))
{
    // Afin de ne pas tout recoder, on fait un p'tit subterfuge
    $HTTP_POST_VARS["trier"] = 1 ;
    $HTTP_POST_VARS["classement"] = $HTTP_GET_VARS["classement"] ;
    $HTTP_POST_VARS["sens"] = $HTTP_GET_VARS["sens"] ;
}
else
{
    $classement = "date_reelle" ;
    $HTTP_POST_VARS["classement"] = 3 ;
    $sens = " desc" ;
    $HTTP_POST_VARS["sens"] = 1 ;
}


    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
    <html>
      <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title><?php echo $titre_forum ; ?></title>
        <meta name="keywords" content=""><meta name="author" content="Bruno Castagné">
        <meta name="author" content="Bubule">
        <LINK href="<?php echo $fichier_css ; ?>" type=text/css rel="STYLESHEET">
    </head>
    <body>
  <?php

    // Connextion à la base de donnée
    $my_sql = @mysql_connect($host,$user,$pw) or die(mysql_error()) ;
    // Sélectionne la base
    @mysql_select_db("$db") or die(mysql_error()) ;

    // Affiche le forum
    echo "<center><h1>$titre_forum</h1><hr width='$width'>" ;
    echo "<a href='" . $url_site . $SCRIPTNAME .  "?newpost=1&x=" . urlencode($x) . "&sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "'>" ;

    if ($afficher_icone)
    {
        echo "<img src='" . $url_site . "images/new.gif' alt='Nouveau sujet' border='0' align='absmiddle'>" ;
    }

    echo " Nouveau sujet</a> | <a href='" . $url_site . "aide.php?sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "'>" ;

    if ($afficher_icone)
    {
        echo "<img src='" . $url_site . "images/aide.gif' alt='aide' border='0' align='absmiddle'> " ;
    }

    echo "Aide</a> | <a href='" . $url_site . "recherche.php?sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "'>" ;

    if ($afficher_icone)
    {
        echo "<img src='" . $url_site . "images/search.gif' alt='Recherche' border='0' align='absmiddle'>" ;
    }

    echo " Rechercher</a>" ;

    if (!empty($url_de_retour))
    {
        echo " | <a href='$url_de_retour'>" ;

        if ($afficher_icone)
        {
            echo "<img src='" . $url_site . "images/quitter.gif' alt='Quitter' align='absmiddle' border='0'> " ;
        }

        echo "Quitter le forum</a>" ;
    }

    echo "<hr width='$width'>" ;

    echo "<form action='" . $url_site . $SCRIPTNAME . "?sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "' method='post'>" ;

    // Efface les questions et les réponses non lues depuis X jours
    // 1° Releve les id des message
    if ($nb_jour_avant_suppression > 0)
    {
        $res = @mysql_query("SELECT id FROM ". $prefixe_de_table . "question WHERE TO_DAYS(NOW()) - TO_DAYS(date_reelle) >= $nb_jour_avant_suppression") ;

        $optimise = false ;

        // 2° Suppression des messages et de leurs reponses
        while ($row = @mysql_fetch_array($res))
        {
            $nb_question = @mysql_query("DELETE FROM " . $prefixe_de_table . "question WHERE id='" . $row["id"] . "'") ;

            $nb_reponse = @mysql_query("DELETE FROM " . $prefixe_de_table . "reponse WHERE id='" . $row["id"] . "'") ;

            if ($nb_question || $nb_reponse)
            {
                $optimise = true ;
            }
        }

        if ($optimise)
        {
            // Optimisation de la base de données
            @mysql_query("OPTIMIZE TABLE " . $prefixe_de_table . "reponse") ;
            @mysql_query("OPTIMIZE TABLE " . $prefixe_de_table . "question") ;
        }
    }

    /*
     * Enregistre la réponse
     */
    if (isset($HTTP_POST_VARS["quest_reponse"]))
    {
        $id = gpcAddSlashes($HTTP_POST_VARS['id']) ;
        $auteur = gpcAddSlashes($HTTP_POST_VARS['auteur']) ;
        $adresse = gpcAddSlashes($HTTP_POST_VARS['adresse']) ;
        $texte = gpcAddSlashes($HTTP_POST_VARS['texte']) ;

        if (!empty($texte)&& !empty($auteur))
        {
            // Régarde s'il y a un retour par e-mail
            $res = @mysql_query("SELECT envoi, mail FROM " . $prefixe_de_table . "question WHERE id='$id'") ;
            $row = @mysql_fetch_array($res) ;

            if (($row["envoi"] == "oui") && !empty($row["mail"]) && $can_send_email)
            {
                if (ereg("^@.+\$", $row["mail"]))
                {
                    $email = substr($row["mail"], 1) ;

                    // Masque l'adresse e-mail dans le message
                    $adresse = "" ;
                }
                else
                {
                    $email = $row["mail"] ;
                }

                $mailSubject = "Reponse du forum." ;
                $mailBody = "$auteur vous répond :\n" ;
                $mailBody .= "$texte\n" ;
                $mailBody .= $adresse ;
                $ok = mail($email, $mailSubject, $mailBody) ;
            }

            $date = date("d-m-Y") ;

            // Rend invisible l'adresse e-mail si nécessaire
            if (isset($HTTP_POST_VARS["invisible"]))
            {
                $adresse = "@" . $adresse ;
            }

            @mysql_query("insert into " . $prefixe_de_table . "reponse (date_reelle, id, date, auteur, mail, texte) values (now(), '$id', '$date', '$auteur', '$adresse', '$texte')") ;
        }
        else
        {
            echo "Il vous faut entrer un nom d'auteur et un texte !" ;
            // Créer la variable pour que le forulaire réapparaisse.
            $HTTP_GET_VARS['repondre'] = 1 ;
            $HTTP_GET_VARS["id"] = $id ;
        }
    }

    /*
     * reponde à une question....
     */
    if (isset($HTTP_GET_VARS["repondre"]))
    {
        // Met à jour sa date réelle
        @mysql_query("UPDATE " . $prefixe_de_table . "question SET date_reelle=now() WHERE id='" . $HTTP_GET_VARS["id"] . "' ") ;

        $res = @mysql_query("select * from " . $prefixe_de_table . "question where id like '" . $HTTP_GET_VARS["id"] . "' ") ;

        echo "<table width='$width' border='0' align='center'>" ;

        while ($row = @mysql_fetch_array($res))
        {
            $id = $row["id"] ;

            echo "<tr class='ligneSujetMessage'><td colspan='2'><b>" . htmlentities($row["sujet"]) .
                 "</b> par <i>" . htmlentities($row["auteur"]) . "</i>";

            if (!empty($row["mail"]) && !ereg("^@.+\$", $row["mail"]))
            {
                echo " (<a href='mailto:" . htmlentities($row["mail"]) . "'>" .
                     htmlentities($row["mail"]) . "</a>)" ;
            }

            echo " </td></tr><tr><td colspan='2'>" ;

            echo BBCode(smiley(lien_cliquable(censurer($row["texte"])))) ;

            echo "</td></tr>" ;
        }

        $res2 = @mysql_query("select * from " . $prefixe_de_table . "reponse where id like '" . $HTTP_GET_VARS["id"] . "'");

        $color1 = "ligneRéponseDevellopeImpaire" ;
        $color2 = "ligneRéponseDevellopePaire" ;
        $coul = $color1;

        while ($row1 = @mysql_fetch_array($res2))
        {
            echo "<tr class='$coul'><td colspan='2'><b>$row1[auteur]</b>" ;

            if (!empty($row1["mail"]) && !ereg("^@.+\$", $row1["mail"]))
            {
                echo " (<a href='mailto:$row1[mail]'>$row1[mail]</a>)" ;
            }

            echo " a r&eacute;pondu le $row1[date] : <br>" ;

            echo BBCode(smiley(lien_cliquable(censurer($row1["texte"])))) ;

            echo "</td></tr>" ;

            if ($coul == $color1)
            {
                $coul = $color2 ;
            }
            else
            {
                $coul = $color1 ;
            }
        }

        echo "<input type='hidden' value='$id' name='id'>" ;
        echo "<tr><td colspan='2' align='center'><b>Répondre</td><tr>" ;
        echo "<td align='right'><b>Auteur : </b></td><td><input type='text'" .
             " name='auteur' value='" . htmlentities($HTTP_COOKIE_VARS["auteur"]).
              "'></td></tr><tr>" ;
        echo "<td align='right'><b>Adresse : </b></td><td><input type='text'" .
             " name='adresse' value='" . htmlentities($HTTP_COOKIE_VARS["email"]).
             "'></td></tr><tr>" ;
        echo "<td colspan='2' align='center'><b>D&eacute;velopper votre r&eacute;ponse ci-dessous :</b></td></tr><tr align='center'>" ;
        echo "<td colspan='2'><textarea cols='60' rows='17' name='texte' wrap='PHYSICAL'>" .
             htmlentities($HTTP_POST_VARS["texte"]) . "</textarea></td></tr></table><br>" ;

        if ($can_send_email == true)
        {
            echo "<input type='checkbox' name='envoi_mel'> Je souhaite recevoir un e-mail pour chaque r&eacute;ponses<br>" ;
        }

        echo "<input name='invisible' type='checkbox'> Ne pas rendre mon adresse e-mail publique.<br>" ;
        echo "<br><input type='submit' value='Poster le sujet' name='quest_reponse'>&nbsp;" ;
        echo "<input type='hidden' name='x' value='" . htmlentities($x) . "'><input type='submit' value='Retour au forum'>" ;
        echo "<hr width='50%'>" ;
    }

    /*
     * Enregistre le nouveau sujet dans la base de données
     */
    if (isset($HTTP_POST_VARS['poster']))
    {
        $envoi_mel = gpcAddSlashes($HTTP_POST_VARS['envoi_mel']) ;
        $sujet = gpcAddSlashes($HTTP_POST_VARS['sujet']) ;
        $auteur = gpcAddSlashes($HTTP_POST_VARS['auteur']) ;
        $adresse = gpcAddSlashes($HTTP_POST_VARS['adresse']) ;
        $texte = gpcAddSlashes($HTTP_POST_VARS['texte']) ;

        if (!empty($texte) && !empty($sujet) && !empty($auteur))
        {
            if ($HTTP_POST_VARS['envoi_mel'])
            {
                $envoi = "oui" ;
            }
            else
            {
                $envoi = "non" ;
            }

            $id = (uniqid('')) ;
            $id = substr($id, 4, 8) ;
            $date = date("d-m-Y") ;

            // Rend invisible l'adresse e-mail si nécessaire
            if (isset($HTTP_POST_VARS["invisible"]))
            {
                $adresse = "@" . $adresse ;
            }

            @mysql_query("insert into " . $prefixe_de_table . "question (date_reelle, id, date, sujet, auteur, mail, texte, envoi) values (now(),'$id','$date','$sujet','$auteur','$adresse','$texte','$envoi')") ;
        }
        else
        {
            echo "Il vous faut entrer un titre, un nom d'auteur et un sujet !" ;
            // Créer la variable pour que le forulaire réapparaisse.
            $HTTP_POST_VARS['newpost'] = 1 ;
        }
    }

    /*
     * Affiche le formulaire pour ajouter un message
     */
    if (isset($HTTP_GET_VARS['newpost']))
    {
        echo "<table border='0'><tr>" ;
        echo "<td align='right'><b>Sujet : </b></td><td><input type='text'" .
             " name='sujet' value='" . htmlentities($HTTP_POST_VARS["sujet"]) .
             "'></td></tr><tr>" ;
        echo "<td align='right'><b>Auteur : </b></td><td><input type='text'" .
             " name='auteur' value='" . htmlentities($HTTP_COOKIE_VARS["auteur"]).
             "'></td></tr><tr>" ;
        echo "<td align='right'><b>Adresse : </b></td><td><input type='text'" .
             " name='adresse' value='" . htmlentities($HTTP_COOKIE_VARS["email"]).
             "'></td></tr><tr>" ;
        echo "<td colspan='2'><b>D&eacute;velopper votre question ci-dessous : </b></td></tr><tr>" ;
        echo "<td colspan='2'><textarea cols='50' rows='15' name='texte' wrap='PHYSICAL'>" .
             htmlentities($HTTP_POST_VARS["texte"]) . "</textarea></td></tr></table><br>" ;

        if ($can_send_email == true)
        {
            echo "<input type='checkbox' name='envoi_mel'> Je souhaite recevoir un e-mail pour chaque r&eacute;ponses<br>" ;
        }

        echo "<input name='invisible' type='checkbox'> Ne pas rendre mon adresse e-mail publique.<br><br>" ;
        echo "<input type='hidden' name='x' value='" . htmlentities($x) . "'><input type='submit' value='Poster' name='poster'>&nbsp;" ;
        echo "<input type='submit' value='Annuler'>" ;
        echo "<hr width='50%'>" ;
    }

    /*
     * Affiche la liste des messages
     */
     if (!isset($HTTP_GET_VARS["repondre"]) && !isset($HTTP_GET_VARS['newpost']))
     {
        $res1 = @mysql_query("select * from " . $prefixe_de_table . "question") ;

        $tot = @mysql_num_rows($res1) ;
        @mysql_free_result($res1) ;

        $page_tot = ceil($tot / $nb_post_par_page);

        if ($tot <= 1)
        {
            $question = "question" ;
        }
        else
        {
            $question = "questions" ;
        }

        if ($page_tot <= 1)
        {
            $page_ecran = "page" ;
        }
        else
        {
            $page_ecran = "pages" ;
        }

        if ($page_tot == 0)
        {
            $page_tot = 1 ;
        }
        else
        {
            $page_tot = $page_tot ;
        }

        echo "</center><table cellspacing='2' cellpadding='1' border='0' width='100%'><tr><td><b>Il y a $tot $question sur $page_tot $page_ecran.</b>" ;
        ?>
        </td><td align="right">
        Classer par
        <select name="classement">
          <option value="0" <?php echo ($HTTP_POST_VARS["classement"] == 0) ? "selected" : "" ; ?>>Date de post </option>
          <option value="1" <?php echo ($HTTP_POST_VARS["classement"] == 1) ? "selected" : "" ; ?>>Auteur</option>
          <option value="2" <?php echo ($HTTP_POST_VARS["classement"] == 2) ? "selected" : "" ; ?>>Titre</option>
          <option value="3" <?php echo ($HTTP_POST_VARS["classement"] == 3) ? "selected" : "" ; ?>>Date de derni&egrave;re lecture/r&eacute;ponse</option>
        </select>
        par ordre
        <select name="sens">
          <option value="0" <?php echo ($HTTP_POST_VARS["sens"] == 0) ? "selected" : "" ; ?>>Croissant</option>
          <option value="1" <?php echo ($HTTP_POST_VARS["sens"] == 1) ? "selected" : "" ; ?>>D&eacute;croissant</option>
        </select>
        <input type="submit" value="Trier" name="trier">
        </td></tr>
        <tr><td colspan="2">&nbsp;</td></tr></table>
        <?php
        // interoge la table question ....
        echo "<table cellspacing='2' cellpadding='1' border='0' width='$width' align='center'>" ;

        $color1 = "ligneTitreMessageImpaire" ;
        $color2 = "ligneTitreMessagePaire" ;
        $coul = $color1;

        // Vérifie qu'il s'agit bien d'un chiffre
        if (!is_numeric($x))
        {
            $x = 0 ;
        }

        // Si on a demandé la page précédante
        if (isset($HTTP_POST_VARS["moins"]) && ($x > 0))
        {
            $x -= $nb_post_par_page ;
        }

        // Si on a demandé la page précédante
        if (isset($HTTP_POST_VARS["plus"]) && (($x + $nb_post_par_page) <= $tot))
        {
            $x += $nb_post_par_page ;
        }

        // 1erer page
        if (isset($HTTP_POST_VARS["debut"]))
        {
            $x = 0 ;
        }

        // Derniere page
        if (isset($HTTP_POST_VARS["fin"]))
        {
            $x = $tot - $nb_post_par_page ;
        }

        // Page XXX
        if (isset($HTTP_POST_VARS["direct_valid"]) && is_numeric($HTTP_POST_VARS["direct"]))
        {
            $x = $HTTP_POST_VARS["direct"] ;
        }

        $res1 = @mysql_query("select * from " . $prefixe_de_table . "question order by $classement $sens limit $x, $nb_post_par_page");

        while ($row = @mysql_fetch_array($res1))
        {
            $id = $row["id"] ;

            // interroge la table réponse sur le nombre de réponses...
            $res2 = @mysql_query("select * from " . $prefixe_de_table . "reponse where id='$id'") ;
            $rep = @mysql_num_rows($res2) ;

            if ($afficher_icone == true)
            {
                if ($rep >= $nb_rep_pr_msg_hot)
                {
                    $image = "message-hot.gif" ;
                }
                else if ($rep == 0)
                {
                    $image = "message-nouveau.gif" ;
                }
                else
                {
                    $image = "message.gif" ;
                }

                $image = "<img alt='' src='" . $url_site . "images/" . $image . "'>&nbsp;" ;
            }
            else
            {
                $image = "" ;
            }

            echo "<tr class='$coul'><td>$image" . $row['date'] . " :&nbsp;</td>" ;

            echo "<td><a href='". $url_site . $SCRIPTNAME . "?repondre=1&id=". urlencode($row["id"]) . "&x=$x&sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "'> " . htmlentities($row["sujet"]) . "</a></td>" ;
            echo "<td nowrap align='right'>auteur : " . htmlentities($row["auteur"]) . "</td>" ;

            if ($coul == $color1)
            {
                $coul = $color2 ;
            }
            else
            {
                $coul = $color1 ;
            }

            if ($rep <= 1)
            {
                $reponse = "r&eacute;ponse" ;
            }
            else
            {
                $reponse = "r&eacute;ponses" ;
            }

            echo "<td align='right'>(<b>$rep $reponse</b>)</td></tr>" ;

            /*
             * Affiche les réponses
             */
            if (($rep > 0) && ($afficher_reponse_dans_liste == true))
            {

                $col1 = "ligneReponseMessageImpaire" ;
                $col2 = "ligneReponseMessagePaire" ;
                $coul1 = $col1 ;

                while ($row = @mysql_fetch_array($res2))
                {
                    echo "<tr><td>&nbsp;</td><td colspan='3' class='$coul1'><b>"
                         . htmlentities($row["auteur"]) . "</b>" ;

                    if (!empty($row["mail"]) && !ereg("^@.+\$", $row["mail"]))
                    {
                        echo " (<a href='mailto:" . htmlentities($row["mail"]) . "'>" . htmlentities($row["mail"]) . "</a>)"  ;
                    }

                    echo " a r&eacute;pondu le $row[date]</td></tr>" ;

                    if ($coul1 == $col1)
                    {
                        $coul1 = $col2 ;
                    }
                    else
                    {
                        $coul1 = $col1 ;
                    }
                }
            }
        }

        echo "</table><br><table border='0' cellspacing='0' cellpadding='2' align='center'><tr><td valign='top'>" ;

        if ($x > 0)
        {
            echo "<input type='Submit' value='&lt;&lt; D&eacute;but' name='debut'>&nbsp;" ;
            echo "<input type='Submit' value='&lt; Pr&eacute;c&eacute;dant' name='moins'>" ;
        }
        else
        {
            echo "&nbsp;" ;
        }

        echo "</td><td valign='top' align='center' nowrap>Page " .
             (ceil($x / $nb_post_par_page) + 1) . "/$page_tot" .
             "</td><td valign='top' align='right'>" ;

        if ((ceil($x / $nb_post_par_page) + 1) < $page_tot)
        {
            echo "<input type='submit' value=' Suivant &gt;' name='plus'>&nbsp;" ;
            echo "<input type='Submit' value='Fin &gt;&gt;' name='fin'>" ;
        }
        else
        {
            echo "&nbsp;" ;
        }

        echo "<input type='hidden' name='x' value='$x'><input type='hidden' name='total' value='$tot'>" ;
        echo "</td></tr><tr><td colspan='3' align='center' nowrap>" ;
        echo "Allez directement &agrave; la page <select name='direct'>" ;

        for ($i = 0; $i < $page_tot; $i++)
        {
            echo "<option value='" . $i * $nb_post_par_page . "'>". ($i + 1) . "</option>" ;
        }

        echo "</select>&nbsp;<input type='Submit' name='direct_valid' value='Go'>" ;

        echo "</td></tr><tr><td align='center' colspan='3'><br>Power by [ <a href='http://php4php.free.fr/nfaq/'>NFAQ</a> ]</td></tr></table></form>" ;
    }

echo "</body></html>" ;
?>