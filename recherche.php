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
 ******************************************************************************/
// Inclu le fichier de configuration
include("config.php") ;
include("fonctions.php") ;

// Numéro de la page pour la recherche
if (isset($HTTP_POST_VARS['y']))
{
    $y = gpcAddSlashes($HTTP_POST_VARS['y']) ;
}
else if (isset($HTTP_GET_VARS['y']))
{
    $y = $HTTP_GET_VARS['y'] ;
}
else
{
    $y = 0 ;
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
    </head><body>
<?php

echo "<center><h1>$titre_forum</h1><hr width='$width'><b>Recherche</b><hr width='$width'></center><br>" .
     "<table cellspacing='2' cellpadding='1' border='0' width='$width' align='center'>" ;

if (!isset($HTTP_POST_VARS["rechercher"]))
{
?>
    <tr><td align="center"><form method="post" action="<?php echo $url_site . "recherche.php?sens=" . $HTTP_GET_VARS["sens"] . "&classement=" . $HTTP_GET_VARS["classement"] ; ?>">
      Auteur<br><input type="text" name="auteur"><br>
      Titre/Texte<br><input type="type" name="titre_texte"><br><br>
      <input type="submit" value="Rechercher" name="rechercher">
    </form></td></tr></table>
<?php
}
else
{
    echo "<tr><td colspan='3'><a href='" . $url_site . "recherche.php?sens=" . $HTTP_GET_VARS["sens"] . "&classement=" . $HTTP_GET_VARS["classement"] . "'>" ;

    if ($afficher_icone)
    {
        echo "<img src='" . $url_site . "images/search.gif' alt='Recherche' border='0' align='absmiddle'> " ;
    }

    echo "Nouvel recherche</a></td></tr>" ;

    // Connextion à la base de donnée
    $my_sql = @mysql_connect($host,$user,$pw) or die(mysql_error()) ;
    // Sélectionne la base
    @mysql_select_db("$db") or die(mysql_error()) ;

    // ETAPE 1 : recherche dans les messages
    $rep = @mysql_query("SELECT * FROM ". $prefixe_de_table . "question") ;

    // Indique qu'ancune recherche n'a été trouvée
    $found = false ;

    // Contient les résultats touvées
    $resultatTrouvees = array() ;

    while ($row = @mysql_fetch_array($rep))
    {
        $pos1 = @strpos(strtolower($row["sujet"]), strtolower($HTTP_POST_VARS["titre_texte"])) ;
        $pos2 = @strpos(strtolower($row["auteur"]), strtolower($HTTP_POST_VARS["auteur"])) ;
        $pos3 = @strpos(strtolower($row["texte"]), strtolower($HTTP_POST_VARS["titre_texte"])) ;

        // Si rien n'a été trouvé, on cherche dans les réponses du message en cours
        // Si quelque chose est trouvé dans les réponses, $pos2 ou $pos3 sera à true
        // donc le message principal s'affichera.
        if (!(is_integer($pos1) || is_integer($pos2) || is_integer($pos3)))
        {
            // Effectue la recherche sur les réponses
            $rep2 = @mysql_query("SELECT * FROM ". $prefixe_de_table . "reponse WHERE id='" . $row["id"]. "'") ;

            while ($row2 = @mysql_fetch_array($rep2))
            {
                $pos2 = @strpos(strtolower($row2["auteur"]), strtolower($HTTP_POST_VARS["auteur"])) ;
                $pos3 = @strpos(strtolower($row2["texte"]), strtolower($HTTP_POST_VARS["titre_texte"])) ;

                if ((is_integer($pos2)) || (is_integer($pos3)))
                {
                    break ;
                }
            }
        }

        if (is_integer($pos1) || is_integer($pos2) || is_integer($pos3))
        {
            $resultatTrouvees[count($resultatTrouvees)] = array("id" => $row["id"], "date" => $row['date'], "sujet" => $row["sujet"], "auteur" => $row["auteur"]) ;

            $found = true ;
        }
    }

    if ($found == false)
    {
        echo "<tr><td colspan='3'>Auncun r&eacute;sultats trouv&eacute;s !</td></tr>" ;
    }
    else
    {
        $tot = count($resultatTrouvees) ;
        $page_tot = ceil($tot / $nb_post_par_page);

        // Vérifie qu'il s'agit bien d'un chiffre
        if (!is_numeric($y))
        {
            $y = 0 ;
        }

        // Si on a demandé la page précédante
        if (isset($HTTP_POST_VARS["moins"]) && ($y > 0))
        {
            $y -= $nb_post_par_page ;
        }

        // Si on a demandé la page précédante
        if (isset($HTTP_POST_VARS["plus"]) && (($y + $nb_post_par_page) <= $tot))
        {
            $y += $nb_post_par_page ;
        }

        // 1erer page
        if (isset($HTTP_POST_VARS["debut"]))
        {
            $y = 0 ;
        }

        // Derniere page
        if (isset($HTTP_POST_VARS["fin"]))
        {
            $y = $tot - $nb_post_par_page ;
        }

        // Page XXX
        if (isset($HTTP_POST_VARS["direct_valid"]) && is_numeric($HTTP_POST_VARS["direct"]))
        {
            $y = $HTTP_POST_VARS["direct"] ;
        }

        if (($y + $nb_post_par_page) > $tot)
        {
            $borne = $tot - $y ;
        }
        else
        {
            $borne = $nb_post_par_page ;
        }

        $color1 = "ligneTitreMessageImpaire" ;
        $color2 = "ligneTitreMessagePaire" ;
        $coul = $color1;

        for ($i = 0; $i < $borne; $i++)
        {
            echo "<tr class='$coul'><td>" . $resultatTrouvees[$y + $i]['date'] . " :&nbsp;</td>" ;

            echo "<td><a href='". $url_site . $SCRIPTNAME . "?repondre=1&id=". urlencode($resultatTrouvees[$y + $i]["id"]) . "&sens=" . $HTTP_GET_VARS["sens"] . "&classement=" . $HTTP_GET_VARS["classement"] . "'> " . htmlentities($resultatTrouvees[$y + $i]["sujet"]) . "</a></td>" ;
            echo "<td nowrap align='right'>auteur : " . htmlentities($resultatTrouvees[$y + $i]["auteur"]) . "</td></tr>" ;

            if ($coul == $color1)
            {
                $coul = $color2 ;
            }
            else
            {
                $coul = $color1 ;
            }
        }
    }

        echo "</table><br><form action='recherche.php?sens=" . $HTTP_GET_VARS["sens"] . "&classement=" . $HTTP_GET_VARS["classement"] . "' method='post'>" ;
        echo "<input type='hidden' name='rechercher' value='1'><input type='hidden' name='auteur' value='" .
             gpcAddSlashes(htmlentities($HTTP_POST_VARS["auteur"])) . "'><input type='hidden' name='titre_texte' value='" .
             gpcAddSlashes(htmlentities($HTTP_POST_VARS["titre_texte"])) . "'>" ;


        echo "<table border='0' cellspacing='0' cellpadding='2' align='center'><tr><td valign='top'>" ;

        if ($y > 0)
        {
            echo "<input type='Submit' value='&lt;&lt; D&eacute;but' name='debut'>&nbsp;" ;
            echo "<input type='Submit' value='&lt; Pr&eacute;c&eacute;dant' name='moins'>" ;
        }
        else
        {
            echo "&nbsp;" ;
        }

        echo "</td><td valign='top' align='center' nowrap>Page " . (ceil($y / $nb_post_par_page) + 1) . "/$page_tot" .
             "</td><td valign='top' align='right'>" ;

        if ((ceil($y / $nb_post_par_page) + 1) < $page_tot)
        {
            echo "<input type='submit' value=' Suivant &gt;' name='plus'>&nbsp;" ;
            echo "<input type='Submit' value='Fin &gt;&gt;' name='fin'>" ;
        }
        else
        {
            echo "&nbsp;" ;
        }

        echo "<input type='hidden' name='y' value='$y'><input type='hidden' name='total' value='$tot'>" ;
        echo "</td></tr><tr><td colspan='3' align='center' nowrap>" ;
        echo "&nbsp;<br>Allez directement &agrave; la page <select name='direct'>" ;

        for ($i = 0; $i < $page_tot; $i++)
        {
            echo "<option value='" . $i * $nb_post_par_page . "'>". ($i + 1) . "</option>" ;
        }

        echo "</select>&nbsp;<input type='Submit' name='direct_valid' value='Go'>" ;

        echo "</td></tr></table></form>" ;

}

?>
<br>
<a href='<?php echo $url_site . $SCRIPTNAME . "?sens=" . $HTTP_GET_VARS["sens"] . "&classement=" . $HTTP_GET_VARS["classement"]; ?>'>Retour au site &gt;&gt;</a></body></html>

