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

        $envoi_mel = gpcAddSlashes($HTTP_POST_VARS['envoi_mel']) ;
        $sujet = gpcAddSlashes($HTTP_POST_VARS['sujet']) ;
        $auteur = gpcAddSlashes($HTTP_POST_VARS['auteur']) ;
        $adresse = gpcAddSlashes($HTTP_POST_VARS['adresse']) ;
        $texte = gpcAddSlashes($HTTP_POST_VARS['texte']) ;

        if (!empty($texte) && !empty($sujet) && !empty($auteur))
        {
            if ($HTTP_POST_VARS['envoi_mel'])
            {
                $envoi = "1" ;
            }
            else
            {
                $envoi = "0" ;
            }

            $id = (uniqid('')) ;
            $id = substr($id, 4, 8) ;
            $date = date("d-m-Y") ;

            // Rend invisible l'adresse e-mail si nécessaire
            if (isset($HTTP_POST_VARS["invisible"]))
            {
                $adresse = "@" . $adresse ;
            }

            @mysql_query("INSERT INTO " . $prefixe_de_table . "question (date_reelle, id, date, sujet, auteur, mail, texte, envoi) VALUES (now(),'$id','$date','$sujet','$auteur','$adresse','$texte','$envoi')") ;

            $messageErreur = "" ;
        }
        else
        {
            $messageErreur = "Il vous faut entrer un titre, un nom d'auteur et un sujet !" ;
            // Créer la variable pour que le forMulaire réapparaisse.
            $HTTP_GET_VARS['newpost'] = 1 ;
        }

?>