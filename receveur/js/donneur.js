// Likes
document.querySelectorAll(".like-btn").forEach(btn=>{
  btn.addEventListener("click",()=>{
    const type = btn.dataset.type;
    const id_pub = btn.dataset.id;
    fetch("like_action.php",{
      method:"POST",
      headers:{"Content-Type":"application/x-www-form-urlencoded"},
      body:`action=toggle&type=${type}&id_pub=${id_pub}`
    }).then(r=>r.json()).then(data=>{
      if(data.success){
        btn.querySelector(".heart").classList.toggle("liked",data.user_liked);
        btn.querySelector(".like-count").textContent = data.total;
      }
    });
  });
});

// Comment modal
const modal = document.getElementById("commentModal");
const mentionBox = document.getElementById("mention-box");

function openCommentModal(type,id_pub){
  document.getElementById("commentType").value = type;
  document.getElementById("commentId").value = id_pub;
  document.getElementById("commentText").value = "";
  modal.classList.add("active");
}
function closeCommentModal(){ modal.classList.remove("active"); }

function submitComment(){
  const type = document.getElementById("commentType").value;
  const id_pub = document.getElementById("commentId").value;
  const text = document.getElementById("commentText").value.trim();
  if(!text) return alert("Le commentaire est vide");
  fetch("comment_action.php",{
    method:"POST",
    headers:{"Content-Type":"application/x-www-form-urlencoded"},
    body:`type=${type}&id_pub=${id_pub}&contenu=${encodeURIComponent(text)}`
  }).then(r=>r.json()).then(data=>{
    if(data.success){
      const commentsDiv = document.getElementById(`comments-${type}-${id_pub}`);
      const div = document.createElement('div');
      div.className='comment';
      div.textContent=text;
      commentsDiv.appendChild(div);
      closeCommentModal();
    } else alert("Erreur lors de l'envoi du commentaire");
  });
}

// Mentions
function handleMention(e,input){
  const word = input.value.split(" ").pop();
  if(word.startsWith("@")){
    fetch("mention_search.php?q="+word.substring(1))
    .then(r=>r.json()).then(users=>{
      mentionBox.innerHTML="";
      users.forEach(u=>{
        const div = document.createElement("div");
        div.textContent = u.nom;
        div.onclick = ()=>insertMention(u.nom);
        mentionBox.appendChild(div);
      });
      mentionBox.style.display="block";
    });
  } else mentionBox.style.display="none";
}
function insertMention(name){
  const textarea = document.getElementById("commentText");
  textarea.value = textarea.value.replace(/@\w*$/,"@"+name+" ");
  mentionBox.style.display="none";
}
document.querySelectorAll(".share-btn").forEach(btn=>{
  btn.addEventListener("click", ()=>{
    const type = btn.dataset.type;
    const id = btn.dataset.id;
    const url = `${window.location.origin}/feed.php?type=${type}&id=${id}`;
    navigator.clipboard.writeText(url)
      .then(()=>alert("Lien copié !"))
      .catch(()=>alert("Impossible de copier le lien."));
  });
});

const filterType = document.getElementById("filter-type");
const filterBlood = document.getElementById("filter-blood");
const resetBtn = document.querySelector(".reset-filter");

function applyFilters() {

  const types = [...document.querySelectorAll('.filter-type:checked')].map(e => e.value);
  const dates = [...document.querySelectorAll('.filter-date:checked')].map(e => e.value);
  const bloods = [...document.querySelectorAll('.filter-blood:checked')].map(e => e.value);

  const now = new Date();

  document.querySelectorAll("article").forEach(article => {
    let show = true;

    const type = article.dataset.type;
    const blood = article.dataset.blood;
    const date = new Date(article.dataset.date);

    // TYPE
    if (types.length && !types.includes(type)) show = false;

    // GROUPE SANGUIN
    if (bloods.length && blood !== "all" && !bloods.includes(blood)) show = false;

    // DATE
    if (dates.length) {
      const diff = (now - date) / (1000*60*60*24);

      const valid =
        (dates.includes("today") && diff < 1) ||
        (dates.includes("week") && diff <= 7) ||
        (dates.includes("month") && diff <= 30);

      if (!valid) show = false;
    }

    article.style.display = show ? "block" : "none";
  });
}

function resetFilters() {
  document.querySelectorAll('.filter-panel input').forEach(i => i.checked = false);
  document.querySelectorAll('.filter-type').forEach(i => i.checked = true);

  document.querySelectorAll("article").forEach(a => a.style.display = "block");
}

  const burger = document.querySelector('.burger');
  const nav = document.getElementById('mainNav');
  if (!burger || !nav) return;

  const mqMobile = window.matchMedia('(max-width: 576px)');

  function closeAllDropdowns() {
    document.querySelectorAll('.nav-links .dropdown.open').forEach(li => li.classList.remove('open'));
  }

  function closeMenu() {
    nav.classList.remove('open');
    burger.classList.remove('open');
    burger.setAttribute('aria-expanded', 'false');
    closeAllDropdowns();
  }

  burger.addEventListener('click', () => {
    const isOpen = nav.classList.toggle('open');
    burger.classList.toggle('open', isOpen);
    burger.setAttribute('aria-expanded', String(isOpen));
    if (!isOpen) closeAllDropdowns();
  });

  // Dropdowns: click-to-open on mobile (desktop garde :hover)
  document.querySelectorAll('.nav-links .dropdown > a.dropdown-toggle').forEach(a => {
    a.addEventListener('click', (e) => {
      if (!mqMobile.matches) return;
      e.preventDefault();
      const li = a.closest('.dropdown');
      const willOpen = !li.classList.contains('open');
      closeAllDropdowns();
      li.classList.toggle('open', willOpen);
    });
  });

  // Fermer le menu après clic sur un lien (sauf toggles dropdown)
  nav.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
      if (!mqMobile.matches) return;
      if (a.classList.contains('dropdown-toggle')) return;
      closeMenu();
    });
  });

  // En repassant en desktop, on reset
  window.addEventListener('resize', () => {
    if (!mqMobile.matches) closeMenu();
  });


