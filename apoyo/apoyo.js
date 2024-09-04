document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("form-modal");
    var editModal = document.getElementById("edit-modal");
    var openFormButtons = document.querySelectorAll(".btn-open-form");
    var openEditButtons = document.querySelectorAll(".btn-edit");

    // Abre el modal de formulario al hacer clic en los botones de agregar
    openFormButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var apoyoId = this.getAttribute('data-apoyo-id');
            var apoyoNombre = this.getAttribute('data-apoyo-nombre');
            
            document.getElementById('id_formulario_apoyo').value = apoyoId;
            document.getElementById('nombre_apoyo').value = apoyoNombre;
            
            modal.style.display = "block";
        });
    });

    // Abre el modal de edición al hacer clic en los botones de editar
    openEditButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var apoyoId = this.getAttribute('data-apoyo-id');
            var apoyoNombre = this.getAttribute('data-apoyo-nombre');
            var apoyoDescripcion = this.getAttribute('data-apoyo-descripcion');
            var apoyoImagen = this.getAttribute('data-apoyo-imagen');
            
            document.getElementById('edit_id_apoyo').value = apoyoId;
            document.getElementById('edit_nombre').value = apoyoNombre;
            document.getElementById('edit_descripcion').value = apoyoDescripcion;

            // Mostrar la imagen actual en el modal de edición
            var imagePreview = document.getElementById('image-preview');
            if (imagePreview) {
                imagePreview.src = 'apoyo/img_apoyos/' + apoyoImagen; // Mostrar imagen actual
            }
            
            editModal.style.display = "block";
        });
    });

    // Cierra el modal al hacer clic en el botón de cerrar
    document.querySelectorAll(".close-button, .close-button-edit").forEach(function(button) {
        button.addEventListener('click', function() {
            modal.style.display = "none";
            editModal.style.display = "none";
        });
    });

    // Cierra el modal al hacer clic fuera del modal
    window.addEventListener('click', function(event) {
        if (event.target == modal || event.target == editModal) {
            modal.style.display = "none";
            editModal.style.display = "none";
        }
    });

    // Vista previa de la imagen en el modal de edición
    document.getElementById('edit_imagen').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var imagePreview = document.getElementById('image-preview');
        
        if (file) {
            var reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
            }

            reader.readAsDataURL(file);
        } else {
            imagePreview.src = ''; // Limpia la vista previa si no hay archivo
        }
    });
});
