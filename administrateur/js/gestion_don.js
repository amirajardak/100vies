const modal = document.getElementById('addDonModal');

    function openModal() {
      modal.style.display = 'flex';
    }

    window.onclick = function(e) {
      if (e.target == modal) modal.style.display = 'none';
    }