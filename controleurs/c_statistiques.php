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
$leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_SPECIAL_CHARS);
$mois = getMois(date('d/m/Y'));
$lesMois = getDerniers12Mois($mois);
$moisSelectionne = $leMois;
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);

switch ($action){
    case 'selectionnerMois':
    include 'vues/v_ficheMoisStat.php';
    break;
    case 'statistiquesVisiteurs':
        $nbVisiteurs = $pdo->getmoisFicheFrais($moisSelectionne);
        $plusGrosRemboursement = $pdo->getMaxRemboursementPourMois($moisSelectionne);
        $montantTotalValide = $pdo->getTotalValidePourMois($moisSelectionne);
        include 'vues/v_statistiques.php';
        break;
        }
?>