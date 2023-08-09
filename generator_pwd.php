<?php
// Démarrer une session 
session_start();

// Initialiser les classes dès le départ
$_SESSION['none'] = "d-none";
$_SESSION['mobile-visible'] = "";                            
$_SESSION['error'];
$_SESSION['center'] = "j-center";

// margin-top: 0rem;
$_SESSION['top'] = "0";

// Utilisez une clé de session différente pour stocker les mots de passe générés
if (!isset($_SESSION['generated_passwords'])) {
    $_SESSION['generated_passwords'] = array();
}

// Gérer l'état des checkboxes 
$_SESSION["AZ"] = isset($_POST["AZ"]) ? "checked" : "";
$_SESSION["az"] = isset($_POST["az"]) ? "checked" : "";
$_SESSION["09"] = isset($_POST["09"]) ? "checked" : "";
$_SESSION["speciaux"] = isset($_POST["speciaux"]) ? "checked" : "";

// Si le boutton générer est pressé
if (isset($_POST["button_generate"])) {
    // Générer les mots de passe
    $_SESSION['generated_passwords'] = array();
    for ($i = 0; $i < 5; $i++) {
        $_SESSION['generated_passwords'][] = Password();
    }
    $password = $_SESSION['generated_passwords'][0]; // Prendre le premier mot de passe généré

    $_SESSION['none'] = "";
    // margin-top: 15rem;
    $_SESSION['top'] = "15";
    $_SESSION['mobile-visible'] ="mobile-visible";

    // Changer la classe en fonction de la longueur des mots de passe générés
    if (strlen($password) === 0) {
        $_SESSION['center'] = "j-center"; // Si des mots de passe ont été générés, changer la classe en "j-evenly"
    } else {
        $_SESSION['center'] = "j-evenly"; // Si aucun mot de passe n'a été généré, laisser la classe à "j-center"
    }

} else {
    $password = ""; // Laisser le mot de passe vide si le bouton de génération n'a pas été cliqué
    $passwords = array(); // Vider le tableau des mots de passe
}

// Si le boutton Réinitaliser switchs est pressé
if (isset($_POST["reset_switches_passwords"])) {
    // Réinitialiser les valeurs des cases à cocher dans la session
    $_SESSION["AZ"] = "";
    $_SESSION["az"] = "";
    $_SESSION["09"] = "";
    $_SESSION["speciaux"] = "";

    // reset passwords 
    // Vider le tableau des mots de passe et réinitialiser la variable $password
    $_SESSION['generated_passwords'] = array();
    $_SESSION['passwords_generated'] = false;
    $passwords = array();
 
    // Margin top Formulaire
    $_SESSION['margin-top'] = "0";
    $_SESSION['left'] = "";
    // Mobile visibility for Media Queries
    $_SESSION['mobile-visible'] = "";
    $_SESSION['center'] = "j-center";

}

