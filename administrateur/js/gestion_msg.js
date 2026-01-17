 // Recherche simple
    function searchMessages() {
      const input = document.getElementById("searchInput").value.toLowerCase();
      const rows = document.querySelectorAll("#messagesTable tbody tr");

      rows.forEach(row => {
        const nom = row.cells[0].textContent.toLowerCase();
        const msg = row.cells[2].textContent.toLowerCase();
        row.style.display = (nom.includes(input) || msg.includes(input)) ? "" : "none";
      });
    }