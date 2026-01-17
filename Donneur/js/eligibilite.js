
const total = questions.length;
let currentStep = 0;
let score = 0;

const quizArea = document.getElementById("quiz-area");
const progressBar = document.querySelector(".progress-bar");

function renderQuestion() {
    if(currentStep >= total){
        showResult();
        return;
    }

    const q = questions[currentStep];
    quizArea.innerHTML = `
        <div class="fade show">
            <h4 class="fw-semibold mb-4">${q.text}</h4>
            <div class="d-grid gap-3">
                ${q.answers.map(a => `<button class="btn btn-outline-secondary btn-answer" data-score="${a.s}">${a.t}</button>`).join('')}
            </div>
            <p class="text-end mt-3 text-muted">Question ${currentStep + 1} / ${total}</p>
        </div>
    `;
    progressBar.style.width = ((currentStep)/total*100)+"%";

    document.querySelectorAll(".btn-answer").forEach(btn => {
        btn.addEventListener("click", () => {
            const val = parseInt(btn.dataset.score);
            score += val;

            // Feedback rapide
            btn.classList.remove("btn-outline-secondary");
            btn.classList.add(val>0 ? "btn-success" : "btn-danger");

            // Passage à la question suivante après un petit délai
            setTimeout(()=>{
                currentStep++;
                renderQuestion();
            }, 500);
        });
    });
}

function showResult(){
    let title, text, button = false;
    if(score>=6){ title="Tu es éligible 🎉"; text="Tu peux potentiellement donner ton sang."; button=true;}
    else if(score>=0){ title="À vérifier ⚠️"; text="Un avis médical est conseillé."; button=true;}
    else { title="Non éligible ❌"; text="Tu ne remplis pas les conditions pour le moment."; button=false;}
    quizArea.innerHTML = `
        <div class="text-center py-4 fade show">
            <h2 class="fw-bold mb-3">${title}</h2>
            <p class="text-muted mb-4">${text}</p>
            ${button? `<a href="rdv.php" class="btn text-white px-4 py-2 fw-semibold" style="background: var(--accent);">Prendre rendez-vous</a>` : ''}
        </div>
    `;
    progressBar.style.width = "100%";
}

renderQuestion();