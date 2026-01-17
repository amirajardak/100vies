<?php
session_start();
include("connexion.php");

$message = "";


if (isset($_POST["login"])) {
    // 🔹 Sécurisation des entrées
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    // 🔹 Vérification Donneur
    $sqlDonneur = "SELECT id_donneur FROM donneur WHERE email='$email' AND mdp='$password'";
    $resDonneur = mysqli_query($conn, $sqlDonneur);

    if (mysqli_num_rows($resDonneur) == 1) {
        $user = mysqli_fetch_assoc($resDonneur);
        $_SESSION["user_id"] = $user["id_donneur"];
        $_SESSION["role"] = "donneur";
        header("Location: http://localhost/sensibilisation-au-don-de-sang/Donneur/php/donneur.php");
        exit();
    }

    // 🔹 Vérification Receveur
    $sqlReceveur = "SELECT id_receveur FROM receveur WHERE email_receveur='$email' AND mdp_receveur='$password'";
    $resReceveur = mysqli_query($conn, $sqlReceveur);

    if (mysqli_num_rows($resReceveur) == 1) {
        $user = mysqli_fetch_assoc($resReceveur);
        $_SESSION["user_id"] = $user["id_receveur"];
        $_SESSION["role"] = "receveur";
        header("Location: http://localhost/sensibilisation-au-don-de-sang/receveur/php/receveur.php");
        exit();
    }

    // 🔹 Vérification Admin
    $sqlAdmin = "SELECT id_admin FROM admin WHERE email_admin='$email' AND mdp_admin='$password'";
    $resAdmin = mysqli_query($conn, $sqlAdmin);

    if (mysqli_num_rows($resAdmin) == 1) {
        $user = mysqli_fetch_assoc($resAdmin);
        $_SESSION["user_id"] = $user["id_admin"];
        $_SESSION["role"] = "admin";
        header("Location: http://localhost/sensibilisation-au-don-de-sang/administrateur/php/dashboard.php");
        exit();
    }

    // 🔹 Message d'erreur
    $message = "Email ou mot de passe incorrect.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/png" href="../media/logo_noir.png">
  <title>Login form</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="container">
  <div class="login-box">
    <h2>Login</h2>

    <p style="color:red; text-align:center;"><?php echo $message; ?></p>
    <form method="POST" action="">
      <div class="input-box">
        <input type="email" name="email" required placeholder="utilisateur@gmail.com">
      
      </div>
      <div class="input-box">
        <input type="password" name="password" required placeholder="Taper votre mot de passe">
    
      </div>
      <div class="forgot-password">
        <a href="nouveaupsw.php">Forgot Password?</a>
      </div>

      <button id="loginBtn" name="login">Se connecter</button>

      <div class="signup-link">
        <a href="inscription.php">Signup</a>
      </div>
    </form>
  </div>
  <span style="--i:0;"></span>
  <span style="--i:1;"></span>
  <span style="--i:2;"></span>
  <span style="--i:3;"></span>
  <span style="--i:4;"></span>
  <span style="--i:5;"></span>
  <span style="--i:6;"></span>
  <span style="--i:7;"></span>
  <span style="--i:8;"></span>
  <span style="--i:9;"></span>
  <span style="--i:10;"></span>
  <span style="--i:11;"></span>
  <span style="--i:12;"></span>
  <span style="--i:13;"></span>
  <span style="--i:14;"></span>
  <span style="--i:15;"></span>
  <span style="--i:16;"></span>
  <span style="--i:17;"></span>
  <span style="--i:18;"></span>
  <span style="--i:19;"></span>
  <span style="--i:20;"></span>
  <span style="--i:21;"></span>
  <span style="--i:22;"></span>
  <span style="--i:23;"></span>
  <span style="--i:24;"></span>
  <span style="--i:25;"></span>
  <span style="--i:26;"></span>
  <span style="--i:27;"></span>
  <span style="--i:28;"></span>
  <span style="--i:29;"></span>
  <span style="--i:30;"></span>
  <span style="--i:31;"></span>
  <span style="--i:32;"></span>
  <span style="--i:33;"></span>
  <span style="--i:34;"></span>
  <span style="--i:35;"></span>
  <span style="--i:36;"></span>
  <span style="--i:37;"></span>
  <span style="--i:38;"></span>
  <span style="--i:39;"></span>
  <span style="--i:40;"></span>
  <span style="--i:41;"></span>
  <span style="--i:42;"></span>
  <span style="--i:43;"></span>
  <span style="--i:44;"></span>
  <span style="--i:45;"></span>
  <span style="--i:46;"></span>
  <span style="--i:47;"></span>
  <span style="--i:48;"></span>
  <span style="--i:49;"></span>
</div>
</body>
</html>