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
<table border="0" cellspacing="5" cellspadding="0">
  </tr>
    <td class="aide">
      <h1 class="aide">1 - Pr&eacute;sentation</h1>

<?php echo $titre_forum ; ?> est un mono-forum (forum avec un seul salon) o&ugrave; tout le monde peut d&eacute;poser un message, consulter et r&eacute;pondre, sans n&eacute;cessit&eacute; une authentification.<br>
<?php if ($nb_jour_avant_suppression > 0) {?>Les messages sont supprim&eacute;s automatiquement tous les <?php echo $nb_jour_avant_suppression ; ?> &nbsp;jours apr&egrave;s qu'un message n'est plus &eacute;t&eacute; lu.<br><br><?php } ?>
Le forum g&egrave;re tout automatiquement, les urls dans les messages sont automatiquement cliquables, les smileys sont convertis en images, les retours de lignes sont g&eacute;r&eacute;s automatiquement et le forum g&egrave;re le BBCode afin d'agr&eacute;menter vos messages.<br>
Pour votre confort, votre nom et adresse e-mail sont m&eacute;moris&eacute;s dans un cookie d&eacute;pos&eacute; sur votre ordinateur, évitant ainsi la resaisie.<br>
Rassurez-vous toutefois, <?php echo $titre_forum ; ?> ne contient pas la moindre lettre de JavaScript.<br>
Le forum dispose &eacute;galement d'un filtre qui censure certains mots et il est possible, suivant la configuration du forum et du serveur h&eacute;bergeur, d'&ecirc;tre pr&eacute;venu par e-mail quand une r&eacute;ponse est post&eacute;e.<br>
<br>
<a name="smiley"></a>
<h1 class='aide'>2 - Liste des smiley</h1>
<table border="0" cellspacing="0" cellpadding="5">
  <tr class='aide'>
    <td><b>Texte</b></td>
    <td><b>Image</b></td>
    <td><b>Explication</b></td>
  </tr>
  <?php
  $nb_smiley = count($smiley) ;

  for ($i = 0; $i < $nb_smiley; $i++)
  {
      echo "<tr class='aide'><td>" . $smiley[$i][0] . "</td><td><img src='" . $url_site .
           "images/smiley/" . $smiley[$i][1] . "' border='0' alt='" .
            $smiley[$i][2] . "'></td><td>" . $smiley[$i][2] . "</td></tr>" ;
  }
  ?>
</table>
<br>
<a name="bbcode"></a>
<h1 class='aide'>3 - Liste des BBCode</h1>
<table border="0" cellspacing="0" cellpadding="5">
  <tr class='aide'>
    <td><b>Code</b></td>
    <td><b>Explication</b></td>
  </tr>
  <?php
  $nb_bbcode = count($bbcode) ;

  for ($i = 0; $i < $nb_bbcode; $i++)
  {
      echo "<tr class='aide'><td>" . $bbcode[$i][0] . "</td><td>" . $bbcode[$i][2] . "</td></tr>" ;
  }
  ?>
</table>
<h1 class='aide'>4 - Moteur de recherche</h1>
Le moteur de recherche effectue une recherche sur toute la base sur l'auteur et/ou 
sur le titre et le texte. La recherche ne tient pas compte de la case et on ne 
peut que rechercher un bout de texte (pas de possibilit&eacute; de chercher tel 
mot et tel mot).
      <h1 class="aide">5 - Stastistiques</h1>
      <?php
          $q = mysql_query("SELECT count(*) FROM " . $prefixe_de_table . "question") ;
          $nb = mysql_fetch_row($q) ;

          echo $nb[0] . " question(s) dans la base.<br>";

          $q = mysql_query("SELECT count(*) FROM " . $prefixe_de_table . "reponse") ;
          $nb = mysql_fetch_row($q) ;

          echo $nb[0] . " r&eacute;ponse(s) dans la base.<br>";

      ?>
    </td>
  </tr>
</table>