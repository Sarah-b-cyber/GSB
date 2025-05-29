<?php
/**
 * Classe d'accès aux données.
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL - CNED <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

/**
 * Classe d'accès aux données.
 *
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoGsb qui contiendra l'unique instance de la classe
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Cheri Bibi - Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   Release: 1.0
 * @link      http://www.php.net/manual/fr/book.pdo.php PHP Data Objects sur php.net
 */

class PdoGsb
{
    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=gsb_frais';
    private static $user = 'root';
    private static $mdp = '';
    private static $monPdo;
    private static $monPdoGsb = null;
    
    /*private static $serveur = 'mysql:host=db5017915252.hosting-data.io';
    private static $bdd = 'dbs14262895';
    private static $user = 'dbu755842';
    private static $mdp = 'BenizriSarah1234';
    private static $monPdo;
    private static $monPdoGsb = null;*/


    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    private function __construct()
    {
        PdoGsb::$monPdo = new PDO(
            PdoGsb::$serveur . ';' . PdoGsb::$bdd,
            PdoGsb::$user,
            PdoGsb::$mdp
        );
        PdoGsb::$monPdo->query('SET CHARACTER SET utf8');
    }

    /**
     * Méthode destructeur appelée dès qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrêt.
     */
    public function __destruct()
    {
        PdoGsb::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     *
     * @return l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb()
    {
        if (PdoGsb::$monPdoGsb == null) {
            PdoGsb::$monPdoGsb = new PdoGsb();
        }
        return PdoGsb::$monPdoGsb;
    }

    /**
     * Retourne les informations d'un visiteur
     *
     * @param String $login Login du visiteur
     * @param String $mdp   Mot de passe du visiteur
     *
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getInfosVisiteur($login, $mdp) {
    $requetePrepare = PdoGsb::$monPdo->prepare(
        'SELECT id, nom, prenom, mdp FROM visiteur WHERE login = :unLogin'
    );
    $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
    $requetePrepare->execute();
    $utilisateur = $requetePrepare->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur && password_verify($mdp, $utilisateur['mdp'])) {
        unset($utilisateur['mdp']);
        return $utilisateur;
    }

    return false;
}

public function getInfosComptable($login, $mdp) {
    $requetePrepare = PdoGsb::$monPdo->prepare(
        'SELECT id, nom, prenom, mdp FROM comptable WHERE login = :unLogin'
    );
    $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
    $requetePrepare->execute();
    $comptable = $requetePrepare->fetch(PDO::FETCH_ASSOC);

    if ($comptable && password_verify($mdp, $comptable['mdp'])) {
        unset($comptable['mdp']);
        return $comptable;
    }

    return false;
}
    /**
     * Retourne la liste des  visiteurs
     *
     * @param String $nom nom du visiteur
     * @param String $prenom  prenom du visiteur
     *
     *
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getLesVisiteurs()
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT visiteur.id AS id, visiteur.nom AS nom, '
            . 'visiteur.prenom AS prenom '
            . 'FROM visiteur '
            . 'ORDER BY visiteur.nom asc'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }


    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments.
     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return tous les champs des lignes de frais hors forfait sous la forme
     * d'un tableau associatif
     */
    public function getLesFraisHorsForfait($idVisiteur, $leMois)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT * FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraishorsforfait.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        for ($i = 0; $i < count($lesLignes); $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return le nombre entier de justificatifs
     */
    public function getNbjustificatifs($idVisiteur, $leMois)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fichefrais.nbjustificatifs as nb FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
       return ($laLigne && isset($laLigne['nb'])) ? $laLigne['nb'] : 0;
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernées par les deux arguments
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return l'id, le libelle et la quantité sous la forme d'un tableau
     * associatif
     */
    public function getLesFraisForfait($idVisiteur, $leMois)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fraisforfait.id as idfrais, '
            . 'fraisforfait.libelle as libelle, '
            . 'lignefraisforfait.quantite as quantite '
            . 'FROM lignefraisforfait '
            . 'INNER JOIN fraisforfait '
            . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
            . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraisforfait.mois = :unMois '
            . 'ORDER BY lignefraisforfait.idfraisforfait'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne tous les id de la table FraisForfait
     *
     * @return un tableau associatif
     */
    public function getLesIdFrais()
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fraisforfait.id as idfrais '
            . 'FROM fraisforfait ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Met à jour la table ligneFraisForfait
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais)
    {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE lignefraisforfait '
                . 'SET lignefraisforfait.quantite = :uneQte '
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'AND lignefraisforfait.idfraisforfait = :idFrais'
            );
            $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }

