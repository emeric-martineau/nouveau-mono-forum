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

        echo "<br>Suppression des fichiers d'installations...<br>" ;
        // Constitution de la liste des fichiers
        $handle = opendir(".") ;
        $erreur = false ;

        if ($handle)
        {
            while ($file = readdir($handle))
            {
                if (!ereg("^\.", $file))
                {
                    echo "Suppression du fichier $file : " ;
                    $erreur = unlink($file) ;

                    if ($erreur)
                    {
                        echo "OK<br>" ;
                    }
                    else
                    {
                        echo "<font color='red'><b>ERREUR</b></font><br>"  ;
                    }
                }
            }
        }

        if (!$erreur)
        {
            echo "<font color='red'><b>ATTENTION</b> ! Certains fichiers d'installation n'ont pas &eacute;t&eacute; supprim&eacute;s ! Vous devez les supprimer &agrave; la main.</font>" ;
        }
?>