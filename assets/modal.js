function openModal(src) {
  document.getElementById("imgModal").style.display = "flex";
  document.getElementById("modalImg").src = src;
}

function closeModal() {
  document.getElementById("imgModal").style.display = "none";
}
