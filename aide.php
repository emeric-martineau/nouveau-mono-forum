<?php
/*******************************************************************************
 * Copyright (C) 2001-2004 MARTINEAU Emeric (php4php@free.fr)
 *
 * Nouvelle foire aux question.
 *
 * Version 1.2.1
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


echo "<center><h1>$titre_forum</h1><hr width='$width'><b>Aide</b><hr width='$width'></center><br><h2>1 - Pr&eacute;sentation</h2>" ;

echo $titre_forum ; ?>&nbsp;est une sorte de foire aux questions o&ugrave;
tout le monde peut d&eacute;poser (anonymement s'il le souhaite) une question<?php if ($nb_jour_avant_suppression > 0)
{?>
, qui sera supprim&eacute;e automatiquement tous les <?php echo $nb_jour_avant_suppression ; ?> &nbsp;jours apr&egrave;s qu'une question n'est plus &eacute;t&eacute; lue
<?php
}
?>. Tout le monde peut la consulter et y r&eacute;pondre.<br>
Si vous entrez une adresse URL dans le corps du message, un lien sera automatiquement
cr&eacute;&eacute;. Vous pouvez aussi ins&eacute;rer des smiley ou du BBCode (pour les
codes, voir la liste des smiley et BBCode ci-apr&egrave;s).<br>
Pour votre confort, votre nom et adresse e-mail sont m&eacute;moris&eacute;s dans
un cookie d&eacute;pos&eacute; sur votre ordinateur.<br>
<?php echo $titre_forum ; ?>&nbsp;dispose aussi d'une filtre qui censure certains mots.<br>
<br>
<h2>2 - Liste des smiley</h2>
<table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><b>Texte</b></td>
    <td><b>Image</b></td>
    <td><b>Explication</b></td>
  </tr>
  <?php
  $nb_smiley = count($smiley) ;

  for ($i = 0; $i < $nb_smiley; $i++)
  {
      echo "<tr><td>" . $smiley[$i][0] . "</td><td><img src='" . $url_site .
           "images/smiley/" . $smiley[$i][1] . "' border='0' alt='" .
            $smiley[$i][2] . "'></td><td>" . $smiley[$i][2] . "</td></tr>" ;
  }
  ?>
</table>
<br>
<h2>3 - Liste des BBCode</h2>
<table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><b>Code</b></td>
    <td><b>Explication</b></td>
  </tr>
  <?php
  $nb_bbcode = count($bbcode) ;

  for ($i = 0; $i < $nb_bbcode; $i++)
  {
      echo "<tr><td>" . $bbcode[$i][0] . "</td><td>" . $bbcode[$i][2] . "</td></tr>" ;
  }
  ?>
</table>
<h2>4 - Moteur de recherche</h2>
Le moteur de recherche effectue une recherche sur toute la base sur l'auteur et/ou 
sur le titre et le texte. La recherche ne tient pas compte de la case et on ne 
peut que rechercher un bout de texte (pas de possibilit&eacute; de chercher tel 
mot et tel mot).<br>
<br>
<a href='<?php echo $url_site . $SCRIPTNAME . "?sens=" . $HTTP_GET_VARS["sens"] . "&classement=" . $HTTP_GET_VARS["classement"]; ?>'>Retour au site &gt;&gt;</a></body></html>