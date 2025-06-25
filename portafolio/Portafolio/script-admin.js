// script-admin.js

document.addEventListener('DOMContentLoaded', function () {
    const adminBtn = document.getElementById('admin-mode-btn');
    const modal = document.getElementById('admin-login-modal');
    const closeModal = document.getElementById('close-admin-modal');
    const loginForm = document.getElementById('admin-login-form');
    const errorMsg = document.getElementById('admin-login-error');
    let isAdmin = false;

    // Mostrar modal
    adminBtn.addEventListener('click', function () {
        modal.classList.remove('hidden');
        errorMsg.textContent = '';
    });

    // Cerrar modal
    closeModal.addEventListener('click', function () {
        modal.classList.add('hidden');
    });

    // Login admin
    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const user = document.getElementById('admin-username').value;
        const pass = document.getElementById('admin-password').value;
        if (user === 'admin' && pass === '123456') {
            isAdmin = true;
            modal.classList.add('hidden');
            activarModoAdmin();
        } else {
            errorMsg.textContent = 'Usuario o contraseña incorrectos';
        }
    });

    function activarModoAdmin() {
        // Mostrar botones de editar y eliminar en los proyectos destacados
        const projects = document.querySelectorAll('.project-card');
        projects.forEach((card, idx) => {
            // Crear contenedor para los botones admin
            let adminBtns = card.querySelector('.admin-btns-container');
            if (!adminBtns) {
                adminBtns = document.createElement('div');
                adminBtns.className = 'admin-btns-container';
                adminBtns.style.position = 'absolute';
                adminBtns.style.top = '10px';
                adminBtns.style.right = '10px';
                adminBtns.style.zIndex = '2';
                card.style.position = 'relative';
                card.appendChild(adminBtns);
            }
            // Botón editar
            let editBtn = document.createElement('button');
            editBtn.textContent = 'Editar';
            editBtn.className = 'admin-action-btn edit-btn';
            editBtn.onclick = function (e) {
                e.stopPropagation();
                editarProyecto(card);
            };
            // Botón eliminar
            let deleteBtn = document.createElement('button');
            deleteBtn.textContent = 'Eliminar';
            deleteBtn.className = 'admin-action-btn delete-btn';
            deleteBtn.onclick = function (e) {
                e.stopPropagation();
                if (confirm('¿Seguro que deseas eliminar este proyecto?')) {
                    card.remove();
                }
            };
            // Agregar botones si no existen
            if (!adminBtns.hasChildNodes()) {
                adminBtns.appendChild(editBtn);
                adminBtns.appendChild(deleteBtn);
            }
        });
    }

    function editarProyecto(card) {
        // Permitir editar el título y la descripción del proyecto
        let title = card.querySelector('h3');
        let desc = card.querySelector('p');
        let newTitle = prompt('Editar título del proyecto:', title.textContent);
        if (newTitle !== null) title.textContent = newTitle;
        let newDesc = prompt('Editar descripción del proyecto:', desc.textContent);
        if (newDesc !== null) desc.textContent = newDesc;
    }

    // Hacer clic en la tarjeta redirige al primer enlace del proyecto
    document.querySelectorAll('.project-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Si el clic fue en un botón admin, no hacer nada
            if (e.target.closest('.admin-action-btn')) return;
            const link = card.querySelector('.project-link');
            if (link) {
                window.open(link.href, '_blank');
            }
        });
    });
}); 