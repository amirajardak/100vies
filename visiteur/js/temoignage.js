const openBtn = document.querySelector('.btn-open-form');
const modal = document.getElementById('temoignageModal');
const closeBtn = document.querySelector('.close-modal');

openBtn.onclick = () => {
    modal.classList.add('active');
    openBtn.style.display = 'none'; // cacher le bouton
};

closeBtn.onclick = () => {
    modal.classList.remove('active');
    openBtn.style.display = 'flex'; // réafficher le bouton
};

modal.onclick = e => {
    if(e.target === modal){
        modal.classList.remove('active');
        openBtn.style.display = 'flex'; // réafficher le bouton si clic hors modal
    }
};