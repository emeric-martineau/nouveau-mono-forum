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
            ?>
            <table width="100%" border="0" cellspacing="1" cellpadding="4">
              <tr class="titre">
                <td class="titreIcone">&nbsp;</td>
                <td class="titreTitreSujet">Titre du sujet</td>
                <td class="titreCreateurSujet">Créateur du sujet</td>
                <td class="titreReponse">Réponses</td>
                <td class="titreDerniereReponse">Dernière r&eacute;ponse</td>
              </tr>
            <?php
            $res1 = @mysql_query("SELECT * FROM " . $prefixe_de_table . "question ORDER BY $classement $sens LIMIT $x, $nb_post_par_page");

            while ($row = @mysql_fetch_array($res1))
            {
                $id = $row["id"] ;

                // interroge la table réponse sur le nombre de réponses...
                $res2 = @mysql_query("SELECT * FROM " . $prefixe_de_table . "reponse WHERE id='$id'") ;
                $rep = @mysql_num_rows($res2) ;

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
            ?>
              <tr class="message">
                <td class="messageIcone"><?php echo "<img alt='' src='" . $url_site . "themes/" . $theme . "/images/" . $image . "'>" ; ?></td>
                <td class="messageTitreSujet"><?php echo "<a href='" . $url_site . $SCRIPTNAME . "?voirsujet=1&id=". urlencode($row["id"]) . "&x=$x&sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "&titre=" . urlencode($row["sujet"]) . "'> " . htmlentities($row["sujet"]) . "</a>" ; ?></td>
                <td class="messageCreateurSujet">
                <?php
                if (!empty($row["mail"]) && !ereg("^@.+\$", $row["mail"]))
                {
                     echo "<a href='mailto:" . htmlentities($row["mail"]) . "'>" . htmlentities($row["auteur"]) . "</a>" ;
                }
                else
                {
                     echo htmlentities($row["auteur"]) ;
                }

                echo " le " . format_date_court($row["date"]) ;
                ?>
                </td>
                <td class="messageReponse "><?php echo $rep ; ?></td>
                <td class="messageDerniereReponse">
                <?php
                    if ($rep > 0)
                    {
                        $res3 = @mysql_query("SELECT * FROM " . $prefixe_de_table . "reponse WHERE id='$id' ORDER BY date_reelle DESC LIMIT 0,1") ;
                        $row = @mysql_fetch_array($res3) ;

                        echo "le " . format_date($row["date_reelle"]) . " par " ;

                        if (!empty($row["mail"]) && !ereg("^@.+\$", $row["mail"]))
                        {
                             echo "<a href='mailto:" . htmlentities($row["mail"]) . "'>" . htmlentities($row["auteur"]) . "</a>" ;
                        }
                        else
                        {
                             echo htmlentities($row["auteur"]) ;
                        }
                    }
                    else
                    {
                        echo "&nbsp;" ;
                    }
                ?></td>
              </tr>
            <?php
            }
            ?>
            </table>
            <?php
            echo "<table border='0' cellspacing='0' cellpadding='2' width='100%' class='piedDeTableauListe'><tr><td valign='top' align='left' nowrap>" ;

            if ($x > 0)
            {
                echo "<input type='Submit' value='&lt;&lt; D&eacute;but' name='debutX' class='piedDeTableauListe'>&nbsp;" ;
                echo "<input type='Submit' value='&lt; Pr&eacute;c&eacute;dant' name='moinsX' class='piedDeTableauListe'>" ;
            }
            else
            {
                echo "&nbsp;" ;
            }

            echo "</td><td valign='center' align='left' nowrap>Page " .
                 (ceil($x / $nb_post_par_page) + 1) . "/$page_tot" .
                 "</td><td valign='top' align='left' nowrap>" ;

            if ((ceil($x / $nb_post_par_page) + 1) < $page_tot)
            {
                echo "<input type='submit' value=' Suivant &gt;' name='plusX' class='piedDeTableauListe'>&nbsp;" ;
                echo "<input type='Submit' value='Fin &gt;&gt;' name='finX' class='piedDeTableauListe'>" ;
            }
            else
            {
                echo "&nbsp;" ;
            }

            echo "<input type='hidden' name='x' value='$x'>" ;
            echo "</td><td align='right' valign='center' width='100%' nowrap>" ;
            echo "Allez directement &agrave; la page <select name='directX' class='piedDeTableauListe'>" ;

            for ($i = 0; $i < $page_tot; $i++)
            {
                echo "<option value='" . $i * $nb_post_par_page . "'" ;

                if (($x / $nb_post_par_page) == $i)
                {
                    echo "selected" ;
                }

                echo " >". ($i + 1) . "</option>" ;
            }

            echo "</select>&nbsp;<input type='Submit' name='direct_validX' value='Go' class='piedDeTableauListe'>" ;

            echo "</td></tr></table>" ;
            ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="2" class="piedDeTableauListe1">
              <tr>
                <td align="right" valign="center">Classer par
                  <select name="classement" class="piedDeTableauListe">
                    <option value="0" <?php echo ($HTTP_POST_VARS["classement"] == 0) ? "selected" : "" ; ?>>Date de post </option>
                    <option value="1" <?php echo ($HTTP_POST_VARS["classement"] == 1) ? "selected" : "" ; ?>>Auteur</option>
                    <option value="2" <?php echo ($HTTP_POST_VARS["classement"] == 2) ? "selected" : "" ; ?>>Titre</option>
                    <option value="3" <?php echo ($HTTP_POST_VARS["classement"] == 3) ? "selected" : "" ; ?>>Date de derni&egrave;re lecture/r&eacute;ponse</option>
                  </select>
                  par ordre
                  <select name="sens" class="piedDeTableauListe">
                    <option value="0" <?php echo ($HTTP_POST_VARS["sens"] == 0) ? "selected" : "" ; ?>>Croissant</option>
                    <option value="1" <?php echo ($HTTP_POST_VARS["sens"] == 1) ? "selected" : "" ; ?>>D&eacute;croissant</option>
                  </select>
                  <input type="submit" value="Trier" name="trier" class="piedDeTableauListe">
                </td>
              </tr>
            </table>

