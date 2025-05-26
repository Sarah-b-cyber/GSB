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
 $idFrais = filter_input(INPUT_POST, 'idfrais', FILTER_SANITIZE_SPECIAL_CHARS);
 $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_SPECIAL_CHARS);
 $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
 $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_SPECIAL_CHARS);
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
            $lesFraisForfait=$pdo->getLesFraisForfait($idVisiteur, $mois);
            ajouterErreur('Les frais forfait ont bien ete modifié');
            var_dump($lesFrais);
            include 'vues/v_erreurs.php';
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");

        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        break;

   case 'majHorsForfait':
         $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
        if (isset($_POST['corrigerFHF'])){

            $pdo->majFraisHorsForfait($idVisiteur, $leMois, $libelle, $date, $montant, $idFrais);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
            ajouterErreur('Les frais hors forfait ont bien ete modifié');
            include 'vues/v_erreurs.php';
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");

        }elseif (isset($_POST['supprimerFHF'])){

            $pdo->supprimerFraisHorsForfait($idFrais);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
            ajouterErreur('Les frais hors forfait ont bien ete supprimé');
            include 'vues/v_erreurs.php';
            header("Refresh:2 ; URL= index.php?uc=validerFrais&action=selectionnerVisiteur");

        }elseif (isset($_POST['reporterFHF'])){

            $libelle2 = "Frais refusé : " . $libelle;
            $pdo->majFraisHorsForfait($idVisiteur, $mois, $libelle2, $date, $montant, $idFrais);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
            $moisSuivant = getMoisSuivant($mois);
            var_dump($idVisiteur, $moisSuivant, $libelle, $date, $montant);
            //$mois = $moisSuivant;
            $pdo->creeNouveauFraisHorsForfaitV($idVisiteur, $moisSuivant, $libelle, $date, $montant);
            $pdo->majFraisHorsForfait($idVisiteur, $mois, $libelle, $date, $montant, $idFrais);
            ajouterErreur("Le frais hors forfait a bien été reporté");
            include 'vues/v_erreurs.php';
            include 'vues/v_validerFicheFrais.php';
        }
        case 'validerMontant':
            var_dump($idVisiteur, $leMois);
            $totalFF= $pdo->calculerFF($idVisiteur, $leMois);
            $totalFF = [0][0];
            $totalFHF = $pdo->calculerFHF($idVisiteur, $leMois);
            $totalFHF = [0][0];
            var_dump($totalFF, $totalFHF);
            $total = $totalFF + $totalFHF;

            $pdo->totalValide($idVisiteur, $leMois, $total);
            
            $pdo->majEtatFicheFrais($idVisiteur, $leMois, 'VA');
    


        break;
 }

 ?>