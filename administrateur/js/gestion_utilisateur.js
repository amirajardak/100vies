function openModal() {
      document.getElementById('addUserModal').style.display = "flex";
    }

    window.onclick = function(e) {
      if (e.target == document.getElementById('addUserModal')) {
        document.getElementById('addUserModal').style.display = "none";
      }
    }