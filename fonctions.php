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
 ******************************************************************************/
// Nom du fichier
//$SCRIPTNAME = basename(getenv("SCRIPT_NAME")) ;

// Définit si on peut envoyer des e-mail
if (strpos(get_cfg_var("disable_functions"), "mail"))
{
    $afficher_reponse_dans_liste = false ;
}
else
{
    $afficher_reponse_dans_liste = true ;
}

////////////////////////////////////////////////////////////////////////////////
// Créer un lien
function lien_cliquable($chaine)
{
    // rend cliquable un lien dans le sujet...
    $text1 = stripslashes(htmlentities($chaine));
    $chaine = eregi_replace("([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])",
    "<a href='\\1://\\2\\3' target='_blank'>\\1://\\2\\3</a>", $text1);

    return $chaine ;
}

////////////////////////////////////////////////////////////////////////////////
// Ajout d'anti-slashes selon "Magic Quotes GPC"
function gpcAddSlashes($chaine)
{
    return(get_magic_quotes_gpc() == 1 ? $chaine : AddSlashes($chaine));
}

////////////////////////////////////////////////////////////////////////////////
// Ajout de smilies
function smiley($chaine)
{
    global $smiley, $url_site ;

    $nb_smiley = count($smiley) ;

    for ($i = 0; $i < $nb_smiley; $i++)
    {
        $chaine = str_replace($smiley[$i][0], "<img src='" . $url_site . "images/smiley/" .
                  $smiley[$i][1] . "' border='0' alt='" .$smiley[$i][2] .
                  "'>", $chaine) ;
    }

    return $chaine ;
}

////////////////////////////////////////////////////////////////////////////////
// Gère le BBCode
function BBCode($chaine)
{
    global $bbcode ;

    $nb_bbcode = count($bbcode) ;

    for ($i = 0; $i < $nb_bbcode; $i++)
    {
        $chaine = str_replace($bbcode[$i][0], $bbcode[$i][1], $chaine) ;
    }

    return $chaine ;
}

////////////////////////////////////////////////////////////////////////////////
// Gère la censure
function censurer($chaine)
{
    global $censure, $texte_a_inserer ;

    $nb_mot = count($censure) ;

    for ($i = 0; $i < $nb_mot; $i++)
    {
        $chaine = eregi_replace($censure[$i], $texte_a_inserer, $chaine) ;
    }

    return $chaine ;
}
?>
