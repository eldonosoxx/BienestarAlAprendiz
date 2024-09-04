function showLoginAlert() {
    var ventana = document.getElementById('loginVentana');
    var closeButton = document.getElementsByClassName('close')[0];

    ventana.style.display = 'block';

    // Ocultar la ventana después de 5 segundos
    setTimeout(function() {
        ventana.style.display = 'none';
    }, 3000); // 5000 ms = 5 seconds

    // Prevenir la navegación
    return false;
}

function hideLoginAlert() {
    var ventana = document.getElementById('loginVentana');
    ventana.style.display = 'none';
}
