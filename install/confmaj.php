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
 ******************************************************************************/
?>
<html>
<head>
<title>NouveauMonoForum - Configuration de la mise &agrave; jour</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<h1>NouveauMonoForum - Configuration de la mise &agrave; jour</h1>
<?php
$erreur = false ;
if (!is_writeable("../include") || !is_readable("../include"))
{
    echo "** ERREUR ** Le r&eacute;pertoire <b>include</b> doit &ecirc;tre accessible en lecture et &eacute;criture pour le propri&eacute;taire, le groupe et tout le monde." ;
    $erreur = true ;
}

if (!is_writeable("../include/config.php") || !is_readable("../include/config.php"))
{
    echo "** ERREUR ** Le fichier <b>include/config.php</b> doit &ecirc;tre accessible en lecture et &eacute;criture pour le propri&eacute;taire, le groupe et tout le monde." ;
    $erreur = true ;
}

if (!is_readable("../config.php"))
{
    echo "** ERREUR ** Le fichier <b>config.php</b> doit &ecirc;tre accessible en lecture pour le propri&eacute;taire, le groupe et tout le monde." ;
    $erreur = true ;
}

if (!is_writeable("../include/email.php") || !is_readable("../include/email.php"))
{
    echo "** ERREUR ** Le fichier <b>include/email.php</b> doit &ecirc;tre accessible en lecture et &eacute;criture pour le propri&eacute;taire, le groupe et tout le monde." ;
    $erreur = true ;
}

if (!is_writeable("../include/bbcode.php") || !is_readable("../include/bbcode.php"))
{
    echo "** ERREUR ** Le fichier <b>include/bbcode.php</b> doit &ecirc;tre accessible en lecture et &eacute;criture pour le propri&eacute;taire, le groupe et tout le monde." ;
    $erreur = true ;
}

if (!is_writeable("../include/smileys.php") || !is_readable("../include/smileys.php"))
{
    echo "** ERREUR ** Le fichier <b>include/smileys.php</b> doit &ecirc;tre accessible en lecture et &eacute;criture pour le propri&eacute;taire, le groupe et tout le monde." ;
    $erreur = true ;
}

if (!is_writeable("../include/censure.php") || !is_readable("../include/censure.php"))
{
    echo "** ERREUR ** Le fichier <b>include/censure.php</b> doit &ecirc;tre accessible en lecture et &eacute;criture pour le propri&eacute;taire, le groupe et tout le monde." ;
    $erreur = true ;
}

if (!$erreur)
{
?>
<form method="post" action="maj.php">
  Avant de valider, vous devez d&eacute;placer le fichier <b>config.php</b> dans 
  le r&eacute;pertoire <b>include/ </b>.<br>
  <br>
  <table border="0" cellpadding="2">
    <tr>
      <td>Sauvegarder le fichier config.php</td>
      <td>
        <input type="checkbox" name="sav_config" value="checkbox" checked>
      </td>
    </tr>
    <tr>
      <td> Sauvegarder le fichier bbcode.php</td>
      <td>
        <input type="checkbox" name="sav_bbcode" value="checkbox" checked>
      </td>
    </tr>
    <tr>
      <td>Sauvegarder le fichier censure.php </td>
      <td>
        <input type="checkbox" name="sav_censure" value="checkbox" checked>
      </td>
    </tr>
    <tr>
      <td>Sauvegarder le fichier email.php </td>
      <td>
        <input type="checkbox" name="sav_email" value="checkbox" checked>
      </td>
    </tr>
    <tr>
      <td>Sauvegerder le fichier smileys.php </td>
      <td>
        <input type="checkbox" name="sav_smiley" value="checkbox" checked>
      </td>
    </tr>
  </table>
  Thème <select name="theme">
  <?php
  // Constitution de la liste des fichiers
  $handle = opendir("../themes") ;

  if ($handle)
  {
      while ($file = readdir($handle))
      {
          if (!ereg("^\.", $file))
          {
              echo '<option value="' . htmlentities($file) . '">' . htmlentities($file) . "</option>" ;
          }
      }
  }
  ?>
  </select>
  <br>
<hr>
  <input type="submit" name="enregistrer" value="Enregistrer la configuration">

</form>
<?php
}
?>
</body>
</html>