    /**
     * Met à jour la table ligneFraisHorsForfait
     * Met à jour la table ligneFraisHorsForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFraisHorsForfait   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisHorsForfait($idVisiteur, $leMois, $libelle, $date, $montant, $unIdFrais) {
        //$dateFr = dateFrancaisVersAnglais($date);
         $dateFormatee = DateTime::createFromFormat('d/m/Y', $date);
        if ($dateFormatee) {
            $dateSql = $dateFormatee->format('Y-m-d');
        } else {
            $dateSql = null; // ou gérer une erreur
        }

        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.libelle = :unLibelle, '
                . ' lignefraishorsforfait.date = :uneDate ,'
                . ' lignefraishorsforfait.montant = :unMontant '
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois '
                . 'AND lignefraishorsforfait.id = :idFrais'
        );

        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDate', $dateSql, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    /**
     * Met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné
     *
     * @param String  $idVisiteur      ID du visiteur
     * @param String  $mois            Mois sous la forme aaaamm
     * @param Integer $nbJustificatifs Nombre de justificatifs
     *
     * @return null
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)
    {
        $requetePrepare = PdoGsB::$monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET nbjustificatifs = :unNbJustificatifs '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(
            ':unNbJustificatifs',
            $nbJustificatifs,
            PDO::PARAM_INT
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return vrai ou faux
     */
    public function estPremierFraisMois($idVisiteur, $mois)
    {
        $boolReturn = false;
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fichefrais.mois FROM fichefrais '
            . 'WHERE fichefrais.mois = :unMois '
            . 'AND fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return le mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT MAX(mois) as dernierMois '
            . 'FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnés
     *
     * Récupère le dernier mois en cours de traitement, met à 'CL' son champs
     * idEtat, crée une nouvelle fiche de frais avec un idEtat à 'CR' et crée
     * les lignes de frais forfait de quantités nulles
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return null
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois)
    {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'INSERT INTO fichefrais (idvisiteur,mois,nbJustificatifs,'
            . 'montantValide,dateModif,idEtat) '
            . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR')"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = PdoGsb::$monPdo->prepare(
                'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                . 'idFraisForfait,quantite) '
                . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(
                ':idFrais',
                $unIdFrais['idfrais'],
                PDO::PARAM_STR
            );
            $requetePrepare->execute();
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $libelle    Libellé du frais
     * @param String $date       Date du frais au format français jj//mm/aaaa
     * @param Float  $montant    Montant du frais
     *
     * @return null
     */
    public function creeNouveauFraisHorsForfait(
        $idVisiteur,
        $mois,
        $libelle,
        $date,
        $montant,
    ) {
        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'INSERT INTO lignefraishorsforfait '
            . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
            . ':unMontant) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    public function creeNouveauFraisHorsForfaitV(
    $idVisiteur,
    $mois,
    $libelle,
    $date,
    $montant,
    $modePaiement
) {
    // Vérifie si une fiche de frais existe déjà pour ce visiteur et ce mois
    $requeteVerif = PdoGSB::$monPdo->prepare(
        'SELECT COUNT(*) FROM fichefrais WHERE idvisiteur = :idVisiteur AND mois = :mois'
    );
    $requeteVerif->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
    $requeteVerif->bindParam(':mois', $mois, PDO::PARAM_STR);
    $requeteVerif->execute();
    $existe = $requeteVerif->fetchColumn();

    // Si la fiche n'existe pas, on la crée avec des valeurs par défaut
    if ($existe == 0) {
        $requeteInsertFiche = PdoGSB::$monPdo->prepare(
            'INSERT INTO fichefrais (idvisiteur, mois, nbjustificatifs, montantvalide, datemodif, idetat)
             VALUES (:idVisiteur, :mois, 0, 0, NOW(), "CR")'
        );
        $requeteInsertFiche->bindParam(':idVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requeteInsertFiche->bindParam(':mois', $mois, PDO::PARAM_STR);
        $requeteInsertFiche->execute();
    }

    // Ensuite on insère le frais hors forfait
    $dateFr = dateFrancaisVersAnglais($date);
    $requetePrepare = PdoGSB::$monPdo->prepare(
        'INSERT INTO lignefraishorsforfait
         VALUES (null, :unIdVisiteur, :unMois, :unLibelle, :uneDateFr, :unMontant, :unModePaiement)'
    );
    $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
    $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
    $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
    $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
    $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
    $requetePrepare->bindParam(':unModePaiement', $modePaiement, PDO::PARAM_STR);
    $requetePrepare->execute();
}


    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFrais ID du frais
     *
     * @return null
     */
    public function supprimerFraisHorsForfait($idFrais)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'DELETE FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fichefrais.mois AS mois FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois['$mois'] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }

    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return un tableau avec des champs de jointure entre une fiche de frais
     *         et la ligne d'état
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT ficheFrais.idEtat as idEtat, '
            . 'ficheFrais.dateModif as dateModif,'
            . 'ficheFrais.nbJustificatifs as nbJustificatifs, '
            . 'ficheFrais.montantValide as montantValide, '
            . 'etat.libelle as libEtat '
            . 'FROM fichefrais '
            . 'INNER JOIN Etat ON ficheFrais.idEtat = Etat.id '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }

    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return null
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'UPDATE ficheFrais '
            . 'SET idEtat = :unEtat, dateModif = now() '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
/**
     * Verifie la fiche frais
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return null
     */
    public function verifFicheFrais($idVisiteur, $mois)
{
    $requetePrepare = PdoGSB::$monPdo->prepare(
        'SELECT ficheFrais.mois 
         FROM ficheFrais 
         WHERE ficheFrais.mois = :unMois 
         AND ficheFrais.idVisiteur = :unIdVisiteur'
    );

    $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
    $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
    $requetePrepare->execute();

    return (bool) $requetePrepare->fetch(); // Retourne true si un résultat est trouvé, sinon false
}
    public function calculerFF($idVisiteur, $leMois) {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT SUM(lignefraisforfait.quantite*fraisforfait.montant)'
                . 'FROM lignefraisforfait join fraisforfait on(lignefraisforfait.idfraisforfait=fraisforfait.id)'
                . 'WHERE idvisiteur = :unIdVisiteur '
                . 'AND mois = :unMois '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->execute();

        return $requetePrepare->fetchAll();
    }

