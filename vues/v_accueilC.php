<div id="accueil">
    <h2>
        Gestion des frais<small> - Comptable : 
            <?php 
            echo $_SESSION['prenom'] . ' ' . $_SESSION['nom']
            ?></small>
    </h2>
</div>
<div class="row">
    <div class="col-md-12" style="color:#0d1b2a">
        <div class="panel panel-primary" style="border-color:#1e3a5f">
            <div class="panel-heading" style="background-color: #1e3a5f; border-color: #1e3a5f">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-bookmark"></span>
                    Navigation
                </h3>
            </div>
            <div class="panel-body">
                <div class="row" style="display: flex; justify-content: center;">
                    <div class="col-xs-12 col-md-12">
                        <a href="index.php?uc=validerFrais&action=gererfichefrais"
                           class="btn btn-success btn-lg" role="button" style="background-color: #2a6f97; color: white; border-color: #2a6f97;">
                            <span class="glyphicon glyphicon-pencil"></span>
                            <br>Valider la fiche de frais</a>
                        <a href="index.php?uc=suivrePaiement&action=selectionnerVisiteur"
                           class="btn btn-primary btn-lg" role="button" style="background-color: #468faf; color: white; border-color: #468faf;">
                            <span class="glyphicon glyphicon-list-alt" style="color:white"></span>
                            <br>Suivre le paiment des fiches de frais</a>
                        <a href="index.php?uc=ficheMois&action=selectionnerMois"
                           class="btn btn-success btn-lg" role="button" style="background-color: #2a6f97; color: white; border-color: #2a6f97;">
                            <span class="glyphicon glyphicon-pencil"></span>
                            <br>Voir les fiches de frais /mois</a>
                        <a href="index.php?uc=statistiques&action=selectionnerMois"
                        class="btn btn-success btn-lg" role="button" style="background-color: #468faf; color: white; border-color: #468faf;">
                               <span class="glyphicon glyphicon-pencil"></span>
                            <br>Voir statistiques</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
