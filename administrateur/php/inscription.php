<?php
session_start();
include("connexion.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST["role"])) {
        $message = "Rôle non défini.";
    } else {

        $role = $_POST["role"];

        // Common fields
        $nom = mysqli_real_escape_string($conn, $_POST["nom"]);
        $prenom = mysqli_real_escape_string($conn, $_POST["prenom"]);
        $email = mysqli_real_escape_string($conn, $_POST["email"]);
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        // 🔒 Check duplicate email (donneur + receveur)
        $check = mysqli_query(
            $conn,
            "SELECT id_receveur FROM receveur WHERE email_receveur='$email'
             UNION
             SELECT id_donneur FROM donneur WHERE email='$email'"
        );

        if (mysqli_num_rows($check) > 0) {
            $message = "Cet email est déjà utilisé.";
        } else {

            /* ================= RECEVEUR ================= */
            if ($role === "receveur") {

                $groupe = $_POST["groupe"];
                $hopital = mysqli_real_escape_string($conn, $_POST["hopital"]);
                $date = $_POST["date_demande"];

                // default values
                $besoin_urgent = 0;
                $description = "";

                $sql = "INSERT INTO receveur
                        (nom_receveur, prenom_receveur, groupe_sanguin_receveur, hopital,
                         besoin_urgent, date_demande, description, email_receveur, mdp_receveur)
                        VALUES
                        ('$nom', '$prenom', '$groupe', '$hopital',
                         '$besoin_urgent', '$date', '$description', '$email', '$password')";

                if (mysqli_query($conn, $sql)) {
                    $_SESSION["user_id"] = mysqli_insert_id($conn);
                    $_SESSION["role"] = "receveur";
                    header("Location: /100vies/receveur/php/receveur.php");
                    exit();
                } else {
                    $message = "Erreur receveur : " . mysqli_error($conn);
                }
            }

            /* ================= DONNEUR ================= */
            elseif ($role === "donneur") {

                $datenais = $_POST["datenais"];
                $sexe = $_POST["sexe"];
                $groupe = $_POST["groupe"];
                $adresse = "";

                $sql = "INSERT INTO donneur
                        (nom, prenom, datenais, sexe, grp_sanguin, email, mdp, adresse)
                        VALUES
                        ('$nom', '$prenom', '$datenais', '$sexe', '$groupe', '$email', '$password', '$adresse')";

                if (mysqli_query($conn, $sql)) {
                    $_SESSION["user_id"] = mysqli_insert_id($conn);
                    $_SESSION["role"] = "donneur";
                    header("Location: /100vies/Donneur/php/donneur.php");
                    exit();
                } else {
                    $message = "Erreur donneur : " . mysqli_error($conn);
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Don du sang – Formulaire</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link rel="stylesheet" href="../css/inscription.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
</head>
<body>
<div class="intro">
    <h1>Un don peut sauver une vie</h1>
    <p>Rejoignez notre initiative de sensibilisation au don du sang. Que vous soyez donneur ou receveur, chaque geste compte.</p>
</div>
<h6>
    <span>Receveur</span>
    <span>Donneur</span>
</h6>

<input class="checkbox" type="checkbox" id="reg-log">
<label for="reg-log"></label>

<div class="card-3d-wrap">
<div class="card-3d-wrapper">

<!-- ===== RECEVEUR ===== -->
<div class="card-front">
<div class="center-wrap">
<h2>J’ai besoin d’aide</h2>
<p>Inscrivez-vous pour recevoir du soutien rapidement.</p>

<div class="progress-bar"><div class="progress-bar-fill" id="progress-receveur"></div></div>

<form id="receveurForm" method="POST" action="http://localhost/100vies/administrateur/php/inscription.php">
    <input type="hidden" name="role" value="receveur">
    <!-- Étape 1 -->
    <div class="form-step step1 active">
        <div style="position:relative">
            <input class="form-style" name="nom" placeholder="Nom" required>
            <i class="uil uil-user form-style-icon"></i>
        </div>
        <div style="position:relative">
            <input class="form-style" name="prenom" placeholder="Prénom" required>
            <i class="uil uil-user form-style-icon"></i>
        </div>
        <div style="position:relative">
            <input class="form-style" type="email" name="email" placeholder="Email" required>
            <i class="uil uil-envelope form-style-icon"></i>
        </div>
        <div style="position:relative">
            <input class="form-style" type="password" name="password" placeholder="Mot de passe" required>
            <i class="uil uil-lock form-style-icon"></i>
        </div>
        <div class="step-buttons">
            <button type="button" class="btn" onclick="nextStep('receveur')">Suivant</button>
        </div>
        <div class="signup-link">
            <p>Déjà inscrit ? <a href="form.php">Se connecter</a></p>
        </div>
    </div>
    <!-- Étape 2 -->
    <div class="form-step step2 hide">
        <div style="position:relative">
            <select class="form-style" name="groupe" required>
                <option value="">Groupe sanguin</option>
                <option>A+</option>
                <option>A-</option>
                <option>B+</option>
                <option>B-</option>
                <option>AB+</option>
                <option>AB-</option>
                <option>O+</option>
                <option>O-</option>
            </select>
            <i class="uil uil-droplet form-style-icon"></i>
        </div>
        <div style="position:relative">
            <input class="form-style" name="hopital" placeholder="Hôpital" required>
        
        </div>
        <div style="position:relative">
            <input class="form-style" type="date" name="date_demande" required>
            
        </div>
        <div class="step-buttons">
            <button type="button" class="btn" onclick="prevStep('receveur')">Précédent</button>
            <button type="submit" class="btn">Demander de l’aide</button>
        </div>
        <div class="signup-link">
            <p>Déjà inscrit ? <a href="form.php">Se connecter</a></p>
        </div>
    </div>
</form>
</div>
</div>
<!-- ===== DONNEUR ===== -->
<div class="card-back">
<div class="center-wrap">
<h2>Je veux aider</h2>
<p>Votre don peut faire la différence.</p>

<div class="progress-bar"><div class="progress-bar-fill" id="progress-donneur"></div></div>

<form id="donneurForm" method="POST" action="http://localhost/100vies/administrateur/php/inscription.php">
    <input type="hidden" name="role" value="donneur">
    <!-- Étape 1 -->
    <div class="form-step step1 active">
        <div style="position:relative">
            <input class="form-style" name="nom" placeholder="Nom" required>
            <i class="uil uil-user form-style-icon"></i>
        </div>
        <div style="position:relative">
            <input class="form-style" name="prenom" placeholder="Prénom" required>
            <i class="uil uil-user form-style-icon"></i>
        </div>
        <div style="position:relative">
            <input class="form-style" type="email" name="email" placeholder="Email" required>
            <i class="uil uil-envelope form-style-icon"></i>
        </div>
        <div style="position:relative">
            <input class="form-style" type="password" name="password" placeholder="Mot de passe" required>
            <i class="uil uil-lock form-style-icon"></i>
        </div>
        <div class="step-buttons">
            <button type="button" class="btn" onclick="nextStep('donneur')">Suivant</button>
        </div>
        <div class="signup-link">
            <p>Déjà inscrit ? <a href="form.php">Se connecter</a></p>
        </div>
    </div>

    <!-- Étape 2 -->
    <div class="form-step step2 hide">
        <div style="position:relative">
            <input class="form-style" type="date" name="datenais" required>
            <i class="uil uil-calendar-alt form-style-icon"></i>
        </div>
        <div style="position:relative">
            <select class="form-style" name="sexe" required>
                <option value="">Sexe</option>
                <option value="F">Femme</option>
                <option value="M">Homme</option>
            </select>
            <i class="uil uil-venus-mars form-style-icon"></i>
        </div>
        <div style="position:relative">
            <select class="form-style" name="groupe" required>
                <option value="">Groupe sanguin</option>
                <option>A+</option><option>A-</option><option>B+</option><option>B-</option>
                <option>AB+</option><option>AB-</option><option>O+</option><option>O-</option>
            </select>
            <i class="uil uil-droplet form-style-icon"></i>
        </div>
        <div class="step-buttons">
            <button type="button" class="btn" onclick="prevStep('donneur')">Précédent</button>
            <button type="submit" class="btn">Je deviens donneur</button>
        </div>
        <div class="signup-link">
            <p>Déjà inscrit ? <a href="form.php">Se connecter</a></p>
        </div>
    </div>
</form>
</div>
</div>
</div>
</div>
<?php if (!empty($message)) { ?>
    <p style="color:red; text-align:center;">
        <?php echo $message; ?>
    </p>
<?php } ?>
<script src="../js/inscription.js"></script>
</body>
</html>
