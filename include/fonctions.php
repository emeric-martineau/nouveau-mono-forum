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
 * nettoyer tous les X jours. Vous avez besoin d'une base Mysql. On peut d?poser
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
V?rifie que le fichier n'est pas affich? seul */
if ( !defined('IN_NMF') )
{
    die("Restricted access");
}

// D?finit si on peut envoyer des e-mail
if (is_integer(strpos(get_cfg_var("disable_functions"), "mail")) && ($can_send_email != false))
{
    $can_send_email = false ;
}

////////////////////////////////////////////////////////////////////////////////
// Cr?er un lien
// ATTENTION : converti le reste de la chaine en HTML !
function lien_cliquable($chaine)
{
    // rend cliquable un lien dans le sujet...
    $lien = eregi_replace("([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])",
            "<a href='\\1://{ lien }' target='_blank'>{ lien }</a>", $chaine);

    $lien1 = eregi_replace("([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])",
             "\\1://\\2\\3", $chaine);

    return str_replace("{ lien }", htmlentities($lien1), $lien) ;
}

////////////////////////////////////////////////////////////////////////////////
// Ajout d'anti-slashes selon "Magic Quotes GPC"
function gpcAddSlashes($chaine)
{
    return(get_magic_quotes_gpc() == 1 ? $chaine : AddSlashes($chaine));
}

////////////////////////////////////////////////////////////////////////////////
// Ajout d'anti-slashes selon "Magic Quotes GPC"
function gpcDeleteSlashes($chaine)
{
    return(get_magic_quotes_gpc() == 1 ? stripslashes($chaine) : $chaine) ;
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
// G?re le BBCode
function BBCode($chaine)
{
    global $bbcode ;

    $nb_bbcode = count($bbcode) ;

    for ($i = 0; $i < $nb_bbcode; $i++)
    {
        $chaine = str_replace($bbcode[$i][0], $bbcode[$i][1], $chaine) ;

        // Converti \n en <br>
        // On ne peut plus utiliser nl2br car depuis PHP version 4.0.5 il est
        // compatible XHTML
        $chaine = str_replace("\n", "<br>", $chaine) ;
    }

    return $chaine ;
}

////////////////////////////////////////////////////////////////////////////////
// G?re la censure
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

////////////////////////////////////////////////////////////////////////////////
// Transforme une DATE/HEURE SQL en humain
function format_date($entry)
{
    list($fulldate, $time) = explode(" ", $entry) ;
    list($year, $month, $day) = explode("-", $fulldate);
    list($hour, $minute, $second) = explode(":", $time);

    return $day . "/" . $month . "/" . $year . " &agrave; " . $hour . ":" . $minute . ":" . $second ;
}

///////////////////////////////////////////////////////////////////////////////
// Transforme la date en format humain
function format_date_court($entry)
{
    list($day, $month, $year) = explode("-", $entry);

    return $day . "/" . $month . "/" . $year ;
}

///////////////////////////////////////////////////////////////////////////////
// Afficher un message
function afficher_message($auteur, $sujet, $email, $date, $texte)
{
    global $url_site ;

    ?>
    <table width="100%" border="0" cellspacing="1" cellpadding="0">
      <tr>
        <td colspan="2">
          <table width="100%" border="0" cellspacing="1" cellpadding="4">
            <?php
            if (!empty($sujet))
            {
            ?>
            <tr class="titre">
              <td colspan="2"><?php echo htmlentities($sujet) ; ?></td>
            </tr>
            <?php
            }
            ?>
            <tr class="ligneTitreMessageReponse">
              <td nowrap>
              <?php
              if (!empty($email) && !ereg("^@.+\$", $email))
              {
                  echo "<a href='mailto:" . htmlentities($email) . "'>" . htmlentities($auteur) . "</a>" ;
              }
              else
              {
                  echo htmlentities($auteur) ;
              }
              ?></td>
              <td width='100%'>le <?php echo format_date_court($date) ; ?></td>
            </tr>
            <tr class="ligneTitreMessageReponse2">
              <td colspan='2'><?php echo BBCode(smiley(lien_cliquable(censurer($texte)))) ; ?></td>
            </tr>
          </table>
          <table width="100%" border="0" cellspacing="1" cellpadding="2">
            <tr>
              <td colspan='2' height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <?php
}

///////////////////////////////////////////////////////////////////////////////
// Remplace  les variables
function remplacerVariables($texte, $variables)
{
    $nb = count($variable) ;

    while (list($var, $val) = each($variables))
    {
        $texte = eregi_replace("{ $var }", $val, $texte) ;
    }

    return $texte ;
}
?>