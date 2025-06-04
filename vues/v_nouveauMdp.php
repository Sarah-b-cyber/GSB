<?php
/**
 * Vue Liste des mois
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
?>
<h2>Creer un nouveau mot de passe</h2>
<div class="row">
    <div class="col-md-6">
        <form action="index.php?uc=mdpreset&action=valideNouveauMdp" method="post" role="form">
            <input type="hidden" name="login" value="<?php echo htmlspecialchars($login); ?>">
            <div class="form-group">
                <label for="txtMdp" accesskey="n">Nouveau mot de passe :</label>
                <input type="password" id="txtMdp" name="txtMdp" class="form-control" required>
            </div>
            <input id="ok" type="submit" value="Valider" class="btn btn-success" role="button">
            <input id="annuler" type="reset" value="Effacer" class="btn btn-danger" role="button">
        </form>
    </div>
    <div class="col-md-6">
        <p>Veuillez saisir votre nouveau mot de passe. Il doit contenir au moins 8 caractères, dont au moins une majuscule, une minuscule, un chiffre et un caractère spécial.</p>
</div>
<div class="row">
    <div class="col-md-12">
        <p><a href="index.php?uc=connexion&action=demandeConnexion">Retour à la page de connexion</a></p>
    </div>
</div>