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
VERSION 1
=========
Bubule le 21/08/2003 :
  - formatage du code source,
  - suppression de \" au profit de '
  - mise en fonction du cliquage de lien,
  - ajout de @ devant les fonctions de BDD.
  - suppression de l'envoie de message directe pour éviter les spam,
  - vérification des données venant du formulaire vers la base de données,
  - adressage des variables des formulaires par HTTP_*_VARS,
  - suppression de javascript,
  - gestion de l'affichage des boutons suivant et précédant,
  - personnalisation du nombre de message par page.

Bubule le 22/08/2003 :
  - prefixe de table de base de données,
  - amélioration du système d'auto netoyage,
  - suppression du passage par formulaire du paramètre envoi qui indiquait s'il
    fallait envoyer un e-mail ou non,
  - optimisation des tables après suppression,
  - ajout de la possibilité de configurer le titre du forum,
  - ajout de la possibiliter de configurer un adresse de retour,
  - nom du fichier forum.php en automatique,
  - configuration de l'url du forum,
  - la liste n'apparait pas lors d'un poste de nouveau message ou d'une réponse.

Bubule le 25/08/2003 :
  - affichage ou non d'un lien avec l'adresse e-mail,
  - n'envoie pas d'e-mail s'il n'y a pas d'e-mail,
  - possibilité de ne pas rendre l'e-mail public,
  - possibilité de ne pas afficher la liste des réponses dans la listes des
    question,
  - réaffichage des données lorsqu'on rempli un formulaire incorrectement,
  - paramètre la possibilité d'envoyer ou non des e-mail si la fonction mail()
    est désactivée,
  - tous l'aspect du site est configurable par CSS,
  - correction dans le html,
  - mémorisation du nom d'auteur et adresse e-mail par cokkie,
  - retour automatique à la page de la liste.

Bubule le 26/03/2003 :
  - ajout d'un bouton << Début et Fin >>,
  - ajout d'une liste pour allez directement à une page,
  - gestion des smileys,
  - install automatique.

VERSION 1.2
===========
Bubule le 14/11/2003 :
  - possibilité de ne pas supprimer les messages,
  - affecte une valeur par défaut à la variable $x,
  - ajout d'icône,
  - correction d'une ENORME erreur qui autorisait l'envoie d'e-mail alors
    que la fonction est désactivé.
  - Possibilité d'effectuer une recherche avec gestion multi page,
  - possibilité de configurer la largeur du site,
  - suppression de .... dans les sujets,
  - correction d'un bug qui fait qu'il ne détectait pas toujours que la fonction
    mail() était désactivé,
  - les saut de ligne physique de le texte entré sont pris en compte et sont
    convertis en saut HTML.

Bubule le 17/11/2003 :
  - Possibilité de classer la liste des message,
  - Optimize juste les tables ou il y a eu suppressions,
  - Documenter CSS.

VERSION 1.2.1
=============
Bubule le 25/11/2003 :
  - ENORME oubli de set_magic_quotes_runtime(0);,
  - correction un bug (inversion de deux ligne de code) qui provovoquait un non
    affichage du forum et représente un trou de sécurité.

Bubule le 27/11/2003
  - multi-page pour les réponse,
  - modification de la gestion du classement/sens des questions,
  - personnaliser le message envoyé suite à une réponse,

VERSION 1.4
===========
Bubule le 9/01/2004
  - changement de nom. NFAQ devient NouveauMonoForum,
  - changement de graphisme,

Bubule le 12/01/2004
  - suppression de la possibilité ou nom d'afficher les icônes (obselete),
  - suppression de la possibilité d'afficher au non les réponses dans la liste
    (obselete),
  - suppression de la possibilité de configuraer la largeur du site (obselete),

Bubule le 13/01/2004
  - suppression de l'url de retour (obselete),

Bubule le 14/01/2004
  - dans la liste des messages, la liste des page reste sur la page courante,
  - résolution des conflits de page entre x et y,
  - suppression d'un champs de formulaire obselete (total),
  - réécriture de la fonction lien_cliquable() pour éviter les trous de
    sécurité,
  - gestion des themes,

Bubule le 16/01/2004
  - impossibilité d'exécuter un fichier sans passer par le fichier principal
    du site (par défaut forum.php),
  - externalisation du BBCode, Censure, Smiley,

Bubule le 25/01/2003 :
  - mise en fichier de l'e-mail envoyé quand il y a une réponse,

