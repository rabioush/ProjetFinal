<?php

/**
 * extension pour extraire et modifier les données d'une base de données MySQL
 * @remark utilise le langage SQL pour soumettre les requêtes et des tableaux pour récupérer les données
 * @author Philippe Cosson <philippe.cosson@ac-grenoble.fr>
 * @copyright ©PCo2007-2014
 */
class BDMySQL {

    static private $_cnn = null;

    /**
     * définit les paramètres de connexion
     * @param string $unServeur le serveur MySQL
     * @param string $uneBase la base de données
     * @param string $unUtil l'utilisateur ('root' par défaut)
     * @param string $unMDP le mot de passe ('' par défaut)
     * @example BDMySQL::connecter('localhost', 'mrbs', 'root', 'secret');
     */
    static public function connecter($unServeur, $uneBase, $unUtil = 'root', $unMDP = '') {
        @self::$_cnn = mysql_connect($unServeur, $unUtil, $unMDP);
        if (!self::$_cnn) {
            self::gererErreurCnn();
        }
        mysql_query("SET CHARACTER SET utf8", self::$_cnn);
        @$base = mysql_select_db($uneBase, self::$_cnn);
        if (!$base) {
            self::gererErreurCnn();
        }
    }

    /**
     * gère les erreurs de connexion à la BD
     */
    static private function gererErreurCnn() {
        $errNo = mysql_errno();
        $errMySQL = utf8_encode(mysql_error());
        switch ($errNo) {
            case 1044: $msg = "Utilisateur incorrect !";
                break;
            case 1045: $msg = "Mot de passe erroné !";
                break;
            case 1049: $msg = "Base de données inconnue !";
                break;
            case 2002: $msg = "Serveur inconnu !";
                break;
            default: $msg = "Connexion non réalisée !";
                $errMySQL = "...";
                break;
        }
        $errMySQL .= " [{$errNo}]";
        self::alerter('Erreur de connexion à la base de données', $msg, $errMySQL, 'Utiliser la méthode BDMySQL::connecter(...) pour corriger les paramètres de connexion');
    }

    /**
     * vérifie l'existance d'une connexion
     * @return object connexion
     */
    static private function getCnn() {
        if (self::$_cnn == null) {
            self::gererErreurCnn();
        }
        return self::$_cnn;
    }

    /**
     * protège une chaîne SQL
     * @param string $uneChaine la chaîne à protéger
     * @return string la chaîne protégée
     */
    static public function proteger($uneChaine) {
        if (get_magic_quotes_gpc()) {
            $uneChaine = stripslashes($uneChaine);
        }
        $uneChaine = "'" . mysql_real_escape_string($uneChaine, self::getCnn()) . "'";
        return $uneChaine;
    }

    /**
     * extrait des lignes d'enregistrement (N lignes par N colonnes)
     * @param string $unSQLSelect requête SQL à exécuter
     * @param boolean $estVisible visualisation du résultat (par défaut FALSE)
     * @remark la visualisation permet de vérifier le résultat dans une phase de débogage seulement ...
     * @return array[] tableau associatif [noLigne,nomColonne] représentant les lignes extraites ou null si aucune !
     */
    static public function extraireNxN($unSQLSelect, $estVisible = false) {
        $result = array();
        $je = mysql_query($unSQLSelect, self::getCnn());
        self::gererErreur($unSQLSelect);
        while ($ligne = mysql_fetch_assoc($je)) {
            $result[] = $ligne;
        }
        mysql_free_result($je);
        if (count($result) == 0) {
            $result = null;
        }
        if ($estVisible) {
            self::afficherResultat($result, $unSQLSelect);
        }
        return $result;
    }

    /**
     * extrait une ligne d'enregistrement (1 ligne par N colonnes)
     * @param string $unSQLSelect requête SQL à exécuter
     * @param boolean $estVisible visualisation du résultat (par défaut FALSE)
     * @remark la visualisation permet de vérifier le résultat dans une phase de débogage seulement ...
     * @return array[] tableau associatif [nomColonne] représentant la ligne extraite ou null si aucune !
     */
    static public function extraire1xN($unSQLSelect, $estVisible = false) {
        $result = self::extraireNxN($unSQLSelect);
        if (isset($result[0])) {
            $result = $result[0];
        }
        if ($estVisible) {
            self::afficherResultat($result, $unSQLSelect);
        }
        return $result;
    }

    /**
     * extrait un champ unique (1 ligne par 1 colonne)
     * @param string $unSQLSelect requête SQL à exécuter
     * @param boolean $estVisible visualisation du résultat (par défaut FALSE)
     * @remark la visualisation permet de vérifier le résultat dans une phase de débogage seulement ...
     * @return string variable représentant un champ d'un enregistrement ou null si aucun !
     */
    static public function extraire1x1($unSQLSelect, $estVisible = false) {
        $result = self::extraireNxN($unSQLSelect);
        if ($result) {
            $cles = array_keys($result[0]);
            $result = $result[0][$cles[0]];
        }
        if ($estVisible) {
            self::afficherResultat($result, $unSQLSelect);
        }
        return $result;
    }