// Définir la durée de vie du cookie de session à 0 pour qu'il expire à la fermeture du navigateur
session_set_cookie_params(0);

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <!-- Titre -->
        <title>Générateur de mot de passe</title>
        <!-- Appeler le Script Jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- Données Meta -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Cache-Control" content="no-cache" />
        <meta name="robots" content="noindex, nofollow" />
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Links -->
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
        <link rel="icon" href="favicon.ico" type="image/x-icon"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css?test=<?= rand(0, 9999999) ?>"/>
        <!-- devices css -->
        <link rel="stylesheet" href="mobile.css?test=<?= rand(0, 9999999) ?>"/>
        <!-- Lib -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
        <!-- Links Awesome fonts -->
        <link rel="stylesheet" href="./assets/fontawesome/css/all.css">
    </head>

    <body>

        <!--Container Fluid-->
        <div class="container-fluid d-flex <?php echo $_SESSION['center'] ?> mob-column props">
            <div class="formulaire <?php echo $_SESSION['mobile-visible'] ?>">
           
                <!-- Formulaire -->
                <form method="post" action="" class="login-clean">
                    <div class="title">
                        <h1>Générateur de mot de passe</h1>
                    </div>

                    <div class="form-label">
                        <!-- Échapper les caractères spéciaux dans la value -->
                        <label class="form-label" for="typeNumber">Nombre de caractères</label>
                        <input type="text" id="typeNumber" class="form-control" min="5" name="length"
                        <?php if (isset($_POST["length"])) echo 'placeholder="Nombre de caractères"'; ?>
                        value="<?php if (isset($_POST["length"])) echo htmlspecialchars($_POST["length"]); ?>"
                        >

                    </div>
                    <?php

                        // Interdire les caractères spéciaux 
                        if (isset($_POST["button_generate"]) && (!empty($_POST["length"]) && !preg_match('/^[0-9]+$/', trim($_POST["length"])))) {
                            $_SESSION['mobile-visible'] = "";

                            // Si les résultats sont affichés, alors changer la classe
                            if (!empty($_SESSION['generated_passwords'])) {
                                $_SESSION['center'] = "j-evenly";
                            }
                          
                            echo '<div class="text-danger error">Les caractères spéciaux / lettres sont interdits.</div>';
                        }

                        // En cas de valeur nulle
                        if (isset($_POST["button_generate"]) && isset($_POST["length"]) && empty($_POST["length"])) {
                            $_SESSION['mobile-visible'] = "";
                            $_SESSION['error'] = "true";

                            echo '<div class="text-danger error">Merci d\'entrer une valeur numérique.</div>';
                        } else {
                            $_SESSION['error'] = "false"; // Réinitialiser l'erreur à false en cas de non-erreur

                            // Si les résultats sont affichés, alors changer la classe
                            if (!empty($_SESSION['generated_passwords'])) {
                                $_SESSION['center'] = "j-evenly";
                            }
                        }

                        // Si aucune option n'est choisie
                        if (isset($_POST["button_generate"])){
                            if (empty($_POST["AZ"]) && empty($_POST["az"]) && empty($_POST["09"]) && empty($_POST["speciaux"])) {
                                $_SESSION['mobile-visible'] = "";
                                $_SESSION['error'] = "true";

                                echo '<div class="text-danger error">Merci de choisir au moins une option.</div>';
                            } else {
                                $_SESSION['error'] = "false"; // Réinitialiser l'erreur à false en cas de non-erreur

                                // Si les résultats sont affichés, alors changer la classe
                                if (!empty($_SESSION['generated_passwords'])) {
                                    $_SESSION['center'] = "j-evenly";
                                }
                            }
                        }
                    ?>

                    <div class="allswitchs d-flex j-center">
                        <div class="switch-one">
                        <!-- AZ Checked -->
                        <div class="form-check form-switch" id="check">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckAZ" name="AZ"
                                    <?php echo $_SESSION["AZ"] ?? ""; ?>

                            >
                            <label class="form-check-label" for="flexSwitchCheckAZ">A-Z</label>
                            </div>
                            <!-- az Checked -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckaz" name="az"
                                        <?php echo $_SESSION["az"] ?? ""; ?>

                                >
                                <label class="form-check-label" for="flexSwitchCheckaz">a-z</label>
                            </div>
                        </div>
                    
                        <div class="switch-two">
                            <!-- 09 Checked -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckD09" name="09"
                                        <?php echo $_SESSION["09"] ?? ""; ?>

                                >
                                <label class="form-check-label" for="flexSwitchCheckD09">0-9</label>
                            </div>

                            <!-- caractères spéciaux Checked -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckspecial" name="speciaux"
                                    <?php echo $_SESSION["speciaux"] ?? ""; ?>

                                >
                                <label class="form-check-label" for="flexSwitchCheckspecial">!@#$%^&*</label>
                            </div>
                        </div>
                    </div>
                
                    <button class="btn" type="submit" name="button_generate">Générer les mots de passe</button>
                    <button class="btn" type="submit" name="reset_switches_passwords">Réinitialiser</button>

                </form>
            </div>

                <!-- Afficher les mots de passe -->
                <div class="generated-pwd disflex flex-column j-evenly <?php echo $_SESSION['none'];?>">

                <?php
                $topPosition = 10; // Initialiser la position de départ
                for ($i = 0; $i < 5; $i++) {
                    $password = $_SESSION['generated_passwords'][$i];

                if (!empty(trim($password))):
                ?>

                <div class="" style="top:<?php echo $topPosition; ?>rem;left: 61rem;">
                    <div class="form-control password copy-password d-flex align-items-center justify-content-between" style="max-width:<?php echo $_SESSION['max-width'];?>rem; cursor: pointer;">
                        <div class="password-text">
                            <?php echo $password; 
                ?>
                        </div>
                        <div class="password-icon">
                            <i class="fa-regular fa-copy copy-password" style="color: #ffffff;"></i>
                        </div>
                    </div>
                </div>

                <?php
                $topPosition += 5; // Incrementer la position du top
                endif;
                }
                ?>
                </div>
            </div>
        </div>
        <script>
        $(document).ready(function() {

            // Ici j'ajoute le copier coller du message au clic
            $("body").on("click", ".copy-password", function() {
                var $temp = $("<input>");
                $("body").append($temp);
                // ajout du trim pour éviter les espaces
                var passwordText = $(this).find(".password-text").text().trim();
                $temp.val(passwordText).select();
                document.execCommand("copy");
                $temp.remove();

                // Effacer le message précédent s'il existe
                $(this).find(".info-message").remove();

                // Ajouter le message d'information
                var $infoMessage = $('<div class="position-fixed border border-success pos info-message" style="background-color: #321466;border-radius : 5rem;border: solid 4px #ff0059;">Mot de passe copié dans le presse-papiers</div>');
                $(this).append($infoMessage);

                // Supprimer le message après 0.6s
                setTimeout(function() {
                    $infoMessage.remove();
                }, 600);
            });

        });

        </script>
    </body>
