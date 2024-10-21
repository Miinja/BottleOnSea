document.getElementById('announceForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: form.method,
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('message').textContent = data;
        form.reset();
    })
    .catch(error => {
        console.error('Erreur lors de la soumission:', error);
        document.getElementById('message').textContent = 'Erreur lors de la soumission de l\'annonce.';
    });
});