    /**
     * exécute une requête 'action' (INSERT, UPDATE, DELETE)
     * @param $unSQLAction requête SQL à exécuter
     * @return boolean true si OK, sinon false en cas de problème ...
     */
    static public function executerAction($unSQLAction) {
        $result = mysql_query($unSQLAction, self::getCnn());
        self::gererErreur($unSQLAction);
        return $result;
    }

    /**
     * gère les erreurs SQL éventuelles
     * @param $unSQL
     * @return Exception
     */
    static private function gererErreur($unSQL) {
        if (mysql_error(self::getCnn())) {
            $traces = debug_backtrace();
            $trace = $traces[count($traces)-1];
            $sol = "Corriger votre requête dans :<br/>";
            $sol .= "• fichier «: {$trace['file']}»<br/>";
            $sol .= "• ligne {$trace['line']}<br/>";
            $sol .= "• méthode «" . __CLASS__ . "::{$trace['function']}»";
            $err = utf8_encode(mysql_error(self::getCnn()));
            self::alerter('Erreur SQL', $unSQL, $err, $sol);
        }
    }

    /**
     * affiche le résultat d'une opération d'extraction
     * @param array|scalar $unResultat
     * @param string $uneLegende
     */
    static private function afficherResultat($unResultat, $uneLegende = "") {
        $event = 'onmouseover="basculeTitreValeur(this)" onmouseout="basculeTitreValeur(this)"';
        echo <<<HTML
    <style>
        table.debug { margin-top: 1em; border-collapse: collapse; }
        table.debug th, table.debug td { border: solid #444 1px; padding: 10px; font-family: Verdana, Tahoma, Helvetica, Arial; }
        table.debug th { background-color: silver; color: #444; font-family: monospace; color: white; }
        table.debug td { cursor : help; }
        table.debug td.scalaire { border: dashed silver 1px; }
    </style>
    <script type="text/javascript">
     function basculeTitreValeur(unElt) {
         var tempo = unElt.innerHTML;
         unElt.innerHTML = unElt.title;
         unElt.title = tempo;
         if (unElt.style.backgroundColor == "") {
            unElt.style.backgroundColor= "gold";         
         }
         else {
            unElt.style.backgroundColor= "";
         }
     }
    </script>
    <table class="debug">
        <caption>$uneLegende</caption>

HTML;
        if (is_array($unResultat)) {
            if (count($unResultat) != count($unResultat, COUNT_RECURSIVE)) {
                self::afficherNxN($unResultat, $event); // tableau à 2 dimensions
            } else {
                self::afficher1xN($unResultat, $event); // tableau à 1 dimension
            }
        } else {
            if ($unResultat) { // valeur unique
                echo "<tr><td class=\"scalaire\" title=\"\$var\" {$event}>$unResultat</td></tr>\n";
            } else { // valeur null
                echo "<tr><td class=\"scalaire\" title=\"null\" {$event}></td></tr>\n";
            }
        }
        echo "</table>";
    }

    /**
     * affiche les lignes d'un tableau à 2 dimensions
     * @param array[][] $unResultat
     */
    static private function afficherNxN($unResultat, $unEvent) {
        $entete = true;
        $i = 0;
        foreach ($unResultat as $uneLigne) {
            $noms = "";
            $valeurs = "";
            foreach ($uneLigne as $nom => $valeur) {
                if ($entete) {
                    $noms .= "<th>{$nom}</th>\n";
                }
                $valeur = htmlentities($valeur, ENT_COMPAT, "utf-8");
                $valeurs .= "<td title=\"\$array[{$i}]['{$nom}']\" {$unEvent}>{$valeur}</td>\n";
            }
            if ($entete) {
                $entete = false;
                echo "<tr><th></th>{$noms}</tr>\n";
            }
            echo "  <tr><th>{$i}</th>{$valeurs}</tr>";
            $i++;
        }
    }

    /**
     * affiche les champs d'un tableau à 1 dimension
     * @param array[] $unResultat
     */
    static private function afficher1xN($unResultat, $unEvent) {
        foreach ($unResultat as $nom => $valeur) {
            $valeur = htmlentities($valeur, ENT_COMPAT, "utf-8");
            echo "<tr><th>$nom</th>\n";
            echo "<td title=\"\$array['$nom']\" {$unEvent}>{$valeur}</td></tr>\n";
        }
    }

    /**
     * affiche une alerte d'erreur et arrête l'exécution !
     * @param string $uneOrigine l'origine du problème
     * @param string $unMsgFr le message en français
     * @param string $unMsgEn le message d'erreur en anglais
     * @param string $uneSolution la solution
     */
    static private function alerter($uneOrigine, $unMsgFr, $unMsgEn, $uneSolution) {
        $classe = __CLASS__;
        echo <<<HTML
        <style>
            #bdmysql-titre {color: white;background-color: maroon;padding: 10px;}
            #bdmysql-code {background-color:silver;font-family: monospace;padding:10px;}
        </style>
        <p id='bdmysql-titre'><span style='font-size: 30px'>{$classe} </span>
            # {$uneOrigine}
        </p>     
        <h1 style='color:red'>/!\ {$unMsgFr}</h1>
        <p id='bdmysql-code'>{$unMsgEn}</p>
        <h2 style='color:green'>{$uneSolution}</h2>
HTML;
        die();
    }

}

?>
