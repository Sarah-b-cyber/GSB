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
 $mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_SPECIAL_CHARS);
 $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_DEFAULT);
 
 $mois = getMois(date('d/m/Y'));
 $listeVisiteur = $pdo-> getLesVisiteurs();
 $listeMois = getDerniers12Mois($mois);
 $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_URL);
 $mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_SPECIAL_CHARS);
 $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_DEFAULT);


switch ($action) {
    case 'selectionnerVisiteur':
        $lesCles = array_keys($listeVisiteur);
        $visiteurASelectionner = $lesCles[0];
        //$lesCles1 = array_keys($listeMois);
        $moisASelectionner = $lesCles1[0];
        include 'vues/v_listeVisiteur.php';
        break;

    case 'detailFicheFrais':
        /*
        $mois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_SPECIAL_CHARS);
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_DEFAULT);
        */

        if (!$pdo->verifFicheFrais($idVisiteur, $mois)) {
            ajouterErreur('Erreur : Aucune fiche de frais pour ce mois et ce visiteur sélectionné');
            include 'vues/v_erreurs.php';
        } else {
            // Récupérer les détails de la fiche de frais
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
            $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
            $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);

            // Extraction des informations
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $libEtat = $lesInfosFicheFrais['libEtat'];
            $montantValide = $lesInfosFicheFrais['montantValide'];
            $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
            $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);

            // Affichage de la fiche de frais
        
            include 'vues/v_etatfraisC.php';
        }
        break;

        case 'ModifierFraisForfait':
            if (isset($_POST['corrigerFraisForfait']) && $_POST['corrigerFraisForfait'] == "Corriger")
            {
                $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT ,FILTER_FORCE_ARRAY);
                $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
                //$quantite = filter_input(INPUT_POST, 'quantite', FILTER_VALIDATE_FLOAT);
                

            }elseif (isset($_POST['reinitialiserFraisForfait']) AND $_POST['reinitialiserFraisForfait'] == 'Réinitialiser'){

            }
        
}
?>
