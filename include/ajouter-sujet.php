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
?>
            <table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td colspan="2">
                  <table width="100%" border="0" cellspacing="1" cellpadding="4">
                    <tr class="titre">
                      <td>
                      <?php
                      if (isset($HTTP_GET_VARS["repondre"]))
                      {
                          echo "R&eacute;pondre au message <i>" . stripslashes($HTTP_GET_VARS["titre"]) . "</i>" ;
                      }
                      else
                      {
                          echo "Ecriture d'un nouveau message" ;
                      }

                      if (!empty($messageErreur))
                      {
                          echo "<br><font color='red'><b>$messageErreur</b></font>" ;
                      }
                      ?>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                <table width="100%" border="0" cellspacing="1" cellpadding="4" class="sousTitre">
                  <tr class="sousTitre">
                    <td colspan="2">Information sur l'utilisateur</td>
                  </tr>
                  <tr class="ligneAjout">
                    <td nowrap>Pseudo</td>
                    <td width='100%'><input type='text' name='auteur' value='<?php echo htmlentities($HTTP_COOKIE_VARS["auteur"]) ; ?>' class='ligneAjout' size='30'></td>
                  </tr>
                  <tr class="ligneAjout">
                    <td nowrap>E-mail</td>
                    <td width='100%'><input type='text' class='ligneAjout' name='adresse' value='<?php echo htmlentities($HTTP_COOKIE_VARS["email"]) ; ?>' size='30'>
                    <br><input name='invisible' type='checkbox' <?php echo (isset($HTTP_POST_VARS["invisible"])) ? "checked" : "" ; ?>> Ne pas rendre mon adresse e-mail publique.</td>
                  </tr>
                  <tr class="sousTitre">
                    <td colspan="2">Paramètre du sujet</td>
                  </tr>
                  <tr class="ligneAjout">
                    <td nowrap>
                    <?php
                    if (!isset($HTTP_GET_VARS["repondre"]))
                    {
                    ?>
                      Titre du sujet
                    </td>
                    <td width='100%'>
                      <input type='text' name='sujet' class='ligneAjout' value='<?php echo htmlentities($HTTP_POST_VARS["sujet"]) ; ?>' size='50'><br>
                    <?php
                    }
                    else
                    {
                    ?>
                      &nbsp;
                    </td>
                    <td width='100%'>
                    <?php
                    }
                    if ($can_send_email == true)
                    {
                        echo "<input type='checkbox' name='envoi_mel' class='ligneAjout' " ;
                        echo (isset($HTTP_POST_VARS["envoi_mel"])) ? "checked" : "" ;
                        echo "> M'avertir par e-mail quand une réponse au sujet est donnée.<br><b>Attention !</b> Vous ne pourrez pas supprimer la surveillance du message par la suite. Vous recevrez des e-mails tant que le message existera." ;
                    }
                    ?>
                    </td>
                  </tr>
                  <tr class="sousTitre">
                    <td colspan="2">Saisissez votre message</td>
                  </tr>
                  <tr class="ligneAjout">
                    <td nowrap valign="center">
                    <?php
                    echo "<a target='_blank' href='" . $url_site . $SCRIPTNAME .  "?aide=1&x=" . urlencode($x) . "&sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "#smiley'>" ;
                    echo "Liste des smileys</a><br><a target='_blank' href='" . $url_site . $SCRIPTNAME . "?aide=1&x=" . urlencode($x) . "&sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "#bbcode'>" ;
                    echo " Liste des BBCode</a>" ;
                    ?>
                    </td>
                    <td width='100%'><textarea cols='60' rows='15' class='ligneAjout' name='texte' wrap='PHYSICAL'><?php echo htmlentities($HTTP_POST_VARS["texte"]) ; ?></textarea></td>
                  </tr>
                  <tr class="sousTitre">
                    <td colspan="2" align="center">
                    <?php
                    echo "<input type='hidden' name='x' value='" . htmlentities($x) . "'>" ;

                    echo "<input type='submit' value='Enregistrer le message' name='" .
                         ((isset($HTTP_GET_VARS["repondre"])) ? "quest_reponse" : "poster") . "' class='ligneAjout'>&nbsp;" ;
                    echo "<input type='submit' class='ligneAjout' value='Retour au forum'>" ;

                    if (isset($HTTP_GET_VARS["repondre"]))
                    {
                        echo "<input type='hidden' name='y' value='" . htmlentities($y) . "'>" ;
                        echo "<input type='hidden' name='id' value='" . htmlentities($HTTP_GET_VARS["id"]) . "'>" ;
                        echo "<input type='hidden' name='titre' value='" . htmlentities($HTTP_GET_VARS["titre"]) . "'>" ;
                    }
                    ?>
                    </td>
                  </tr>
                </table>
                </td>
              </tr>
            </table>

