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
<title>NouveauMonoForum - enregistrement de la configuration</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<h1>NouveauMonoForum - enregistrement de la configuration</h1>
<?php
define('IN_NMF', true);
include("../include/fonctions.php") ;
$login = gpcAddSlashes($HTTP_POST_VARS["login"]) ;
$host = gpcAddSlashes($HTTP_POST_VARS["host"]) ;
$mdp = gpcAddSlashes($HTTP_POST_VARS["mdp"]) ;
$base = gpcAddSlashes($HTTP_POST_VARS["base"]) ;
$prefixe_de_table = gpcAddSlashes($HTTP_POST_VARS["prefixe"]) ;
$nb_msg_par_page = gpcAddSlashes($HTTP_POST_VARS["nb_msg_par_page"]) ;
$nb_jour_avant_supp = gpcAddSlashes($HTTP_POST_VARS["nb_jour_avant_supp"]) ;
if (isset($HTTP_POST_VARS["dont_delete"]))
{
    $nb_jour_avant_supp = -1 ;
}
$nb_msg_pop = gpcAddSlashes($HTTP_POST_VARS["nb_msg_pop"]) ;
$email = gpcAddSlashes($HTTP_POST_VARS["email"]) ;
$titre = gpcAddSlashes($HTTP_POST_VARS["titre"]) ;
$url = gpcAddSlashes($HTTP_POST_VARS["url"]) ;
$script = gpcAddSlashes($HTTP_POST_VARS["script"]) ;
$theme = gpcAddSlashes($HTTP_POST_VARS["theme"]) ;
//$email = gpcAddSlashes($HTTP_POST_VARS["email"]) ;

$my_sql = mysql_connect($host,$login,$mdp) ;

if ($my_sql)
{
        @mysql_select_db($base) ;
        $query = mysql_query("CREATE TABLE " . $prefixe_de_table . "question (id varchar(8) NOT NULL default '', texte longtext NOT NULL, auteur varchar(50) NOT NULL default '', date varchar(10) NOT NULL default '', mail varchar(100) NOT NULL default '', sujet varchar(100) NOT NULL default '', envoi tinyint(1) NOT NULL default '', date_reelle datetime NOT NULL default '0000-00-00 00:00:00', UNIQUE KEY id (id), KEY auteur (auteur)) TYPE=MyISAM;") ;

        echo "Création de la table contenant les questions : " ;

        if ($query)
        {
            echo "OK" ;
        }
        else
        {
            echo "<font color='red'><b>ERREUR</b></font>"  ;
        }

        $query = mysql_query("CREATE TABLE " . $prefixe_de_table . "reponse (id varchar(8) NOT NULL default '', texte longtext NOT NULL, auteur varchar(50) NOT NULL default '', date varchar(10) NOT NULL default '', mail varchar(100) NOT NULL default '', date_reelle datetime NOT NULL default '0000-00-00 00:00:00', envoi tinyint(1) NOT NULL default '') TYPE=MyISAM;") ;

        echo "<br>Création de la table contenant les réponses : " ;

        if ($query)
        {
            echo "OK" ;
        }
        else
        {
            echo "<font color='red'><b>ERREUR</b></font>" ;
        }

    // Sauvegarde de l'ancien config.php
    if (!copy("../include/config.php", "../include/config.old"))
    {
        echo "<br>** ERREUR ** Impossible de sauvegarder le fichier config.php" ;
    }

    $fd = fopen("../include/config.php", "w") ;

    if ($fd)
    {
        fwrite($fd, "<?php // Généré le " . date("d/m/Y à G:i:s") . "\n") ;
        fwrite($fd, "/* Vérifie que le fichier n'est pas affiché seul */\n") ;
        fwrite($fd, "if ( !defined('IN_NMF') ) { die('Restricted access'); }\n") ;
        fwrite($fd, "    \$host = '$host' ;\n") ;
        fwrite($fd, "    \$user = '$login' ;\n") ;
        fwrite($fd, "    \$pw = '$mdp';\n" ) ;
        fwrite($fd, "    \$db = '$base' ;\n");
        fwrite($fd, "    \$prefixe_de_table = '$prefixe_de_table' ;\n") ;
        fwrite($fd, "    \$nb_post_par_page = $nb_msg_par_page ;\n") ;
        fwrite($fd, "    \$nb_jour_avant_suppression = $nb_jour_avant_supp ;\n") ;
        fwrite($fd, "    \$theme = '$theme' ;\n") ;
        fwrite($fd, "    \$titre_forum = '$titre' ;\n") ;

        if (ereg("^.+/\$", $url) != 1)
        {
            $url = $url . "/" ;
        }

        fwrite($fd, "    \$url_site = '$url' ;\n") ;
        fwrite($fd, "    \$can_send_email = $email ;\n") ;
        fwrite($fd, "    \$SCRIPTNAME = '$script' ;\n") ;
        fwrite($fd, "    \$nb_rep_pr_msg_hot = $nb_msg_pop ;\n") ;
//        fwrite($fd, "    \$email_site = '$email' ;\n") ;
        fwrite($fd, "?>") ;

        echo "<br>Fichier de configuration g&eacute;n&eacute;r&eacute;.<br>" ;

        include("suppfichiers.php") ;
    }
    else
    {
        echo "<br>** ERREUR ** Impossible d'enregistrer le fichier de configuration config.php." ;
    }
}
else
{
    echo "** ERREUR ** Les informations pour la connection à la base de données sont erron&eacute;." ;
}
?>
</body>
</html>