Bubule le 26/01/2003 :
  - licence de NouveauMonoForum en pied de page,

Bubule le 5/02/2004 :
  - Convertir les oui en 1 et les non en 0 dans la base de données.
  - Ajouter un champ pour les réponses pour recevoir un e-mail,

Bubule le 6/02/2004 :
  - les gens qui répondent peuvent recevoire aussi un e-mail,
*/

// Définit cette constante qui permet ou empache l'exécution des scripts
define('IN_NMF', true);

// Inclu le fichier de configuration
include("include/config.php") ;
include("include/smileys.php") ;
include("include/bbcode.php") ;
include("include/censure.php") ;

// "Magic Quotes". NE PAS MODIFIER !!!
set_magic_quotes_runtime(0);

// Connextion à la base de donnée
$my_sql = @mysql_connect($host,$user,$pw) or die(mysql_error()) ;

// Sélectionne la base
@mysql_select_db("$db") or die(mysql_error()) ;

// Gestion des cookies
if(isset($HTTP_POST_VARS['auteur']))
{
    setcookie("auteur", $HTTP_POST_VARS['auteur']);
    $HTTP_COOKIE_VARS['auteur'] = $HTTP_POST_VARS['auteur'] ;
}

if(isset($HTTP_POST_VARS['adresse']))
{
    setcookie("email", $HTTP_POST_VARS['adresse']);
    $HTTP_COOKIE_VARS['adresse'] = $HTTP_POST_VARS['adresse'] ;
}

include("include/fonctions.php") ;

/* Initialisation des variables */
if (!isset($HTTP_GET_VARS["id"]))
{
    $HTTP_GET_VARS["id"] = "" ;
}

if (!isset($HTTP_COOKIE_VARS["email"]))
{
    $HTTP_COOKIE_VARS["email"] = "" ;
}

if (!isset($HTTP_COOKIE_VARS["auteur"]))
{
    $HTTP_COOKIE_VARS["auteur"] = "" ;
}

if (!isset($HTTP_GET_VARS["titre"]))
{
    $HTTP_GET_VARS["titre"] = "" ;
}

if (!isset($HTTP_POST_VARS["sujet"]))
{
    $HTTP_POST_VARS["sujet"] = "" ;
}

if (!isset($HTTP_POST_VARS["texte"]))
{
    $HTTP_POST_VARS["texte"] = "" ;
}
/* Fin initailisation des variables */

// Numéro de la page question
if (isset($HTTP_POST_VARS['x']))
{
    $x = gpcAddSlashes($HTTP_POST_VARS['x']) ;
}
else if (isset($HTTP_GET_VARS['x']))
{
    $x = $HTTP_GET_VARS['x'] ;
}
else
{
    $x = 0 ;
}

// Numéro de la page réponse
if (isset($HTTP_POST_VARS['y']))
{
    $y = gpcAddSlashes($HTTP_POST_VARS['y']) ;
}
else
{
    $y = 0 ;
}

// permet de réafficher la suite des réponses
if (isset($HTTP_POST_VARS["voirsujet"]) && !isset($HTTP_POST_VARS["quest_reponse"]) && !isset($HTTP_POST_VARS["retour_forum"]))
{
    $HTTP_GET_VARS["voirsujet"] = 1 ;
}

// Tri de l'ordre des questions
if ((isset($HTTP_GET_VARS["classement"]) || isset($HTTP_GET_VARS["sens"])) && !isset($HTTP_POST_VARS["trier"]))
{
    // Afin de ne pas tout recoder, on fait un p'tit subterfuge
    $HTTP_POST_VARS["trier"] = 1 ;
    $HTTP_POST_VARS["classement"] = $HTTP_GET_VARS["classement"] ;
    $HTTP_POST_VARS["sens"] = $HTTP_GET_VARS["sens"] ;
}
else if (!isset($HTTP_POST_VARS["trier"]))
{
    $classement = "date_reelle" ;
    $HTTP_POST_VARS["classement"] = 3 ;
    $sens = " desc" ;
    $HTTP_POST_VARS["sens"] = 1 ;
}

if (isset($HTTP_POST_VARS["trier"]))
{
    switch($HTTP_POST_VARS["classement"])
    {
        case 0 : $classement = "date" ;
                 break ;
        case 1 : $classement = "auteur" ;
                 break ;
        case 2 : $classement = "sujet" ;
                 break ;
        case 3 : $classement = "date_reelle" ;
                 break ;
        default : $classement = "date_reelle" ;
    }

    switch ($HTTP_POST_VARS["sens"])
    {
        case 0 : $sens = "asc" ;
                 break ;
        case 1 : $sens = "desc" ;
                 break ;
        default : $sens = "desc" ;
    }
}

