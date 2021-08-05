<?php
/*******************************************************************************
 * Copyright (C) 2001-2004 MARTINEAU Emeric (php4php@free.fr)
 *
 * Nouvelle foire aux question.
 *
 * Version 1.0
 *
 * Voici un forum qui ne tient que sur un seul fichier. Ultra rapide, simple a 
 * mettre en place. Les urls sont cliquables, la personne qui pose une question
 * peut recevoir directement un mel pour chaque reponse. Les message sont
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

Bubule le 21/08/2003 :
  - formatage du code source,
  - suppression de \" au profit de '
  - mise en fonction du cliquage de lien,
  - ajout de @ devant les fonctions de BDD.
  - suppression de l'envoie de message directe pour éviter les spam,
  - vérification des données venant du formulaire vers la base de données,
  - adressage des varaibles des formulaires par HTTP_*_VARS,
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
  - install automatique
*/


// TODO :
// - pouvoir filtrer par auteur, date...
// - Pouvoir effectuer une recherche


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

// Calcule de la page
if (isset($HTTP_POST_VARS['x']))
{
    $x = gpcAddSlashes($HTTP_POST_VARS['x']) ;
}
else
{
    $x = $HTTP_GET_VARS['x'] ;
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
    echo "<center><h1>$titre_forum</h1><hr width='76%'>" ;
    echo "<a href='" . $url_site . $SCRIPTNAME .  "?newpost=1&x=" .
         urlencode($x) . "'><img src='" . $url_site . "images/new.gif'" .
         "alt='Nouveau sujet' border='0' align='absmiddle'> Nouveau sujet</a>" .
         " | <a href='" . $url_site . "aide.php'><img src='" . $url_site .
         "images/aide.gif' alt='aide' border='0' align='absmiddle'> Aide</a>" ;

    if (!empty($url_de_retour))
    {
        echo " | <a href='$url_de_retour'><img src='" . $url_site . "images/quitter.gif'".
             " alt='Quitter' align='absmiddle' border='0'> Quitter le forum</a>" ;
    }

    echo "<hr width='76%'>" ;

    echo "<form action='" . $url_site . $SCRIPTNAME . "' method='post'>" ;

    // Efface les questions et les réponses non lues depuis X jours
    // 1° Releve les id des message
    $my_question = "SELECT id FROM ". $prefixe_de_table . "question WHERE TO_DAYS(NOW()) - TO_DAYS(date_reelle) >= $nb_jour_avant_suppression" ;
    $res = @mysql_query($my_question) ;

    // 2° Suppression des messages et de leur reponse
    while ($row = @mysql_fetch_array($res))
    {
        $my_question = "DELETE FROM " . $prefixe_de_table . "question WHERE id='" . $row["id"] . "'" ;
        @mysql_query($my_question) ;

        $my_question = "DELETE FROM " . $prefixe_de_table . "reponse WHERE id='" . $row["id"] . "'" ;
        @mysql_query($my_question) ;
    }

    // Optimisation de la base de données
    @mysql_query("OPTIMIZE TABLE " . $prefixe_de_table . "reponse") ;
    @mysql_query("OPTIMIZE TABLE " . $prefixe_de_table . "question") ;

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
            $my = "SELECT envoi, mail FROM " . $prefixe_de_table . "question WHERE id='$id'" ;
            $res = @mysql_query($my) ;
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

            $my = "insert into " . $prefixe_de_table . "reponse (date_reelle, id, date, auteur, mail, texte) values (now(), '$id', '$date', '$auteur', '$adresse', '$texte')" ;
            @mysql_query($my) ;
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
        $my = "UPDATE " . $prefixe_de_table . "question SET date_reelle=now() WHERE id='" . $HTTP_GET_VARS["id"] . "' ";
        @mysql_query($my) ;

        $my = "select * from " . $prefixe_de_table . "question where id like '" . $HTTP_GET_VARS["id"] . "' " ;
        $res = @mysql_query($my) ;

        echo "<table width='76%' border='0' align='center'>" ;

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

        $my_2 = "select * from " . $prefixe_de_table . "reponse where id like '" . $HTTP_GET_VARS["id"] . "'" ;
        $res2 = @mysql_query($my_2);

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
        echo "<td colspan='2'><textarea cols='60' rows='17' name='texte'>" .
             htmlentities($HTTP_POST_VARS["texte"]) . "</textarea></td></tr></table><br>" ;

        if ($can_send_email)
        {
            echo "<input name='invisible' type='checkbox'> Ne pas rendre mon adresse e-mail publique.<br>" ;
        }

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

            $my = "insert into " . $prefixe_de_table . "question (date_reelle, id, date, sujet, auteur, mail, texte, envoi) values (now(),'$id','$date','$sujet','$auteur','$adresse','$texte','$envoi')";
            @mysql_query($my) ;
        }
        else
        {
            echo "Il vous faut entrer un titre, un nom d'auteur et un sujet !" ;
            // Créer la variable pour que le forulaire réapparaisse.
            $HTTP_POST_VARS['newpost'] = 1 ;
        }
    }

    /*
     * Affiche le formulaire
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
        echo "<td colspan='2'><textarea cols='50' rows='15' name='texte'>" .
             htmlentities($HTTP_POST_VARS["texte"]) . "</textarea></td></tr></table><br>" ;

        if ($can_send_email)
        {
            echo "<input type='checkbox' name='envoi_mel'> Je souhaite recevoir un e-mail pour chaque r&eacute;ponses<br>" ;
            echo "<input name='invisible' type='checkbox'> Ne pas rendre mon adresse e-mail publique.<br><br>" ;
        }

        echo "<input type='hidden' name='x' value='" . htmlentities($x) . "'><input type='submit' value='Poster' name='poster'>&nbsp;" ;
        echo "<input type='submit' value='Annuler'>" ;
        echo "<hr width='50%'>" ;
    }

    /*
     * Affiche la liste des messages
     */
     if (!isset($HTTP_GET_VARS["repondre"]) && !isset($HTTP_GET_VARS['newpost']))
     {
        $my_num = "select * from " . $prefixe_de_table . "question order by date desc" ;
        $res1 = @mysql_query($my_num) ;

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

        echo "</center><b>Il y a $tot $question sur $page_tot $page_ecran.</b><br><br><center>" ;

        // interoge la table question ....
        echo "<table cellspacing='2' cellpadding='1' border='0' width='76%'>" ;

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


        $my_1 = "select * from " . $prefixe_de_table . "question order by date_reelle desc limit $x, $nb_post_par_page" ;
        $res1 = @mysql_query($my_1);

        while ($row = @mysql_fetch_array($res1))
        {
            $id = $row["id"] ;

            //interroge la table réponse sur le nombre de réponses...
            echo "<tr class='$coul'><td>$row[date] :&nbsp;</td>" ;
            echo "<td><a href='". $url_site . $SCRIPTNAME . "?repondre=1&id=". urlencode($row["id"]) . "&x=$x'> " . htmlentities($row["sujet"]) . "....</a></td>" ;
            echo "<td nowrap align='right'>auteur : " . htmlentities($row["auteur"]) . "</td>" ;

            $my_2 = "select * from " . $prefixe_de_table . "reponse where id='$id'" ;
            $res2 = @mysql_query($my_2) ;
            $rep = @mysql_num_rows($res2) ;

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

        echo "</table><br><br>" ;
        echo "<table border='0' cellspacing='0' cellpadding='2'><tr><td valign='top'>" ;

        if ($x > 0)
        {
            echo "<input type='Submit' value='&lt;&lt; D&eacute;but' name='debut'>&nbsp;" ;
            echo "<input type='Submit' value='&lt; Pr&eacute;c&eacute;dant' name='moins'>" ;
        }
        else
        {
            echo "&nbsp;" ;
        }

        echo "</td><td valign='top' align='center' nowrap>Page " . (ceil($x / $nb_post_par_page) + 1) . "/$page_tot" .
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
        echo "&nbsp;<br>Allez directement &agrave; la page <select name='direct'>" ;

        for ($i = 0; $i < $page_tot; $i++)
        {
            echo "<option value='" . $i * $nb_post_par_page . "'>". ($i + 1) . "</option>" ;
        }

        echo "</select>&nbsp;<input type='Submit' name='direct_valid' value='Go'>" ;

        echo "</td></tr></table></form>" ;

        echo "Power by [ <a href='http://php4php.free.fr/nfaq/'>NFAQ</a> ]</center>" ;
    }
?>
</body></html>
