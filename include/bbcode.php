<?php // G�n�r� le 05/02/2004 � 19:40:14
/* V�rifie que le fichier n'est pas affich� seul */
if ( !defined('IN_NMF') ) { die('Restricted access'); }
$bbcode[] = array("[b]", "<b>", "Balise d'ouverture du texte en gras") ;
$bbcode[] = array("[/b]", "</b>", "Balise de fermeture du texte en gras") ;
$bbcode[] = array("[i]", "<i>", "Balise d'ouverture du texte en italique") ;
$bbcode[] = array("[/i]", "</i>", "Balise de fermeture du texte en italique") ;
$bbcode[] = array("[u]", "<u>", "Balise d'ouverture du texte en soulign�") ;
$bbcode[] = array("[/u]", "</u>", "Balise de fermeture du texte en soulign�") ;
$bbcode[] = array("[p]", "<p>", "Balise d'ouverture d'un paragraphe") ;
$bbcode[] = array("[/p]", "</p>", "Balise de fermeture d'un paragraphe") ;
$bbcode[] = array("[br]", "<br>", "Balise de saut de ligne") ;
$bbcode[] = array("[espace]", "&nbsp;", "Balise ins�rant un espace obligatoire") ;
$bbcode[] = array("[code]", "<pre>", "Balise d'ouverture d'un texte type code source") ;
$bbcode[] = array("[/code]", "</pre>", "Balise de fermeture d'un texte type code source") ;
?>