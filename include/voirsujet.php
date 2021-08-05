<?php
/*******************************************************************************
 * Copyright (C) 2001-2004 MARTINEAU Emeric (php4php@free.fr)
 *
 * NouveauMiniForum (anciennement NFAQ).
 *
 * Version 1.4
 *
 * Voici un forum ultra rapide, simple a mettre en place. Les urls sont
 * cliquables, la personne qui pose une question peut recevoir directement
 * un e-mail pour chaque reponse. Les messages sont
 * nettoyer tous les X jours. Vous avez besoin d'une base Mysql. On peut déposer
 * du code php sans risque.
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
Vérifie que le fichier n'est pas affiché seul */
if ( !defined('IN_NMF') )
{
    die("Restricted access");
}
            $id = $HTTP_GET_VARS["id"] ;

             // Affichage des réponses
            $res2 = @mysql_query("SELECT * FROM " . $prefixe_de_table . "reponse WHERE id='$id'");

            $tot = @mysql_num_rows($res2) ;
            @mysql_free_result($res2) ;

            $page_tot = ceil($tot / $nb_post_par_page);

            if ($page_tot == 0)
            {
                $page_tot = 1 ;
            }

            // Vérifie qu'il s'agit bien d'un chiffre
            if (!is_integer($y))
            {
                $y = 0 ;
            }

            // Si on a demandé la page précédante
            if (isset($HTTP_POST_VARS["moinsY"]) && ($y > 0))
            {
                $y -= $nb_post_par_page ;
            }

            // Si on a demandé la page précédante
            if (isset($HTTP_POST_VARS["plusY"]) && (($y + $nb_post_par_page) <= $tot))
            {
                $y += $nb_post_par_page ;
            }

            // 1erer page
            if (isset($HTTP_POST_VARS["debutY"]))
            {
                $y = 0 ;
            }

            // Derniere page
            if (isset($HTTP_POST_VARS["finY"]))
            {
                $y = $tot - $nb_post_par_page ;
            }

            // Page XXX
            if (isset($HTTP_POST_VARS["direct_validY"]) && is_integer($HTTP_POST_VARS["directY"]))
            {
                $y = $HTTP_POST_VARS["directY"] ;
            }


            if (isset($HTTP_GET_VARS["id"]))
            {
                $id = $HTTP_GET_VARS["id"] ;
            }
            else if (isset($HTTP_POST_VARS["id"]))
            {
                $id = $HTTP_POST_VARS["id"] ;
            }
            else
            {
                die("** ERREUR ** L'id provient d'une source impr&eacute;vue !!!") ;
            }

            // Met à jour sa date réelle
            @mysql_query("UPDATE " . $prefixe_de_table . "question SET date_reelle=now() WHERE id='$id'") ;

            $res = @mysql_query("SELECT * FROM " . $prefixe_de_table . "question WHERE id='$id' ") ;
            $row = @mysql_fetch_array($res) ;

            if ($y == 0)
            {
                afficher_message($row["auteur"], $row["sujet"], $row["mail"], $row["date"], $row["texte"]) ;
            }

            // liste les réponses
            $res2 = @mysql_query("SELECT * FROM " . $prefixe_de_table . "reponse WHERE id='$id' LIMIT $y, $nb_post_par_page");

            while ($row1 = @mysql_fetch_array($res2))
            {
                afficher_message($row1["auteur"], "", $row1["mail"], $row1["date"], $row1["texte"]) ;
            }


            echo "<table border='0' cellspacing='0' cellpadding='2' width='100%' class='piedDeTableauListe'><tr><td valign='top' align='left' nowrap>" ;

            if ($y > 0)
            {
                echo "<input type='Submit' value='&lt;&lt; D&eacute;but' name='debutY' class='piedDeTableauListe'>&nbsp;" ;
                echo "<input type='Submit' value='&lt; Pr&eacute;c&eacute;dant' name='moinsY' class='piedDeTableauListe'>" ;
            }
            else
            {
                echo "&nbsp;" ;
            }

            echo "</td><td valign='center' align='left' nowrap>Page " .
                 (ceil($y / $nb_post_par_page) + 1) . "/$page_tot" .
                 "</td><td valign='top' align='left' nowrap>" ;

            if ((ceil($y / $nb_post_par_page) + 1) < $page_tot)
            {
                echo "<input type='submit' value=' Suivant &gt;' name='plusY' class='piedDeTableauListe'>&nbsp;" ;
                echo "<input type='Submit' value='Fin &gt;&gt;' name='finY' class='piedDeTableauListe'>" ;
            }
            else
            {
                echo "&nbsp;" ;
            }

            echo "<input type='hidden' name='y' value='$y'>" ;
            echo "<input type='hidden' name='voirsujet' value='1'><input type='hidden' name='id' value='$id'>" ;
            echo "</td><td align='right' valign='center' width='100%' nowrap>" ;
            echo "Allez directement &agrave; la page <select name='directY' class='piedDeTableauListe'>" ;

            for ($i = 0; $i < $page_tot; $i++)
            {
                echo "<option value='" . $i * $nb_post_par_page . "'" ;

                if (($y / $nb_post_par_page) == $i)
                {
                    echo "selected" ;
                }

                echo " >". ($i + 1) . "</option>" ;
            }

            echo "</select>&nbsp;<input type='Submit' name='direct_valid' value='Go' class='piedDeTableauListe'>" ;
            echo "<input type='hidden' name='id_repondre' value='" . htmlentities($HTTP_GET_VARS["id"]) . "'><input type='hidden' name='titre_repondre' value='" . htmlentities(stripslashes($HTTP_GET_VARS["titre"])) . "'>" ;
            echo "</td></tr></table>" ;
?>