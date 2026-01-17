const fileInput = document.getElementById('avatarInput');
const imgPreview = document.querySelector('.ui-w-80');
const resetBtn = document.getElementById('resetAvatar');
const defaultAvatar = imgPreview.src;

fileInput.addEventListener('change', function(){
  const file = this.files[0];
  if(file){
    const reader = new FileReader();
    reader.onload = e => imgPreview.src = e.target.result;
    reader.readAsDataURL(file);
  }
});
resetBtn.addEventListener('click', () => {
  imgPreview.src = defaultAvatar;
  fileInput.value = "";
});

// onglets
const tabs = document.querySelectorAll('.tab-link');
const contents = document.querySelectorAll('.tab-content');
tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    contents.forEach(c => c.style.display='none');
    document.getElementById(tab.dataset.tab).style.display='block';
  });
});
