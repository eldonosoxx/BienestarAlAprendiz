document.addEventListener('DOMContentLoaded', () => {
    // Función para cambiar el mes
    window.changeMonth = (delta) => {
        const params = new URLSearchParams(window.location.search);
        const currentMonth = parseInt(params.get('mes')) || new Date().getMonth() + 1;
        const currentYear = parseInt(params.get('anio')) || new Date().getFullYear();
        
        let newMonth = currentMonth + delta;
        let newYear = currentYear;

        if (newMonth < 1) {
            newMonth = 12;
            newYear -= 1;
        } else if (newMonth > 12) {
            newMonth = 1;
            newYear += 1;
        }

        params.set('mes', newMonth);
        params.set('anio', newYear);
        window.location.search = params.toString();
    };

    // Función para abrir el modal de agregar evento
    const addEventModal = document.getElementById('addEventModal');
    const editEventModal = document.getElementById('editEventModal');
    const eventDetailsModal = document.getElementById('eventDetailsModal');
    const openAddEventModalButton = document.getElementById('openAddEventModal');
    const editEventButton = document.getElementById('editEventButton');

    if (openAddEventModalButton) {
        openAddEventModalButton.addEventListener('click', () => {
            addEventModal.style.display = 'block';
        });
    }

    // Función para cerrar el modal
    const closeModal = (modalId) => {
        document.getElementById(modalId).style.display = 'none';
    };

    // Añadir evento de cierre para los modales
    document.querySelectorAll('.close').forEach(span => {
        span.addEventListener('click', () => {
            closeModal(span.closest('.modal').id);
        });
    });

    // Función para mostrar los detalles del evento
    window.showEventDetails = async (id) => {
        try {
            const response = await fetch(`eventos.php?id_evento=${id}`);
            const data = await response.json();

            if (!data) {
                alert('No se encontraron detalles para este evento.');
                return;
            }

            const detailsContent = `
                <h3 class="titulo_card">${data.nombre}</h3>
                <img class="img_card" src="img_eventos/${data.imagen}" alt="${data.nombre}">
                <p><strong>Descripción:</strong> ${data.descripcion}</p>
                <p><strong>Fecha:</strong> ${data.fecha_evento}</p>
                <p><strong>Hora:</strong> ${data.hora_evento}</p>
                <p><strong>Lugar:</strong> ${data.lugar}</p>
            `;
            document.getElementById('eventDetailsContent').innerHTML = detailsContent;
            
            if (editEventButton) {
                // Solo mostrar el botón si el usuario es admin
                editEventButton.style.display = 'block';
                editEventButton.onclick = () => openEditEventModal(id);
            }

            eventDetailsModal.style.display = 'block';
        } catch (error) {
            console.error('Error:', error);
        }
    };

    // Función para abrir el modal de editar evento
    window.openEditEventModal = async (id) => {
        try {
            const response = await fetch(`eventos.php?id_evento=${id}`);
            const data = await response.json();

            if (!data) {
                alert('No se encontraron datos para este evento.');
                return;
            }

            document.getElementById('edit_id_evento').value = data.id_evento;
            document.getElementById('edit_nombre').value = data.nombre;
            document.getElementById('edit_descripcion').value = data.descripcion;
            document.getElementById('edit_fecha_evento').value = data.fecha_evento;
            document.getElementById('edit_hora_evento').value = data.hora_evento;
            document.getElementById('edit_lugar').value = data.lugar;

            editEventModal.style.display = 'block';
        } catch (error) {
            console.error('Error:', error);
        }
    };

    // Manejo del envío del formulario de agregar evento
    const addEventForm = document.getElementById('addEventForm');
    if (addEventForm) {
        addEventForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            try {
                const response = await fetch('eventos.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.text();
                alert(data);
                location.reload();
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }

    // Manejo del envío del formulario de editar evento
    const editEventForm = document.getElementById('editEventForm');
    if (editEventForm) {
        editEventForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            try {
                const response = await fetch('eventos.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.text();
                alert(data);
                location.reload();
            } catch (error) {
                console.error('Error:', error);
            }
        });
    }
});
