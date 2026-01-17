const logo = document.getElementById('logo');
    
window.addEventListener("scroll", function () {
    const header = document.querySelector("header");
    const hero = document.querySelector(".hero");

    // Quand on dépasse la hauteur du hero = on entre dans les sections blanches
    if (window.scrollY > hero.offsetHeight - 120) {
        header.classList.add("nav-dark");
        logo.src = '../media/logo_noir.png';
    } else {
        header.classList.remove("nav-dark");
        logo.src = '../media/logo_blanc.png';
    }

});

// Scroll animation for droplet cards
const observerOptions = {
    threshold: 0.2,
    rootMargin: '0px 0px -100px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate');
        }
    });
}, observerOptions);

// Observe all droplet cards
const droplets = document.querySelectorAll('.hexagon');
droplets.forEach(droplet => {
    observer.observe(droplet);
});

const faqItems = document.querySelectorAll('.faq-item h4');

faqItems.forEach(item => {
    item.addEventListener('click', () => {
        const parent = item.parentElement;
        parent.classList.toggle('active');
    });
});
const timelineItems = document.querySelectorAll('.timeline-item');

function showTimelineItems() {
    const triggerBottom = window.innerHeight * 0.85;

    timelineItems.forEach(item => {
        const itemTop = item.getBoundingClientRect().top;

        if(itemTop < triggerBottom) {
            item.classList.add('show');
        }
    });
}

window.addEventListener('scroll', showTimelineItems);
window.addEventListener('load', showTimelineItems);


// Sélectionne tous les éléments .step-item
const stepItems = document.querySelectorAll('.step-item');

// Fonction pour vérifier si un élément est visible dans la fenêtre
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.bottom >= 0
    );
}

// Fonction pour ajouter la classe show aux éléments visibles
function animateSteps() {
    stepItems.forEach(item => {
        if (isInViewport(item)) {
            item.classList.add('show');
        }
    });
}

// Événement de scroll et d'initialisation au chargement
window.addEventListener('scroll', animateSteps);
window.addEventListener('load', animateSteps);

// trouver le container de stats (supporte plusieurs noms : #stats, #statistics, .stats, .statistics)
(function() {
    const container =
        document.querySelector('#stats') ||
        document.querySelector('#statistics') ||
        document.querySelector('.stats') ||
        document.querySelector('.statistics');

    if (!container) return; // rien à faire si pas trouvé

    // Récupérer tous les éléments "stat" visibles (supporte plusieurs structures)
    const statNodes = Array.from(container.querySelectorAll('.stat'));

    // Préparer une liste d'objets { el: element, target: number }
    const stats = statNodes.map(node => {
        // cherche un élément avec class stat-number
        const numEl = node.querySelector('.stat-number') || node.querySelector('h3') || node.querySelector('span');
        let target = 0;
        if (numEl) {
            // si data-target présent, priorité
            const dt = numEl.getAttribute('data-target');
            if (dt && !isNaN(dt)) {
                target = parseInt(dt, 10);
            } else {
                // sinon essayer d'extraire un nombre depuis le texte (ex: "1 200+" ou "1200")
                const text = numEl.textContent.replace(/\u00A0/g,' ').trim();
                const digits = text.replace(/[^\d]/g, '');
                target = digits ? parseInt(digits, 10) : 0;
            }
        }
        // si target = 0, peut-être la valeur est dans un data-target du .stat
        if (!target) {
            const alt = node.getAttribute('data-target');
            if (alt && !isNaN(alt)) target = parseInt(alt, 10);
        }
        return { node, numEl, target };
    }).filter(s => s.numEl && s.target > 0);

    if (!stats.length) return;

    let played = false;

    function easeOutQuad(t){ return t*(2-t); } // easing

    function animate() {
        if (played) return;
        played = true;
        stats.forEach(({ numEl, target }, idx) => {
            const duration = 1400 + idx * 150; // léger stagger
            const start = performance.now();
            const startVal = 0;
            function step(now){
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const eased = easeOutQuad(progress);
                const current = Math.floor(startVal + (target - startVal) * eased);
                // affiche avec séparateur d'espace pour lisibilité
                numEl.textContent = current.toLocaleString('fr-FR');
                if (progress < 1) requestAnimationFrame(step);
                else numEl.textContent = target.toLocaleString('fr-FR');
            }
            requestAnimationFrame(step);
            // ajout classe active pour CSS (apparition)
            numEl.closest('.stat').classList.add('active');
        });
    }

    // Intersection Observer pour déclencher l'animation quand container visible
    const io = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                animate();
                io.disconnect();
            }
        });
    }, { threshold: 0.3 });

    io.observe(container);
})();



const header = document.querySelector('header');
const statisticsSection = document.getElementById('statistics');

window.addEventListener('scroll', () => {
    const scrollY = window.scrollY;
    const statsTop = statisticsSection.offsetTop;

    if (scrollY >= statsTop - 50) { // ajuster selon la hauteur du header
        header.classList.add('glass');
    } else {
        header.classList.remove('glass');
    }
});
