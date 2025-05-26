<?php
/**
 * Gestion de l'affichage des frais
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

switch ($action){
    case 'selectionnerVisiteur':
        $lesVisiteurs = $pdo->getNomVisiteurVA();
        $lesMois = $pdo->getLesMoisDisponiblesVA() ;
        $lesCles = array_keys($lesVisiteurs);
        $visiteursASelectionner = $lesCles[0];
        include 'vues/v_listeVisiteurVA.php';
        break;

    case 'valider':
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_SPECIAL_CHARS);
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_SPECIAL_CHARS);
        $ficheValide = $pdo->getVisiteurMoisVA($leMois, $idVisiteur);
        //var_dump($ficheValide);
       if ($ficheValide) {
        $lesMois = $pdo->getLesMoisDisponibles($idVisiteur);
        //$moisASelectionner = $leMois;
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        include 'vues/v_suivrePaiement.php';
        } else {
            ajouterErreur('Pas de fiche de frais validée pour ce visiteur ce mois');
            include 'vues/v_erreurs.php';
            header("Refresh: 2;URL=index.php?uc=suivrePaiement&action=selectionnerVisiteur");
        }
        break;
    case 'remboursement':      
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_SPECIAL_CHARS);
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_SPECIAL_CHARS);
        $pdo->majEtatFicheFrais($idVisiteur, $leMois, 'RB');
        ajouterErreur('Les frais ont bien été remboursés.');
        header("Refresh: 2;URL=index.php?uc=suivrePaiement&action=selectionnerVisiteur");
        include 'vues/v_erreurs.php';
        break;
        
}