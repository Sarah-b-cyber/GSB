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

$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date('d/m/Y'));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_URL);

switch ($action) {

case 'saisirFrais':
    if ($pdo->estPremierFraisMois($idVisiteur, $mois)) {
        $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
    }
    break;

 case 'validerMajFraisForfait':
        $lesFrais = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    
        if (!isset($lesFrais['lesFrais']) || !is_array($lesFrais['lesFrais'])) {
            ajouterErreur('Erreur : Données des frais invalides.');
            include 'vues/v_erreurs.php';
            exit();
        }
        
        $lesFrais = $lesFrais['lesFrais'];
        
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        break;

case 'validerCreationFrais':
    $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_URL);
    $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_URL);
    $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
    $modePaiement = filter_input(INPUT_POST, 'modePaiement', FILTER_SANITIZE_SPECIAL_CHARS);
    valideInfosFrais($dateFrais, $libelle, $montant, $modePaiement);
    if (nbErreurs() != 0) {
        include 'vues/v_erreurs.php';
    } else {
        $pdo->creeNouveauFraisHorsForfaitV(
            $idVisiteur,
            $mois,
            $libelle,
            $dateFrais,
            $montant,
            $modePaiement
        );
    }
    break;
    
case 'supprimerFrais':
    $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_URL);
    $pdo->supprimerFraisHorsForfait($idFrais);
    break;
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
require 'vues/v_listeFraisForfait.php';
require 'vues/v_listeFraisHorsForfait.php';
