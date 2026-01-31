<?php
session_start(); // 🔹 Démarrer la session
include("connexion.php");
function isActive($page) {
  $current = basename($_SERVER['PHP_SELF']);  // ex: index1.php
  return ($current === $page) ? 'active' : '';
}
/* ===================== STATISTIQUES ===================== */

// 1️ Vies sauvées (1 don = 3 vies)
$res = $conn->query("SELECT COUNT(*) AS total FROM don");
$row = $res->fetch_assoc();
$vies_sauvees = $row['total'] * 3;

// 2️ Donneurs inscrits
$res = $conn->query("SELECT COUNT(*) AS total FROM donneur");
$donneurs = $res->fetch_assoc()['total'];

// 3️ Centres partenaires
$res = $conn->query("SELECT COUNT(*) AS total FROM centre");
$centres = $res->fetch_assoc()['total'];
$error = "";
// 1️ Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['name'];
    $email = $_POST['email'];
    $sujet = $_POST['subject'];
    $message = $_POST['message'];

    // Insérer dans la table notification
    $sql = "INSERT INTO notification (nom_donneur, email_donneur, sujet, contenu_message, date_envoi, statu) 
            VALUES (?, ?, ?, ?, NOW(), 'Non lu')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nom, $email, $sujet, $message);
    $stmt->execute();
    $stmt->close();

    // 🔹 Stocker un indicateur de succès dans la session
    $_SESSION['success'] = "✅ Votre message a été envoyé avec succès.";

    // Redirection pour éviter la resoumission
    header("Location: index1.php");
    exit();
}

// 🔹 Vérifier si le message de succès est présent
$success = "";
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']); // 🔹 Supprimer pour que le message n'apparaisse qu'une seule fois
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>100Vies</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/chatbot.css">
    
    <link rel="icon" type="image/png" href="../media/logo_noir.png">
</head>
<body>

    <!-- HEADER + NAVIGATION -->
    <header>
        <div class="logo">
            <img id="logo" src="../media/logo_blanc.png" alt="100Vies Logo" width="50px">
        </div>

        
        <!-- Burger (mobile) -->
        <button class="burger" type="button" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mainNav">
            <span></span><span></span><span></span>
        </button>
<nav>
            <ul id="mainNav" class="mainNav">
  <li><a class="<?= isActive('index1.php') ?>" href="index1.php">Accueil</a></li>
  <li><a class="<?= isActive('campagnes-evenements.php') ?>" href="campagnes-evenements.php">Campagnes</a></li>
  <li><a class="<?= isActive('temoignages.php') ?>" href="temoignages.php">Témoignages</a></li>
  <li><a class="<?= isActive('a-propos.php') ?>" href="a-propos.php">À propos</a></li>
  <li><a class="<?= isActive('contact.php') ?>" href="contact.php">Contact</a></li>
</ul>
        </nav>
        <div class="nav-actions">
            <a href="../../administrateur/php/inscription.php" class="btn-connexion1">Créer compte</a>
            <a href="../../administrateur/php/form.php" class="btn-connexion">Connexion</a>
        </div>
</header>
   

    <!-- HERO SECTION -->
    <section class="hero">
        <video class="hero-video" autoplay muted loop playsinline>
            <source src="../media/header.mp4" type="video/mp4">
        </video>
        <div class="hero-text">
            <h1>Avec 100 vies, personne ne resterait sans vie.</h1>
            <p>
                Trouvez un centre, planifiez un don, publiez un appel et accédez à <br>des 
                informations fiables, au même endroit.
            </p>

            <a href="../../administrateur/php/inscription.php" class="btn-cta">Sauver</a>
            <a href="../../administrateur/php/inscription.php" class="btn-cta">être sauvé</a>
        </div>
    </section>

      <!-- Statistiques & Impact -->
<section class="statistics" id="statistics">
    <h2 class="section-title">STATISTIQUES & IMPACT</h2>

    <div class="stats-container">
        <div class="stat">
            <h3><?= number_format($vies_sauvees, 0, ',', ' ') ?>+</h3>
            <p>Vies sauvées</p>
        </div>

        <div class="stat">
            <h3><?= number_format($donneurs, 0, ',', ' ') ?>+</h3>
            <p>Donneurs inscrits</p>
        </div>

        <div class="stat">
            <h3><?= number_format($centres, 0, ',', ' ') ?></h3>
            <p>Centres partenaires</p>
        </div>
    </div>
</section>

