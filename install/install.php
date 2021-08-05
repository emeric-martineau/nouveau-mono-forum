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
<title>NouveauMonoForum - configuration</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<h1>NouveauMonoForum - configuration</h1>
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

if (!$erreur)
{
?>
<form method="post" action="table.php">
  Login de connexion &agrave; la base de donn&eacute;es 
  <input type="text" name="login">
  <br>
  Mot de passe &agrave; la base de donn&eacute;es 
  <input type="password" name="mdp">
  <br>
  Host de la base de donn&eacute;es 
  <input type="text" name="host" value="localhost">
  <br>
  Base de donn&eacute;es
  <input type="text" name="base" value="">
  <br>
  Pr&eacute;fixe des tables 
  <input type="text" name="prefixe" value="nouveaumonoforum_">
  <br><br>
  <hr>
  <br>
  Nombre de messages par page 
  <input type="text" name="nb_msg_par_page" value="10">
  <br>
  Nombre de jours de non lecture avant suppression d'un message 
  <input type="text" name="nb_jour_avant_supp" value="365">
  <input type="checkbox" name="dont_delete" value="radiobutton">
  Ne pas supprimer<br>
  Nombre de r&eacute;ponses pour qu'un message soit populaire 
  <input type="text" name="nb_msg_pop" value="25">
  <br>
  <?php
  $can_send_email = true ;
  // Définit si on peut envoyer des e-mail
  if (is_integer(strpos(get_cfg_var("disable_functions"), "mail")))
  {
      $can_send_email = false ;
  }
  ?>
  Permettre l'envoie d'un e-mail qu'on une r&eacute;ponse est donn&eacute;e 
  <select name="email">
    <option value="true" <?php echo ($can_send_email) ? "selected" : "" ; ?>>Oui</option>
    <option value="false" <?php echo (!$can_send_email) ? "selected" : "" ; ?>>Non</option>
  </select>
  <?php
  if (!$can_send_email)
  {
      echo "<br><font color='red'><b>ATTENTION</b> ! La fonction d'envoie d'e-mail à été désactivé par votre hébergeur. <b>NouveauMonoForum</b> bloquera automatiquement la possibilité d'envoie d'e-mail.<br>Cependant, vous pouvez tout de même configurer <b>NouveauMonoForum</b> pour permettre l'envoie d'e-mail. Ainsi, dès que votre hébergeur permettra l'envoie d'e-mail, vos visiteurs en profiteront, sans que vous ayez besoin de reconfigurer <b>NouveauMonoForum</b>.</font>" ;
  }
  ?>
  <br>
  <br>
  <hr>
  <br>
  Titre du forum 
  <input type="text" name="titre" value="Mon forum">
  <br>
  URL du site 
  <input type="text" name="url" value="http://">
  <br>
  Nom du fichier du script
  <input type="text" name="script" value="forum.php">
  <br>
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
  </select><br>
  <br><hr>
  <input type="submit" name="enregistrer" value="Enregistrer la configuration">
  <input type="reset" name="Submit2" value="R&eacute;tablir">
</form>
<?php
}
?>
</body>
</html>