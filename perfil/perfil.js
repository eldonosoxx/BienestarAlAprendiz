document.addEventListener('DOMContentLoaded', function() {
    // Selecciona el mensaje
    const messageElement = document.getElementById('message');

    // Verifica si el mensaje existe en la página
    if (messageElement) {
        // Configura el tiempo en milisegundos (3000 ms = 3 segundos)
        const hideAfter = 3000;

        // Usa setTimeout para ocultar el mensaje después de un tiempo
        setTimeout(function() {
            // Agrega la clase 'hidden' para iniciar la animación de desvanecimiento
            messageElement.classList.add('hidden');
            
            // Opcional: Elimina el mensaje del DOM después de la animación
            setTimeout(function() {
                messageElement.remove();
            }, 1000); // Espera el tiempo de la animación antes de eliminar
        }, hideAfter);
    }
});

function toggleEditForm() {
    const formContainer = document.getElementById('edit-form-container');
    if (formContainer.classList.contains('show')) {
        formContainer.classList.remove('show');
        formContainer.classList.add('hide');
    } else {
        formContainer.classList.remove('hide');
        formContainer.classList.add('show');
    }
}