<!-- Features Section -->
    <section class="features" id="features">
        <div class="features-container">
            <h2 class="section-title">NOS ENGAGEMENTS</h2>
            <div class="diagonal-grid">
                <div class="feature-row">
                    <div class="feature-content glass">
                        <div class="feature-icon">❤️</div>
                        <h3>Sauver des vies</h3>
                        <p>Chaque don de sang permet de sauver la vie d’une personne en situation critique.</p>
                    </div>
                    <div class="feature-visual glass">
                        <img src="../media/doctor-dressing-gown-holds-dropper-with-medicines.jpg" alt="" width="600px">
                    </div>
                </div>
                <div class="feature-row">
                    <div class="feature-content glass">
                        <div class="feature-icon">🩺</div>
                        <h3>Sécurité maximale</h3>
                        <p>Nos centres respectent des protocoles stricts pour garantir la sécurité des donneurs et des receveurs.</p>
                    </div>
                    <div class="feature-visual glass ">
                        <img src="../media/close-up-doctor-holding-container.jpg" alt="" width="600px" height="400px">
                    </div>
                </div>
                <div class="feature-row">
                    <div class="feature-content glass">
                        <div class="feature-icon">🤝</div>
                        <h3>Solidarité & Communauté</h3>
                        <p>Ensemble, nous construisons une communauté engagée et responsable autour du don de sang.</p>
                    </div>
                    <div class="feature-visual glass">
                        <img src="../media/people-stacking-hands-together-park.jpg" alt=""width="600px">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Showcase Section -->
    <section class="showcase" id="showcase">
        <h2 class="section-title">NOS ACTIONS</h2>
        <div class="hexagon-container">
            <div class="hexagon">
                <div class="hexagon-inner glass">
                    
                    <h4>Centres de don</h4>
                    <p>Localisez les centres proches et planifiez votre don.</p>
                </div>
            </div>
            <div class="hexagon">
                <div class="hexagon-inner glass">
                    
                    <h4>Campagnes</h4>
                    <p>Participer à nos campagnes de collecte de sang.</p>
                </div>
            </div>
            <div class="hexagon">
                <div class="hexagon-inner glass">
                    
                    <h4>Objectifs</h4>
                    <p>Suivez nos objectifs et impact communautaire.</p>
                </div>
            </div>
            <div class="hexagon">
                <div class="hexagon-inner glass">
                    
                    <h4>Partenaires</h4>
                    <p>Nos collaborations avec les hôpitaux et associations.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Éligibilité & Conseils -->
<section class="eligibility" id="eligibility">
    <h2 class="section-title">ÉLIGIBILITÉ & CONSEILS</h2>

    <div class="steps-container">

        <div class="step-item">
            <div class="step-number">1</div>
            <h4>Qui peut donner ?</h4>
            <p>Les personnes en bonne santé âgées de 18 à 65 ans.</p>
        </div>

        <div class="step-item">
            <div class="step-number">2</div>
            <h4>Fréquence du don</h4>
            <p>Un don de sang complet tous les 3 mois.</p>
        </div>

        <div class="step-item">
            <div class="step-number">3</div>
            <h4>Préparer son don</h4>
            <p>Bien se reposer, boire de l’eau et éviter les repas trop gras.</p>
        </div>

        <div class="step-item">
            <div class="step-number">4</div>
            <h4>Après le don</h4>
            <p>Se reposer, boire de l’eau et éviter les efforts intenses.</p>
        </div>

    </div>
</section>

<!-- Événements & Campagnes à venir -->
<section class="timeline" id="timeline">
    <h2 class="section-title">ÉVÉNEMENTS À VENIR</h2>

    <div class="timeline-container">
        <div class="timeline-line"></div>

        <div class="timeline-item left">
            <div class="timeline-content glass" style="text-align: right;">
                <div class="timeline-year">2025</div>
                <h4>Collecte Nationale</h4>
                <p>Une grande campagne de don se déroulera dans tout le pays.</p>
            </div>
            <div class="timeline-dot"></div>
        </div>

        <div class="timeline-item right">
            <div class="timeline-content glass" style="text-align: left;">
                <div class="timeline-year">2026</div>
                <h4>Journée Santé & Solidarité</h4>
                <p>Programme éducatif avec ateliers, conférences et dépistages.</p>
            </div>
            <div class="timeline-dot"></div>
        </div>

        <div class="timeline-item left">
            <div class="timeline-content glass" style="text-align: right;">
                <div class="timeline-year">2027</div>
                <h4>Campagne Universitaire</h4>
                <p>Initiative nationale pour sensibiliser les jeunes aux dons.</p>
            </div>
            <div class="timeline-dot"></div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="faq" id="faq">
    <h2 class="section-title">FAQ</h2>
    <div class="faq-container">

        <div class="faq-item">
            <h4>Est-ce que je peux donner si je prends des médicaments ?</h4>
            <p>Certains médicaments sont compatibles, d'autres non.</p>
        </div>

        <div class="faq-item">
            <h4>Combien de temps dure un don ?</h4>
            <p>Environ 10 minutes pour le prélèvement.</p>
        </div>

        <div class="faq-item">
            <h4>Est-ce douloureux ?</h4>
            <p>La piqûre est légère et le don est bien toléré.</p>
        </div>

        <div class="faq-item">
            <h4>À quelle fréquence puis-je donner ?</h4>
            <p>Un don de sang est possible tous les 3 mois.</p>
        </div>

        <div class="faq-item">
            <h4>Dois-je être à jeun ?</h4>
            <p>Non, il est recommandé de manger léger avant un don.</p>
        </div>

        <div class="faq-item">
            <h4>Que vérifier avant de donner ?</h4>
            <p>Repos, hydratation et absence de maladie récente.</p>
        </div>

    </div>
