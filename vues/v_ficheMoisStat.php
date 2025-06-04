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
<h2>Mes fiches de frais</h2>
<div class="row">
    <div class="col-md-6">
        <h3>Sélectionner un mois :</h3>
         <form action="index.php?uc=statistiques&action=statistiquesVisiteurs" method="post" role="form">
            <div class="form-group">
                <label for="lstMois" accesskey="n">Mois :</label>
                <select id="lstMois" name="lstMois" class="form-control">
                    <?php
                      foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = substr($mois, 0, 4);
                        $numMois = substr($mois, 4, 2);
                    if ($mois == $moisASelectionner) {
                        ?>
                        <option selected value="<?php echo $mois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?> </option>
                        <?php
                    } else {
                        ?>
                        <option value="<?php echo $mois ?>">
                            <?php echo $numMois . '/' . $numAnnee ?> </option>
                        <?php
                    }
                }
                
                ?>    
                </select>
            </div>
            </div> 
            <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                   role="button">
        </form>




    