const map = L.map('map');
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

const markers = [];
const bounds = L.latLngBounds([]);

centres.forEach((c, i) => {
  if(c.lat && c.lng && c.lat != 0){
    const marker = L.marker([c.lat, c.lng])
      .addTo(map)
      .bindPopup(`<strong>${c.nom_centre}</strong><br>${c.adresse_centre}`);
    markers.push(marker);
    bounds.extend([c.lat, c.lng]);
  }
});

if(bounds.isValid()){
  map.fitBounds(bounds, {padding:[50,50]});
}

// Interaction hover
document.querySelectorAll('.centre-card').forEach(card => {
  card.addEventListener('mouseenter', () => {
    const i = card.dataset.index;
    if(markers[i]){
      markers[i].openPopup();
      map.panTo(markers[i].getLatLng());
    }
  });
  card.addEventListener('click', () => {
    const i = card.dataset.index;
    if(markers[i]){
      map.setView(markers[i].getLatLng(), 14, {animate:true});
      markers[i].openPopup();
    }
  });
});