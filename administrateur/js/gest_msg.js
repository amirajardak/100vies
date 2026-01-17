function searchMessages() {
    let input = document.getElementById('searchInput').value.toLowerCase();
    let table = document.getElementById('messagesTable');
    let trs = table.getElementsByTagName('tr');

    for (let i = 1; i < trs.length; i++) { // commencer à 1 pour sauter l'entête
        let tr = trs[i];
        let rowText = tr.innerText.toLowerCase();
        tr.style.display = rowText.includes(input) ? '' : 'none';
    }
}