<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Mot de passe oublié</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
<link rel="stylesheet" href="../css/nouveaupsw.css">
<link rel="icon" type="image/png" href="../media/logo_noir.png">
</head>
<body>

<div class="card">
    <h2>Mot de passe oublié</h2>
    <p>Entrez votre email pour recevoir un lien de réinitialisation.</p>

    <?php
    include("connexion.php");
    $message = "";
    if(isset($_POST['reset'])){
        $email = $_POST['email'];
        $sql = "SELECT * FROM donneur WHERE email='$email'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 1){
            $message = "Un lien de réinitialisation a été envoyé à votre email.";
        } else {
            $message = "Cet email n'existe pas dans notre base.";
        }
    }
    ?>

    <?php if($message != ""): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="input-box">
            <input type="email" name="email" placeholder="Email" required >
            <i class="uil uil-envelope"></i>
        </div>
        <button type="submit" class="btn" name="reset">Envoyer le lien</button>
    </form>
    <p style="margin-top:15px;"><a href="form.php">Retour à la connexion</a></p>
</div>
</body>
</html>
