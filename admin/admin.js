const links = document.querySelectorAll('.sidebar a');
const sections = document.querySelectorAll('.section');

links.forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const target = document.querySelector(link.getAttribute('href'));

        sections.forEach(section => {
            section.style.display = 'none';
        });
        target.style.display = 'block';

        links.forEach(link => {
            link.classList.remove('active');
        });
        link.classList.add('active');
    });
});

// Mostrar la primera sección por defecto
sections.forEach(section => {
    section.style.display = 'none';
});
sections[0].style.display = 'block';
