function openModal(id, nom, competences, id_user) {
    document.getElementById('modal_id_freelance').value = id;
    document.getElementById('modal_nom').value = nom;
    document.getElementById('modal_competences').value = competences;
    document.getElementById('modal_id_user').value = id_user;
    document.getElementById('updateModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('updateModal').style.display = 'none';
}