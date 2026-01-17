document.addEventListener("DOMContentLoaded", function () {

  /* MODAL SYSTEM */
  function openModal() {
    document.getElementById('addEventModal').style.display = "flex";
  }
  window.openModal = openModal;

  window.onclick = function(e) {
    if (e.target == document.getElementById('addEventModal')) {
      document.getElementById('addEventModal').style.display = "none";
    }
  };


  /* FILTER SYSTEM */
  const filterLieu = document.getElementById("filterLieu");
  const filterDate = document.getElementById("filterDate");
  const tbody = document.querySelector("#eventTable tbody");

  // Normalize date from table → YYYY-MM-DD
  function normalizeDate(text) {
    if (!text) return "";
    text = text.trim();

    // Formats like "2024-12-03" or "2024/12/03"
    const iso = text.match(/(\d{4})[-\/](\d{2})[-\/](\d{2})/);
    if (iso) return `${iso[1]}-${iso[2]}-${iso[3]}`;

    // Formats like "03/12/2024"
    const dmy = text.match(/(\d{1,2})[\/\.](\d{1,2})[\/\.](\d{4})/);
    if (dmy) {
      return `${dmy[3]}-${dmy[2].padStart(2,"0")}-${dmy[1].padStart(2,"0")}`;
    }

    // Fallback
    const d = new Date(text);
    if (!isNaN(d)) return d.toISOString().slice(0, 10);

    return text;
  }

  function filterTable() {
    const lieuValue = filterLieu.value.toLowerCase().trim();
    const dateValue = filterDate.value;  // YYYY-MM-DD
    const rows = tbody.querySelectorAll("tr");

    rows.forEach(row => {
      const cells = row.querySelectorAll("td");
      const cellLieu = cells[2].innerText.toLowerCase().trim();
      const cellDate = normalizeDate(cells[1].innerText.trim());

      let visible = true;

      // Filter by place (lieu)
      if (lieuValue !== "" && !cellLieu.includes(lieuValue)) {
        visible = false;
      }

      // Filter by date
      if (dateValue !== "" && cellDate !== dateValue) {
        visible = false;
      }

      row.style.display = visible ? "" : "none";
    });
  }

  filterLieu.addEventListener("input", filterTable);
  filterDate.addEventListener("change", filterTable);

});