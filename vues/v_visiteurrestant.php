<?php
/**
 * Vue État de Frais
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
<!DOCTYPE html>
<html lang="fr">
<div class="row" style="padding-bottom: 20px;">    
    <h2 style="color: orange;"> Fiche de Frais restante </h2>
<div class="panel panel-warning">
    <div class="panel-heading">Descriptif des fiches de frais- 
    <table class="table table-bordered table-responsive">
        <tr>
            <th>Visiteur</th>
            <th>Date</th>
            <th>Montant</th>              
        </tr>
        <?php
        foreach ($visiteursRestants as $fiche) { ?>
            <tr>
                <td><?php echo htmlspecialchars($fiche['nom'] . ' ' . $fiche['prenom']); ?></td>
                <td><?php echo htmlspecialchars($fiche['datemodif']); ?></td>
                <td><?php echo htmlspecialchars($fiche['montantvalide']); ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
</div>