</html>

<?php

function Password() {

    $maj = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $mini = "abcdefghijklmnopqrstuvwxyz";
    $chif = "0123456789";
    $spe = "!@#$%^&*";
    $length = $_POST["length"];
    $button = isset($_POST["button_generate"]);
    $password = "";
    $conditions = "";

    // Récupérer directement les valeurs cochées depuis $_POST dans la fonction Password()
    $AZ = isset($_POST["AZ"]) ? $_POST["AZ"] : "";
    $az = isset($_POST["az"]) ? $_POST["az"] : "";
    $chiffres = isset($_POST["09"]) ? $_POST["09"] : "";
    $speciaux = isset($_POST["speciaux"]) ? $_POST["speciaux"] : "";


    $conditionsSets = array();
    if ($AZ == "on"){
        $conditionsSets[] = $maj;
    }
    if ($az == "on"){
        $conditionsSets[] = $mini;
    }
    if ($chiffres == "on"){
        $conditionsSets[] = $chif;
    }
    if ($speciaux == "on"){
        $conditionsSets[] = $spe;
    }

    if ($conditionsSets) { // s'assurer que l'utilisateur a coché au moins une case
        $numSets = count($conditionsSets);
        // détermine combien de caractères de chaque type de caractères doivent
        // être inclus dans le mot de passe au minimum
        $minPerSet = floor($length / $numSets);
        // nombre de caractères supplémentaires qui doivent être ajoutés au mot de passe
        // après avoir ajouté le nombre minimum de caractères de chaque type
        $extra = $length % $numSets;

        for ($i = 0; $i < $numSets; $i++) {
            $set = $conditionsSets[$i];
            $numFromSet = $minPerSet + ($i < $extra ? 1 : 0);

            for ($j = 0; $j < $numFromSet; $j++) {
                $password .= $set[random_int(0, strlen($set) - 1)];
            }
        }

        // Mélanger le mot de passe pour rendre la distribution des types de caractères plus aléatoire
        $password = str_shuffle($password);
    }
    return $password;
}


?>





