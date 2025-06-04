<?php
/**
 * Gestion des mots de passe
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

$type = null;
$login = null;
if (isset($_SESSION['idVisiteur'])) {
    $type = 'visiteur';
    $id = $_SESSION['idVisiteur'];
    $infos = $pdo->getInfosVisiteurById($id);
    $login = $infos['login'];
} elseif (isset($_SESSION['idComptable'])) {
    $type = 'comptable';
    $id = $_SESSION['idComptable'];
    $infos = $pdo->getInfosComptableById($id);
    $login = $infos['login'];
}

switch ($action) {
    case 'demandeNouveauMdp':
        // Affiche la vue pour saisir un nouveau mot de passe
        include 'vues/v_nouveauMdp.php';
        break;

    case 'valideNouveauMdp':
        // Récupère le nouveau mot de passe saisi par l'utilisateur
        $mdp = filter_input(INPUT_POST, 'txtMdp', FILTER_UNSAFE_RAW);


        // Vérifie si le nouveau mot de passe respecte les critères
        if (!verifPassword($mdp)) {
            ajouterErreur('Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.');
            include 'vues/v_erreurs.php';
            include 'vues/v_nouveauMdp.php';
            exit();
        }

        // Hachage du mot de passe avant de le stocker
        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

        if ($type === 'visiteur') {
            $pdo->majMdpVisiteur($login, $mdpHash);
        } elseif ($type === 'comptable') {
            $pdo->majMdpComptable($login, $mdpHash);
        }
        $messageSucces = 'Votre mot de passe a bien été modifié.';
        include 'vues/v_succes.php';
        exit();

    default:
        // Par défaut, redirige vers la demande de nouveau mot de passe
        include 'vues/v_nouveauMdp.php';
        break;
}