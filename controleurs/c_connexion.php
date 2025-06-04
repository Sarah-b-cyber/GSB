<?php
/**
 * Gestion de la connexion
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

require_once 'includes/fct.inc.php';

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_URL);
if (!$action) {
    $uc = 'demandeconnexion';
}

switch ($action) {
case 'demandeConnexion':
    include 'vues/v_connexion.php';
    break;
case 'valideConnexion':
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_SPECIAL_CHARS);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_SPECIAL_CHARS);
    $visiteur = $pdo->getInfosVisiteur($login, $mdp);
    $comptable = $pdo->getInfoscomptable($login, $mdp);
    var_dump($login, $mdp);

   

    if (!is_array($visiteur) && !is_array($comptable)) {
        ajouterErreur('Login ou mot de passe incorrect');
        include 'vues/v_erreurs.php';
        include 'vues/v_connexion.php';
    } else {
        if (is_array($visiteur)) {
              if (verifPassword($mdp)) {
                $id = $visiteur['id'];
                $nom = $visiteur['nom'];
                $prenom = $visiteur['prenom'];
                connecterV($id, $nom, $prenom);  
                header('Location: index.php');
                exit();
                  } else {
                // Mot de passe incorrect, proposer de créer un nouveau mot de passe
                include 'vues/v_nouveauMdp.php';
                exit();
            }
        } elseif (is_array($comptable)) {
               if (verifPassword($mdp)) {
                $id = $comptable['id'];
                $nom = $comptable['nom'];
                $prenom = $comptable['prenom'];
                connecterC($id, $nom, $prenom); 
                header('Location:index.php'); 
                exit();
            } else {
                // Mot de passe incorrect, proposer de créer un nouveau mot de passe
                include 'vues/v_nouveauMdp.php';
                exit();
            }
        }
    }
    break;
default:
    include 'vues/v_connexion.php';
    break;
}
 