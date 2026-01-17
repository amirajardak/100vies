function getCheckedValues(selector) {
  return Array.from(document.querySelectorAll(selector + ":checked")).map(el => el.value);
}

function parseArticleDate(dateStr) {
  // gère "2026-01-17" et "2026-01-17 10:20:30"
  if (!dateStr) return null;
  const normalized = dateStr.trim().replace(" ", "T");
  const d = new Date(normalized);
  return isNaN(d.getTime()) ? null : d;
}

function isInDateRange(articleDate, mode) {
  if (!articleDate) return false;

  const now = new Date();
  const startOfToday = new Date(now.getFullYear(), now.getMonth(), now.getDate());

  if (mode === "today") {
    return articleDate >= startOfToday;
  }

  if (mode === "week") {
    // 7 derniers jours
    const sevenDaysAgo = new Date(now);
    sevenDaysAgo.setDate(now.getDate() - 7);
    return articleDate >= sevenDaysAgo;
  }

  if (mode === "month") {
    // depuis le début du mois
    const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
    return articleDate >= startOfMonth;
  }

  return true;
}

function applyFilters() {
  const selectedTypes = getCheckedValues(".filter-type");
  const selectedDates = getCheckedValues(".filter-date");
  const selectedBlood = getCheckedValues(".filter-blood");

  const articles = document.querySelectorAll(".feed-content article");

  articles.forEach(article => {
    const type = article.dataset.type;          // appel / campagne / info
    const blood = article.dataset.blood;        // ex: A+, O-, all
    const dateStr = article.dataset.date;       // ex: 2026-01-17 ou datetime
    const articleDate = parseArticleDate(dateStr);

    // --- TYPE ---
    const matchType = selectedTypes.length === 0 || selectedTypes.includes(type);

    // --- BLOOD ---
    // si aucun groupe sélectionné => on accepte tout
    // si groupe sélectionné => on accepte "all" ou le groupe exact
    const matchBlood =
      selectedBlood.length === 0 ||
      blood === "all" ||
      selectedBlood.includes(blood);

    // --- DATE ---
    let matchDate = true;
    if (selectedDates.length > 0) {
      // si plusieurs cochés, on accepte si l’un des modes matche
      matchDate = selectedDates.some(mode => isInDateRange(articleDate, mode));
    }

    const visible = matchType && matchBlood && matchDate;
    article.style.display = visible ? "" : "none";
  });
}

function resetFilters() {
  document.querySelectorAll(".filter-panel input[type='checkbox']").forEach(cb => {
    cb.checked = false;
  });

  // par défaut, tu avais Type cochés dans le HTML -> tu peux les recocher ici si tu veux :
  document.querySelectorAll(".filter-type").forEach(cb => cb.checked = true);

  // réafficher tout
  document.querySelectorAll(".feed-content article").forEach(article => {
    article.style.display = "";
  });
}
