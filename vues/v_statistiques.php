<?php
/**
 * Vue Staistiques Visiteurs
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
<h2>Statistiques pour le mois <?php echo htmlspecialchars( $numMois .'/'. $numAnnee); ?></h2>

<div class="panel panel-info" style="max-width:500px;margin:30px auto;">
    <div class="panel-heading"><strong>Statistiques du mois</strong></div>
    <div class="panel-body">
        <ul class="list-group">
            <li class="list-group-item">
                <strong>Nombre de visiteurs ayant saisi des frais :</strong>
                <?php echo (int)$nbVisiteurs; ?>
            </li>
            <li class="list-group-item">
                <strong>Plus gros score de remboursement :</strong>
                <?php echo number_format($plusGrosRemboursement, 2, ',', ' '); ?> €
            </li>
            <li class="list-group-item">
                <strong>Montant total validé :</strong>
                <?php echo number_format($montantTotalValide, 2, ',', ' '); ?> €
            </li>
        </ul>
    </div>
</div>