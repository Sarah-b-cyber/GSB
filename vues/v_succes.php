<?php
/**
 * Vue Succès
 *
 * Affiche un message de succès après une opération réussie.
 */
?>
<div class="alert alert-success" style="margin:30px auto; max-width:500px;">
    <strong>Succès :</strong> <?php echo isset($messageSucces) ? htmlspecialchars($messageSucces) : 'Opération réussie.'; ?>
</div>