    public function calculerFHF($idVisiteur, $leMois) {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT SUM(lignefraishorsforfait.montant) '
                . 'FROM lignefraishorsforfait '
                . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois '
                . 'AND lignefraishorsforfait.libelle NOT IN (SELECT libelle '
                . 'FROM lignefraishorsforfait '
                . 'WHERE libelle like "REFUSER%")'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    public function totalValide($idVisiteur, $leMois, $total) {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE ficheFrais '
                . 'SET montantvalide=:unTotal  '
                . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $leMois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unTotal', $total, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    /**
     * Retourne le nom et prenom d'un visteur VA
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
     */
    public function getNomVisiteurVA() {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT DISTINCT (visiteur.id) AS id, visiteur.nom AS nom, visiteur.prenom AS prenom '
                . 'FROM visiteur JOIN fichefrais ON (visiteur.id= fichefrais.idvisiteur) '
                . 'WHERE fichefrais.idetat = "VA" '
                . 'ORDER BY nom '
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Verfie l'existence d'un mois et du visteur VA
     * @return l'id, le nom et le prénom 
     */
    public function getVisiteurMoisVA($leMois, $idVisiteur) {
    
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT idetat '
                . 'FROM  fichefrais ' 
                . "WHERE fichefrais.idvisiteur = '$idVisiteur' AND fichefrais.mois = '$leMois' AND fichefrais.idetat = 'VA' "
        );
        $requetePrepare->execute();
       return $requetePrepare->fetch();
    }
    /**
     * Retourne les mois pour lesquel un visiteur validé a une fiche de frais
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return un tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisDisponiblesVA() {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT DISTINCT (fichefrais.mois) AS mois FROM fichefrais '
                . 'WHERE idetat = "VA" '
                . 'ORDER BY fichefrais.mois desc '
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }
    /**
     * Calcule le nombre fiche de frais d'un visteur pour un mois
     * @return l'id, le nom et le prénom 
     */
    public function getmoisFicheFrais($moisSelectionne) {
        $requetePrepare = PdoGsb::$monPdo->prepare(
        'SELECT COUNT(DISTINCT idVisiteur) AS nb_visiteurs
            FROM fichefrais
            WHERE mois = :mois'
        );

    $requetePrepare->bindParam(':mois', $moisSelectionne, PDO::PARAM_STR);
    $requetePrepare->execute();
    $resultat = $requetePrepare->fetch();

    return $resultat['nb_visiteurs'];

     
    }
       /**
     * Retourne le nom et prenom d'un visteur CL
     * @return l'id, le nom et le prénom sous la forme d'un tableau associatif
     */
   public function getDetailsFichesFraisCL() {
    $requete = PdoGsb::$monPdo->prepare(
        'SELECT v.id AS id, v.nom AS nom, v.prenom AS prenom, 
                f.mois AS mois, f.datemodif AS datemodif, 
                f.montantvalide AS montantvalide
         FROM visiteur v
         JOIN fichefrais f ON v.id = f.idvisiteur
         WHERE f.idetat = "CL"
         ORDER BY v.nom, f.mois DESC'
    );
    $requete->execute();
    return $requete->fetchAll(PDO::FETCH_ASSOC);
}

   /* public function hacherMdp() {
    $requetePrepare = PdoGSB::$monPdo->prepare(
        'SELECT id, mdp FROM comptable'
    );
    $requetePrepare->execute();
    $utilisateurs = $requetePrepare->fetchAll(PDO::FETCH_ASSOC);

    foreach ($utilisateurs as $utilisateur) {
        $id = $utilisateur['id'];
        $mdp = $utilisateur['mdp'];

        // Hasher sans condition
        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        // Mise à jour en base
        $update = PdoGSB::$monPdo->prepare(
            'UPDATE comptable SET mdp = :hash WHERE id = :id'
        );
        $update->execute([
            'hash' => $hash,
            'id' => $id
        ]);

        echo "Mot de passe hashé pour l'utilisateur ID $id<br>";
    }
}*/



}


