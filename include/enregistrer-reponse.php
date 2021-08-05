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

        $id = gpcAddSlashes($HTTP_POST_VARS['id']) ;
        $auteur = gpcAddSlashes($HTTP_POST_VARS['auteur']) ;
        $adresse = gpcAddSlashes($HTTP_POST_VARS['adresse']) ;
        $texte = gpcAddSlashes($HTTP_POST_VARS['texte']) ;
        $envoi_mel = gpcAddSlashes($HTTP_POST_VARS['envoi_mel']) ;

        if (!empty($texte)&& !empty($auteur))
        {
            if ($envoi_mel)
            {
                $envoi = "1" ;
            }
            else
            {
                $envoi = "0" ;
            }

            $date = date("d-m-Y") ;
            @mysql_query("INSERT INTO " . $prefixe_de_table . "reponse (date_reelle, id, date, auteur, mail, texte, envoi) VALUES (now(), '$id', '$date', '$auteur', '$adresse', '$texte', '$envoi')") ;

            // Préparer l'e-mail
            // lit le fichier
            $contenuEmail = @file("include/email.php") ;

            // configure les variables
            $variables = array("TEXTE" => $texte,
                               "AUTEUR" => $auteur,
                               "QUESTION" => $row["sujet"],
                               "SITE_WEB" => $titre_forum) ;

            // sujet de l'e-mail
            $subjectEmail = remplacerVariables($contenuEmail[1], $variables) ;

            $nbLigne = count($contenuEmail) - 1 ;

            $texte = "" ;

            for ($i = 2; $i < $nbLigne; $i++)
            {
                $texte .= $contenuEmail[$i] . "\n" ;
            }

            // texte de l'e-mail
            $texteEmail = remplacerVariables($texte, $variables) ;

            // Régarde s'il y a un retour par e-mail
            $res = @mysql_query("SELECT envoi, mail, sujet FROM " . $prefixe_de_table . "question WHERE id='$id'") ;
            $row = @mysql_fetch_array($res) ;

            if (($row["envoi"] == "1") && !empty($row["mail"]) && $can_send_email)
            {
                if (ereg("^@.+\$", $row["mail"]))
                {
                    $email = substr($row["mail"], 1) ;

                    // Masque l'adresse e-mail dans le message
                    $adresse = "" ;
                }
                else
                {
                    $email = $row["mail"] ;
                }

                // sujet de l'e-mail
                $texteEmailAdr = remplacerVariables($texteEmail, array("ADRESSE_AUTEUR" => $adresse)) ;

                $ok = @mail($email, $subjectEmail, $texteEmailAdr) ;
            }

            // envoi un e-mail a tout les gens voulu
            $q = @mysql_query("SELECT mail FROM " . $prefixe_de_table . "reponse WHERE envoi='1' AND id='$id'") ;

            while($row = @mysql_fetch_array($q))
            {
                if (ereg("^@.+\$", $row["mail"]))
                {
                    $email = substr($row["mail"], 1) ;
                    $adresse = "" ;
                }
                else
                {
                    $adresse = $row["mail"] ;
                    $email = $row["mail"] ;
                }

                // sujet de l'e-mail
                $texteEmailAdr = remplacerVariables($texteEmail, array("ADRESSE_AUTEUR" => $adresse)) ;
                $ok = @mail($email, $subjectEmail, $texteEmailAdr) ;
            }

            $date = date("d-m-Y") ;

            // Rend invisible l'adresse e-mail si nécessaire
            if (isset($HTTP_POST_VARS["invisible"]))
            {
                $adresse = "@" . $adresse ;
            }


        }
        else
        {
            $messageErreur = "Il vous faut entrer un nom d'auteur et un texte !" ;
            // Créer la variable pour que le forulaire réapparaisse.
            $HTTP_GET_VARS["titre"] = urlencode(gpcDeleteSlashes($HTTP_POST_VARS['titre'])) ;
        }

        $HTTP_GET_VARS['voirsujet'] = 1 ;
        $HTTP_GET_VARS["id"] = urlencode($id) ;

?>