</section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <h2 class="section-title">CONTACT</h2>
        <div class="contact-container">
            <div class="contact-info glass">
                <h3>CONTACTEZ-NOUS</h3>
                <p>Prêt à rejoindre notre communauté de donneurs ? Remplissez le formulaire ci-dessous pour nous contacter ou planifier votre don.</p>
                <div class="social-links">
                    <a href="#" class="glass">📡</a>
                    <a href="#" class="glass">🌐</a>
                    <a href="#" class="glass">💬</a>
                    <a href="#" class="glass">📨</a>
                </div>
            </div>
            <form method="POST">
            <div class="contact-form glass">
            <div class="form-group">
                <input type="text" name="name" placeholder="Nom complet" required>
                
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="text" name="subject" placeholder="Sujet" required>
            </div>
            <div class="form-group">
                <textarea name="message" placeholder="Votre message" rows="5" required></textarea>
            </div>
            <div class="submit">
                <button type="submit" class="submit-btn">Envoyer</button>
            </div>     
            </form>
        </div>
    </section>

    <!-- Footer -->
<footer class="footer-clean">
    <div class="footer-container">

        <div class="footer-columns">

            <div class="footer-col">
                <img id="logo" src="../media/logo_blanc.png" alt="100Vies Logo" width="50px">
                <p>Une plateforme dédiée à la sensibilisation et la promotion du don du sang.</p>
            </div>

            <div class="footer-col">
                <h4>Navigation</h4>
                <ul>
                    <li><a href="index1.php">Accueil</a></li>
                    <li><a href="#">Donneur</a></li>
                    <li><a href="#">Receveur</a></li>
                    <li><a href="campagnes-evenements.php">Campagnes</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Informations</h4>
                <ul>
                    <li><a href="a-propos.php">À propos</a></li>
                    <li><a href="temoignages.php">Témoignages</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            © 2025 100Vies — Tous droits réservés.
        </div>

    </div>
</footer>
<script>
const scrollBtn = document.getElementById('scrollTopBtn');

// Afficher le bouton après un certain scroll
window.addEventListener('scroll', () => {
    if (window.scrollY > 300) {
        scrollBtn.style.display = 'block';
    } else {
        scrollBtn.style.display = 'none';
    }
});

// Scroll vers le haut au clic
scrollBtn.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});
</script>

<script>
(function () {
  const burger = document.querySelector('.burger');
  const navList = document.getElementById('mainNav');
  const actions = document.querySelector('.nav-actions');
  if (!burger || !navList) return;

  const mqMobile = window.matchMedia('(max-width: 768px)');

  function closeMenu() {
    navList.classList.remove('open');
    if (actions) actions.classList.remove('open');
    burger.classList.remove('open');
    burger.setAttribute('aria-expanded', 'false');
  }

  burger.addEventListener('click', () => {
    const isOpen = navList.classList.toggle('open');
    if (actions) actions.classList.toggle('open', isOpen);
    burger.classList.toggle('open', isOpen);
    burger.setAttribute('aria-expanded', String(isOpen));
  });

  // En mobile : fermeture après clic sur un lien
  navList.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
      if (!mqMobile.matches) return;
      closeMenu();
    });
  });

  window.addEventListener('resize', () => {
    if (!mqMobile.matches) closeMenu();
  });
})();
</script>

<script src="../js/index.js"></script>
<script src="../js/chatbot.js"></script>
</body>
</html>