// On calcule la position dès le début pour éviter certaines erreurs
// Affichage des sujets
$res1 = @mysql_query("SELECT * FROM " . $prefixe_de_table . "question") ;

$tot = @mysql_num_rows($res1) ;
@mysql_free_result($res1) ;

$page_tot = ceil($tot / $nb_post_par_page);

// Vérifie qu'il s'agit bien d'un chiffre
if (!is_integer($x))
{
    $x = 0 ;
}

// Si on a demandé la page précédante
if (isset($HTTP_POST_VARS["moinsX"]) && ($x > 0))
{
    $x -= $nb_post_par_page ;
}

// Si on a demandé la page précédante
if (isset($HTTP_POST_VARS["plusX"]) && (($x + $nb_post_par_page) <= $tot))
{
    $x += $nb_post_par_page ;
}

// 1erer page
if (isset($HTTP_POST_VARS["debutX"]))
{
    $x = 0 ;
}

// Derniere page
if (isset($HTTP_POST_VARS["finX"]))
{
    $x = $tot - $nb_post_par_page ;
}

// Page XXX
if (isset($HTTP_POST_VARS["direct_validX"]) && is_integer($HTTP_POST_VARS["directX"]))
{
    $x = $HTTP_POST_VARS["directX"] ;
}

            echo "<input type='hidden' name='id_repondre' value='" . htmlentities($HTTP_GET_VARS["id"]) . "'><input type='hidden' name='titre_repondre' value='" . htmlentities($HTTP_GET_VARS["titre"]) . "'>" ;

if (isset($HTTP_POST_VARS["id_repondre"]))
{
    $HTTP_GET_VARS["id"] = urlencode($HTTP_POST_VARS["id_repondre"]) ;
    $HTTP_GET_VARS["titre"] = urlencode($HTTP_POST_VARS["titre_repondre"]) ;
}
/*
 * Enregistre le nouveau sujet dans la base de données
 */
if (isset($HTTP_POST_VARS['poster']))
{
    include("include/enregistrer-sujet.php") ;
}

/*
 * Enregistre la réponse
 */
if (isset($HTTP_POST_VARS["quest_reponse"]))
{
    include("include/enregistrer-reponse.php") ;
}

