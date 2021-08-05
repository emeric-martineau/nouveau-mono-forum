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
<title>NouveauMonoForum - Mise à jour</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<h1>NouveauMonoForum - Mise &agrave; jour</h1>
<?php
include("../config.php") ;

// Sauvegarde de l'ancien config.php
if (isset($HTTP_POST_VARS["sav_config"]))
{
    if (!copy("../config.php", "../include/config.old"))
    {
        echo "<br>** ERREUR ** Impossible de sauvegarder le fichier config.php" ;
    }
}

if (!unlink("../config.php"))
{
    echo "<br>** ERREUR ** Impossible de supprimer l'ancien fichier de configuration !" ;
}

$fd = fopen("../include/config.php", "w") ;

if ($fd)
{
    fwrite($fd, "<?php // Généré le " . date("d/m/Y à G:i:s") . "\n") ;
    fwrite($fd, "/* Vérifie que le fichier n'est pas affiché seul */\n") ;
    fwrite($fd, "if ( !defined('IN_NMF') ) { die('Restricted access'); }\n") ;
    fwrite($fd, "    \$host = '$host' ;\n") ;
    fwrite($fd, "    \$user = '$user' ;\n") ;
    fwrite($fd, "    \$pw = '$pwd';\n" ) ;
    fwrite($fd, "    \$db = '$db' ;\n");
    fwrite($fd, "    \$prefixe_de_table = '$prefixe_de_table' ;\n") ;
    fwrite($fd, "    \$nb_post_par_page = $nb_post_par_page ;\n") ;
    fwrite($fd, "    \$nb_jour_avant_suppression = $nb_jour_avant_suppression ;\n") ;
    fwrite($fd, "    \$theme = '" . $HTTP_POST_VARS["theme"] . "' ;\n") ;
    fwrite($fd, "    \$titre_forum = '$titre_forum' ;\n") ;
    fwrite($fd, "    \$url_site = '$url_site' ;\n") ;
    fwrite($fd, "    \$can_send_email = $can_send_email ;\n") ;
    fwrite($fd, "    \$SCRIPTNAME = '$SCRIPTNAME' ;\n") ;
    fwrite($fd, "    \$nb_rep_pr_msg_hot = $nb_rep_pr_msg_hot ;\n") ;
//    fwrite($fd, "    \$email_site = '" . gpcAddSlashes($HTTP_POST_VARS["email"]) . "' ;\n") ;
    fwrite($fd, "?>") ;

    echo "<br>Fichier de configuration g&eacute;n&eacute;r&eacute;.<br>" ;
}
else
{
    echo "<br>** ERREUR ** Impossible d'enregistrer le fichier de configuration config.php." ;
}

// Sauvegarde de l'ancien bbcode.php
if (isset($HTTP_POST_VARS["sav_bbcode"]))
{
    if (!copy("../include/bbcode.php", "../include/bbcode.old"))
    {
        echo "<br>** ERREUR ** Impossible de sauvegarder le fichier bbcode.php" ;
    }
}

$fd = fopen("../include/bbcode.php", "w") ;

if ($fd)
{
    fwrite($fd, "<?php // Généré le " . date("d/m/Y à G:i:s") . "\n") ;
    fwrite($fd, "/* Vérifie que le fichier n'est pas affiché seul */\n") ;
    fwrite($fd, "if ( !defined('IN_NMF') ) { die('Restricted access'); }\n") ;

    $nbbbcode = count($bbcode) ;

    for ($i = 0; $i < $nbbbcode; $i++)
    {
        fwrite($fd, "\$bbcode[] = array(\"" . $bbcode[$i][0] . "\", \"" . $bbcode[$i][1] . "\", \"" . $bbcode[$i][2] . "\") ;\n") ;
    }

    fwrite($fd, "?>") ;

    echo "<br>Fichier du BBCode g&eacute;n&eacute;r&eacute;.<br>" ;
}
else
{
    echo "<br>** ERREUR ** Impossible d'enregistrer le fichier bbcode.php." ;
}

// Sauvegarde de l'ancien smileys.php
if (isset($HTTP_POST_VARS["sav_smiley"]))
{
    if (!copy("../include/smileys.php", "../include/smileys.old"))
    {
        echo "<br>** ERREUR ** Impossible de sauvegarder le fichier smileys.php" ;
    }
}

$fd = fopen("../include/smileys.php", "w") ;

