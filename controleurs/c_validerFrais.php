<?php
/**
 * Gestion des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

 $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_URL);
 $lesFrais = filter_input(INPUT_GET, 'lesFrais', FILTER_SANITIZE_SPECIAL_CHARS);
 $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_SPECIAL_CHARS);
 $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_SPECIAL_CHARS);
 $lesFrais = filter_input(INPUT_POST,'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
 $lesVisiteurs =  $pdo->getLesVisiteurs();
 $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur,$leMois);
 $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
 $mois = getMois(date('d/m/Y'));
 $lesMois = getDerniers12Mois($mois);
 
 $visiteurASelectionner = $idVisiteur;
 $moisASelectionner = $leMois;

 switch ($action){
    case 'selectionnerVisiteur':
        $lesCles = array_keys($lesVisiteurs);
        $visiteursASelectionner = $lesCles[0];
        include 'vues/v_listeVisiteur.php';
        break;
        

    case 'validerFicheFrais':
        if(empty($lesFraisForfait) && (empty($lesFraisHorsForfait))){
            ajouterErreur("Aucune fiche de frais pour le visiteur et le mois choisi");
            include 'vues/v_erreurs.php';
            header("Refresh:3;URL=index.php?uc=validerFrais&action=selectionnerVisiteur");
        }else{
            $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
            include 'vues/v_validerFicheFrais.php';
   
        }
        break;

    case 'MajFraisForfait':
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        break;

    case 'majHorsForfait':
        if (isset($_POST['corrigerFHF'])){
            //pr corriger
        } 
        if (isset($_POST['supprimerFHF'])){
            //pr supprimer
        } 
        if (isset($_POST['reporterFHF'])){
            //pr reportere
        } 
        break;
 }




 ?>