// Si la variable par forumaire post est défini
if (isset($HTTP_POST_VARS["rechercher"]))
{
    $HTTP_GET_VARS["rechercher"] = 1 ;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?php echo $titre_forum ; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<LINK href="<?php echo $url_site . "themes/" . $theme ; ?>/style.css" type=text/css rel="STYLESHEET">
</head>

<body>
<?php  echo "<form action='" . $url_site . $SCRIPTNAME . "?sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "&x=$x&y=$y' method='post'>" ; ?>
<table width="95%" border="0" cellspacing="0" cellpadding="0" class="body" align="center">
  <tr>
    <td>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="menu">
        <tr class="menu">
          <td width="1" height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
          <td height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
          <td width="1" height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td class="menu" width="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
          <td>
            <table width="100%" border="0" cellspacing="6" cellpadding="0">
              <tr>
                <td nowrap><a href="<?php echo $url_site . $SCRIPTNAME ; ?>" class="menu"><?php echo $titre_forum ; ?></a></td>
                <td width="100%" align="right">
                <?php
                    echo "<a class='menu' href='" . $url_site . $SCRIPTNAME .  "?aide=1&x=" . urlencode($x) . "&sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "'>" ;
                    echo "<img src='" . $url_site . "themes/$theme/images/aide.gif' alt='aide' border='0' align='absmiddle'> " ;
                    echo "Aide</a>&nbsp;&nbsp;<a class='menu' href='" . $url_site . $SCRIPTNAME . "?rechercher=1&x=" . urlencode($x) . "&sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "'>" ;
                    echo "<img src='" . $url_site . "themes/$theme/images/search.gif' alt='Recherche' border='0' align='absmiddle'>" ;
                    echo " Rechercher</a>" ;?>
                </td>
              </tr>
            </table>
          </td>
          <td class="menu" width="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
        </tr>
        <tr class="menu">
          <td width="1" height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
          <td><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
          <td width="1" height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
        </tr>
      </table>
      <br>
      <b><a href="<?php echo $url_site . $SCRIPTNAME . "?x=" . urlencode($x) . "&sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] ; ?>">Forum</a> -
      <?php
      if (isset($HTTP_GET_VARS["licence"]))
      {
          echo "Licence de NouveauMonoForum" ;
      }
      else if (isset($HTTP_GET_VARS["aide"]))
      {
          echo "Aide" ;
      }
      else if (isset($HTTP_GET_VARS["rechercher"]))
      {
          echo "Rechercher" ;
      }
      // Répond au sujet en cours
      else if (isset($HTTP_GET_VARS["repondre"]))
      {
          echo "R&eacute;ponse" ;
      }
      // affiche le sujet demandé
      else if (isset($HTTP_GET_VARS["voirsujet"]))
      {
          echo "<a href='" . $url_site . $SCRIPTNAME .  "?repondre=1&id=" . $HTTP_GET_VARS["id"] . "&titre=" . urlencode(stripslashes($HTTP_GET_VARS["titre"])) . "&x=$x'>R&eacute;pondre au sujet</a>" ;
      }
      // Affiche quon ajoute un message
      else if (isset($HTTP_GET_VARS['newpost']))
      {
          echo "Ajout d'un message" ;
      }
      else
      // Affiche le lien pour ajouter un message
      {
          echo "<a href='" . $url_site . $SCRIPTNAME .  "?newpost=1&x=" . urlencode($x) . "&sens=" . $HTTP_POST_VARS["sens"] . "&classement=" . $HTTP_POST_VARS["classement"] . "'>Nouveau sujet</a>" ;
      }
      ?></b><br>
      <br>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" class="listemessage">
        <tr class="listemessage">
          <td width="1" height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
          <td height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
          <td width="1" height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
        </tr>
        <tr>
          <td class="listemessage" width="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
          <td>

            <?php
            /*
             * Rechercher
             */
            if (isset($HTTP_GET_VARS["licence"]))
            {
                include("include/gpl.php") ;
            }
            /*
             * Aide
             */
            else if (isset($HTTP_GET_VARS["aide"]))
            {
                include("include/aide.php") ;
            }
            /*
             * Rechercher
             */
            else if (isset($HTTP_GET_VARS["rechercher"]))
            {
                include("include/rechercher.php") ;
            }
            /*
             * lister le sujet et ses réponses
             */
            else if (isset($HTTP_GET_VARS["voirsujet"]))
            {
                include("include/voirsujet.php") ;
            }
            /*
             * Affiche le formulaire pour ajouter un message
             * OU
             * Affiche le formulaire pour répondre
             */
            else if (isset($HTTP_GET_VARS['newpost']) || isset($HTTP_GET_VARS["repondre"]))
            {
                include("include/ajouter-sujet.php") ;
            }
            /*
             * Affiche la liste des messages
             */
            else
            {
                include("include/lister-sujet.php") ;
            }
            ?>

          </td>
          <td class="listemessage" width="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
        </tr>
        <tr class="listemessage">
          <td width="1" height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
          <td><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
          <td width="1" height="1"><img alt="" scr="<?php echo $url_site ; ?>images/pixel-vide.gif" width="1" height="1"></td>
        </tr>
      </table><br>
      <?php
      // Affiche les icônes si on liste les messages
      if (!isset($HTTP_GET_VARS["repondre"]) && !isset($HTTP_GET_VARS['newpost'])
          && !isset($HTTP_GET_VARS["rechercher"]) && !isset($HTTP_GET_VARS["aide"])
          && !isset($HTTP_GET_VARS["licence"]))
      {
      ?>
      <img src="<?php echo $url_site . "themes/" . $theme ; ?>/images/message-nouveau.gif" alt=""> Nouveau message<br>
      <img src="<?php echo $url_site . "themes/" . $theme ; ?>/images/message.gif" alt=""> Message ayant une ou plusieurs r&eacute;ponse<br>
      <img src="<?php echo $url_site . "themes/" . $theme ; ?>/images/message-hot.gif" alt=""> Message populaire</td>
      <?php
      }
      ?>
  </tr>
</table>
</form>
<br>
Powered by <a href="http://php4php.free.fr/nfaq/" target="_blank">NouveauMonoForum</a> v1.4 &copy; 2003 Bubule. <a href="<?php echo $url_site . $SCRIPTNAME ; ?>?licence=1">Licence</a>.
</body>
</html>