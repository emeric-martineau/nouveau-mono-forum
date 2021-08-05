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

        if (!isset($HTTP_POST_VARS["rechercher"]))
        {
        ?>
            <table border="0" align="center">
              <tr class="rechercher">
                <td>Auteur</td><td><input type="text" name="auteur" class="ligneAjout"></td>
              </tr>
              <tr class="rechercher">
                <td>Titre/Texte</td><td><input type="type" name="titre_texte" class="ligneAjout"></td>
              </tr>
              <tr>
                <td colspan="2" align="center"><br><input type="submit" value="Rechercher" name="rechercher" class="ligneAjout"></td>
              </tr>
            </table>
        <?php
        }
        else
        {
            ?>
            <table width="100%" border="0" cellspacing="1" cellpadding="4">
              <tr class="titre">
                <td class="titreTitreSujet" width="100%">Titre du sujet</td>
                <td class="titreCreateurSujet" nowrap>Créateur du sujet</td>
              </tr>

            <?php
            // "Magic Quotes". NE PAS MODIFIER !!!
            set_magic_quotes_runtime(0);

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
                echo "<tr class='message'><td colspan='2'>Auncun r&eacute;sultats trouv&eacute;s !</td></tr>" ;
            }
            else
            {
                $tot = count($resultatTrouvees) ;
                $page_tot = ceil($tot / $nb_post_par_page);

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

                if (($y + $nb_post_par_page) > $tot)
                {
                    $borne = $tot - $y ;
                }
                else
                {
                    $borne = $nb_post_par_page ;
                }

                for ($i = 0; $i < $borne; $i++)
                {
                    echo "<tr class='message'><td class='messageTitreSujet'><a href='" . $url_site . $SCRIPTNAME . "?voirsujet=1&id=". urlencode($resultatTrouvees[$y + $i]["id"]) . "&x=$x&sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "&titre=" . urlencode($resultatTrouvees[$y + $i]["sujet"]) . "'> " . htmlentities($resultatTrouvees[$y + $i]["sujet"]) . "</a></td>" ;
                    echo "<td nowrap class='messageCreateurSujet'>" . htmlentities($resultatTrouvees[$y + $i]["auteur"]) . " le " . format_date_court($resultatTrouvees[$y + $i]['date']) . "</td></tr>" ;

                }
            }

            echo "</table>" ;
            echo "<input type='hidden' name='rechercher' value='1'><input type='hidden' name='auteur' value='" .
                 gpcAddSlashes(htmlentities($HTTP_POST_VARS["auteur"])) . "'><input type='hidden' name='titre_texte' value='" .
                 gpcAddSlashes(htmlentities($HTTP_POST_VARS["titre_texte"])) . "'>" ;

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

            echo "</select>&nbsp;<input type='Submit' name='direct_validY' value='Go' class='piedDeTableauListe'>" ;

            echo "</td></tr></table>" ;

        }

?>