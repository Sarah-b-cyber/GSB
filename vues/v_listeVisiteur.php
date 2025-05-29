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
        <h3>Sélectionner un visiteur :</h3>
        <form action="index.php?uc=validerFrais&action=gererfichefrais" method="post" role="form">
            <div class="form-group">
                <label for="lstVisiteurs" accesskey="n">Visiteur :</label>
                <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                    <?php
                    foreach ($lesVisiteurs as $unVisiteur) {
                        $idVisiteur = $unVisiteur['id'];
                        $nomVisiteur = $unVisiteur['nom'];
                        $prenomVisiteur = $unVisiteur['prenom'];

                        if (isset($visiteurASelectionner) && $idVisiteur == $visiteurASelectionner) {
                            echo "<option selected value=\"$idVisiteur\">$nomVisiteur $prenomVisiteur</option>";
                        } else {
                            echo "<option value=\"$idVisiteur\">$nomVisiteur $prenomVisiteur</option>";
                        }
                    }
                    ?>
                </select>
            </div>
    </div>
<div class="row">
    <div class="col-md-6">
        <h3>Sélectionner un mois :</h3>
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
            <input name="ok" type="submit" value="Valider" class="btn btn-success" role="button">
            <input name="visteurrestant" type="submit" value="Voir fiches restantes" class="btn btn-success" role="button"/>
 
       <!-- </form>
        <div id="popupVA" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.3);">
    <div style="background:white; padding:20px; width:300px; margin:100px auto; border:1px solid #ccc;">
        <h4>Visiteurs avec fiche VA</h4>
        <ul>
            <?php foreach ($visiteursVA as $visiteur): ?>
                <li><?php echo htmlspecialchars($visiteur['nom'] . ' ' . $visiteur['prenom']); ?></li>
            <?php endforeach; ?>
        </ul>
        <button onclick="document.getElementById('popupVA').style.display='none'">Fermer</button>
    </div>
</div>

<script>
window.onload = function() {
    document.getElementById('popupVA').style.display = 'block';
};
</script>

  Popup simple sans Bootstrap -->
<!--<div id="popupVA" style="
    display:none;
    position:fixed;
    top:0; left:0; width:100vw; height:100vh;
    background:rgba(0,0,0,0.4);
    z-index:1000;
">
    <div style="
        background:#fff;
        border-radius:10px;
        box-shadow:0 4px 16px rgba(0,0,0,0.3);
        padding:30px 40px;
        max-width:400px;
        margin:10vh auto 0 auto;
        position:relative;
        top:10vh;
    ">
        <h4 style="margin-top:0;">Visiteurs avec fiche VA</h4>
        <ul>
            <?php foreach ($visiteursVA as $visiteur): ?>
                <li><?php echo htmlspecialchars($visiteur['nom'] . ' ' . $visiteur['prenom']); ?></li>
            <?php endforeach; ?>
        </ul>
        <button style="
            background:#007bff; color:#fff; border:none; border-radius:5px;
            padding:8px 16px; margin-top:15px; cursor:pointer;
        " onclick="document.getElementById('popupVA').style.display='none'">Fermer</button>
    </div>
</div>
<script>
window.onload = function() {
    document.getElementById('popupVA').style.display = 'block';
};-->
</script>