if ($fd)
{
    fwrite($fd, "<?php // Généré le " . date("d/m/Y à G:i:s") . "\n") ;
    fwrite($fd, "/* Vérifie que le fichier n'est pas affiché seul */\n") ;
    fwrite($fd, "if ( !defined('IN_NMF') ) { die('Restricted access'); }\n") ;

    $nbsmiley = count($smiley) ;

    for ($i = 0; $i < $nbsmiley; $i++)
    {
        fwrite($fd, "\$smiley[] = array(\"" . $smiley[$i][0] . "\", \"" . $smiley[$i][1] . "\", \"" . $smiley[$i][2] . "\") ;\n") ;
    }

    fwrite($fd, "?>") ;

    echo "<br>Fichier du smiley g&eacute;n&eacute;r&eacute;.<br>" ;
}
else
{
    echo "<br>** ERREUR ** Impossible d'enregistrer le fichier smileys.php." ;
}

// Sauvegarde de l'ancien censure.php
if (isset($HTTP_POST_VARS["sav_censure"]))
{
    if (!copy("../include/censure.php", "../include/censure.old"))
    {
        echo "<br>** ERREUR ** Impossible de sauvegarder le fichier censure.php" ;
    }
}

$fd = fopen("../include/censure.php", "w") ;

if ($fd)
{
    fwrite($fd, "<?php // Généré le " . date("d/m/Y à G:i:s") . "\n") ;
    fwrite($fd, "/* Vérifie que le fichier n'est pas affiché seul */\n") ;
    fwrite($fd, "if ( !defined('IN_NMF') ) { die('Restricted access'); }\n") ;
    fwrite($fd, "    \$texte_a_inserer = '$texte_a_inserer';\n") ;
    fwrite($fd, "    \$censure = array(") ;

    $nbcensure = count($censure) - 1;

    for ($i = 0; $i < $nbcensure; $i++)
    {
        fwrite($fd, "'" . $censure[$i] . "',") ;
    }

    fwrite($fd, "'" . $censure[$i] . "');\n") ;
    fwrite($fd, "?>") ;

    echo "<br>Fichier de la censure g&eacute;n&eacute;r&eacute;.<br>" ;
}
else
{
    echo "<br>** ERREUR ** Impossible d'enregistrer le fichier censure.php." ;
}

// Sauvegarde de l'ancien email.php
if (isset($HTTP_POST_VARS["sav_email"]))
{
    if (!copy("../include/email.php", "../include/email.old"))
    {
        echo "<br>** ERREUR ** Impossible de sauvegarder le fichier email.php" ;
    }
}

$fd = fopen("../include/email.php", "w") ;

if ($fd)
{
    fwrite($fd, "<?php die('Restricted access');\n") ;
    fwrite($fd, "$subjectEmail\n") ;
    fwrite($fd, "$texteEmail\n") ;
    fwrite($fd, "?>") ;

    echo "<br>Fichier email g&eacute;n&eacute;r&eacute;.<br>" ;
}
else
{
    echo "<br>** ERREUR ** Impossible d'enregistrer le fichier email.php." ;
}

$my_sql = mysql_connect($host,$user,$pw) ;

if ($my_sql)
{
    @mysql_select_db($db) ;

    echo "Conversion des oui en 1 : " ;
    $query = mysql_query("UPDATE " . $prefixe_de_table . "question SET envoi='1' WHERE envoi='oui';") ;
    if ($query)
    {
        echo "OK" ;
    }
    else
    {
        echo "<font color='red'><b>ERREUR</b></font>"  ;
    }

    echo "<br>Conversion des non en 0 : " ;
    $query = mysql_query("UPDATE " . $prefixe_de_table . "question SET envoi='0' WHERE envoi='non';") ;
    if ($query)
    {
        echo "OK" ;
    }
    else
    {
        echo "<font color='red'><b>ERREUR</b></font>"  ;
    }

    echo "<br>Conversion du champ envoi en TINYINT(1) : " ;
    $query = mysql_query("ALTER TABLE " . $prefixe_de_table . "question CHANGE envoi envoi TINYINT(1) NOT NULL;") ;
    if ($query)
    {
        echo "OK" ;
    }
    else
    {
        echo "<font color='red'><b>ERREUR</b></font>"  ;
    }

    echo "<br>Conversion du champ envoi en TINYINT(1) : " ;
    $query = mysql_query("ALTER TABLE " . $prefixe_de_table . "reponse ADD envoi TINYINT(1) DEFAULT '0' NOT NULL;") ;
    if ($query)
    {
        echo "OK" ;
    }
    else
    {
        echo "<font color='red'><b>ERREUR</b></font>"  ;
    }

    include("suppfichiers.php") ;
}
else
{
    echo "** ERREUR ** Les informations pour la connection à la base de données sont erron&eacute;." ;
}
?>
</body>
</html>