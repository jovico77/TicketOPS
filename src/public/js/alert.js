function closeAlert() {
    const alert = document.getElementById('success-alert');

    if (alert) {
        alert.classList.add('hide');

        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}

// Desaparece automáticamente después de 4 segundos
// setTimeout(closeAlert, 4000);
