document.addEventListener('DOMContentLoaded', () => {
    const cursor = document.querySelector('.cursor');
    const cursorFollower = document.querySelector('.cursor-follower');
    let mouseX = 0;
    let mouseY = 0;
    let cursorX = 0;
    let cursorY = 0;
    let followerX = 0;
    let followerY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function animateCursor() {
        const dx = mouseX - cursorX;
        const dy = mouseY - cursorY;
        cursorX += dx * 0.3;
        cursorY += dy * 0.3;
        
        const followerDx = mouseX - followerX;
        const followerDy = mouseY - followerY;
        followerX += followerDx * 0.1;
        followerY += followerDy * 0.1;

        cursor.style.left = cursorX + 'px';
        cursor.style.top = cursorY + 'px';
        cursorFollower.style.left = followerX + 'px';
        cursorFollower.style.top = followerY + 'px';

        requestAnimationFrame(animateCursor);
    }

    animateCursor();

    document.querySelectorAll('a, button, .service-card, input, textarea').forEach(element => {
        element.addEventListener('mouseenter', () => {
            cursor.classList.add('active');
            cursorFollower.classList.add('active');
        });
        
        element.addEventListener('mouseleave', () => {
            cursor.classList.remove('active');
            cursorFollower.classList.remove('active');
        });
    });

    document.addEventListener('mousedown', () => {
        cursor.classList.add('click');
        cursorFollower.classList.add('click');
    });

    document.addEventListener('mouseup', () => {
        cursor.classList.remove('click');
        cursorFollower.classList.remove('click');
    });

    const heroSection = document.querySelector('.hero');
    for (let i = 0; i < 100; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDuration = (Math.random() * 3 + 2) + 's';
        particle.style.animationDelay = Math.random() * 2 + 's';
        particle.style.setProperty('--duration', (Math.random() * 3 + 2) + 's');
        heroSection.appendChild(particle);
    }

    window.addEventListener('mousemove', (e) => {
        const moveX = (e.clientX - window.innerWidth / 2) * 0.01;
        const moveY = (e.clientY - window.innerHeight / 2) * 0.01;
        
        document.querySelectorAll('.hero-content > *').forEach((element, index) => {
            element.style.transform = `translate(${moveX * (index + 1)}px, ${moveY * (index + 1)}px)`;
        });
    });

    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                if (entry.target.classList.contains('service-card')) {
                    entry.target.style.animationDelay = `${entry.target.dataset.index * 0.2}s`;
                }
            }
        });
    }, observerOptions);

    document.querySelectorAll('.service-card, .form-container').forEach((element, index) => {
        element.dataset.index = index;
        observer.observe(element);
    });

    const form = document.getElementById('contactForm');
    const successMessage = document.getElementById('successMessage');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^\+?[\d\s-]{10,}$/;

    function showError(input, message) {
        const formGroup = input.parentElement;
        const errorElement = formGroup.querySelector('.error-message');
        formGroup.classList.add('error');
        errorElement.textContent = message;
        input.classList.add('shake');
        input.style.animation = 'shake 0.5s cubic-bezier(.36,.07,.19,.97) both';
        setTimeout(() => {
            input.classList.remove('shake');
            input.style.animation = '';
        }, 500);
    }

    function clearError(input) {
        const errorElement = document.getElementById(`${input.id}Error`);
        errorElement.textContent = '';
        input.classList.remove('error');
    }

    form.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', () => validateField(input));
        input.addEventListener('blur', () => validateField(input));
        input.addEventListener('focus', () => input.parentElement.classList.add('focused'));
        input.addEventListener('blur', () => input.parentElement.classList.remove('focused'));
    });

    function validateField(input) {
        clearError(input);

        if (input.required && !input.value.trim()) {
            showError(input, 'Este campo es obligatorio');
            return false;
        }

        switch (input.type) {
            case 'email':
                if (input.value && !emailRegex.test(input.value)) {
                    showError(input, 'Por favor, ingresa un correo electrónico válido');
                    return false;
                }
                break;
            case 'tel':
                if (input.value && !phoneRegex.test(input.value)) {
                    showError(input, 'Por favor, ingresa un número de teléfono válido');
                    return false;
                }
                break;
            case 'text':
                if (input.value.length < 2) {
                    showError(input, 'El nombre debe tener al menos 2 caracteres');
                    return false;
                }
                break;
        }

        return true;
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const isNombreValid = validateField(form.querySelector('input[name="nombre"]'));
        const isEmailValid = validateField(form.querySelector('input[name="email"]'));
        const isTelefonoValid = validateField(form.querySelector('input[name="telefono"]'));
        const isMensajeValid = validateField(form.querySelector('textarea[name="mensaje"]'));

        if (isNombreValid && isEmailValid && isTelefonoValid && isMensajeValid) {
            console.log('Datos del formulario:', {
                nombre: form.querySelector('input[name="nombre"]').value.trim(),
                email: form.querySelector('input[name="email"]').value.trim(),
                telefono: form.querySelector('input[name="telefono"]').value.trim(),
                mensaje: form.querySelector('textarea[name="mensaje"]').value.trim()
            });
            
            successMessage.textContent = '¡Bienvenido a GameZone! Tu registro ha sido exitoso.';
            successMessage.classList.add('show');
            createConfetti();
            
            setTimeout(() => {
                form.reset();
                document.querySelectorAll('.form-group').forEach(group => {
                    group.classList.remove('success');
                });
                successMessage.classList.remove('show');
            }, 3000);
        }
    });

    form.querySelectorAll('input[maxlength], textarea[maxlength]').forEach(input => {
        input.addEventListener('input', () => {
            if (input.value.length > input.maxLength) {
                input.value = input.value.slice(0, input.maxLength);
            }
        });
    });

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    const glitchText = document.querySelector('.glitch-text');
    const originalText = glitchText.textContent;
    let currentText = '';
    let currentIndex = 0;

    function typeText() {
        if (currentIndex < originalText.length) {
            currentText += originalText[currentIndex];
            glitchText.textContent = currentText;
            currentIndex++;
            setTimeout(typeText, 100);
        }
    }

    const textObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                typeText();
                textObserver.unobserve(entry.target);
            }
        });
    });

    textObserver.observe(glitchText);

    function createConfetti() {
        for (let i = 0; i < 100; i++) {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + '%';
            confetti.style.animationDuration = (Math.random() * 3 + 2) + 's';
            confetti.style.animationDelay = Math.random() * 2 + 's';
            confetti.style.backgroundColor = `hsl(${Math.random() * 360}, 100%, 50%)`;
            document.body.appendChild(confetti);
            setTimeout(() => confetti.remove(), 5000);
        }
    }

    const themeToggle = document.getElementById('themeToggle');
    const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
    
    function setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
    }

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        setTheme(savedTheme);
    } else {
        setTheme('dark');
    }

    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';
        setTheme(newTheme);
        document.body.style.transition = 'all 0.3s ease';
        setTimeout(() => {
            document.body.style.transition = '';
        }, 300);
    });

    prefersDarkScheme.addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            setTheme('dark');
        }
    });
}); 