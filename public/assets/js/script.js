document.addEventListener('DOMContentLoaded', () => {
    // --- Header Scroll Effect ---
    const header = document.querySelector('header');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // --- Smooth Scrolling for Navigation Links ---
    document.querySelectorAll('nav ul li a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            // Only if it's an internal link
            if (this.hash !== '') {
                // Check if target element exists
                try {
                    const targetElement = document.querySelector(this.hash);
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                        // Close mobile menu after clicking a link
                        if (navUl.classList.contains('active')) {
                            navUl.classList.remove('active');
                            menuToggle.classList.remove('active');
                        }
                    }
                } catch (error) {
                    // console.warn("Could not find target for smooth scroll:", this.hash);
                    // Allow default behavior if hash target is not found (e.g., external links)
                }
            }
        });
    });

    // --- Mobile Menu Toggle ---
    const menuToggle = document.querySelector('.menu-toggle');
    const navUl = document.querySelector('nav ul');

    if (menuToggle && navUl) {
        menuToggle.addEventListener('click', () => {
            navUl.classList.toggle('active');
            menuToggle.classList.toggle('active');
        });
    }

    // --- Scroll Animation for Sections ---
    const sections = document.querySelectorAll('.section');
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15 // Trigger when 15% of the section is visible
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                observer.unobserve(entry.target); // Stop observing once animated
            }
        });
    }, observerOptions);

    sections.forEach(section => {
        observer.observe(section);
    });

    // --- Lightbox for Gallery Images ---
    const galleryItems = document.querySelectorAll('.gallery-item img');
    const lightbox = document.createElement('div');
    lightbox.classList.add('lightbox');
    document.body.appendChild(lightbox);

    galleryItems.forEach(item => {
        item.addEventListener('click', () => {
            lightbox.classList.add('active');
            lightbox.style.display = 'flex'; // Make sure it's flex for centering
            const img = document.createElement('img');
            img.src = item.src;
            img.classList.add('lightbox-content');

            while (lightbox.firstChild) {
                lightbox.removeChild(lightbox.firstChild);
            }

            const caption = document.createElement('div');
            caption.classList.add('lightbox-caption');
            caption.textContent = item.nextElementSibling.querySelector('h3').textContent;

            const closeBtn = document.createElement('span');
            closeBtn.classList.add('lightbox-close');
            closeBtn.innerHTML = '&times;';
            closeBtn.addEventListener('click', () => {
                lightbox.classList.remove('active');
                lightbox.style.display = 'none';
            });

            lightbox.appendChild(closeBtn);
            lightbox.appendChild(img);
            lightbox.appendChild(caption);
        });
    });

    lightbox.addEventListener('click', (e) => {
        // Close lightbox if clicking outside the image or caption
        if (e.target.classList.contains('lightbox')) {
            lightbox.classList.remove('active');
            lightbox.style.display = 'none';
        }
    });


    // --- Form Submission Handling for Kontak ---
    const contactForm = document.querySelector('.contact-form form');
    const formAlert = document.createElement('div'); // Create a div for alerts
    formAlert.classList.add('alert'); // Add alert class
    contactForm.parentNode.insertBefore(formAlert, contactForm.nextSibling); // Insert after form

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

            const formData = new FormData(this);
            fetch('kontak.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                formAlert.style.display = 'block'; // Show alert
                if (data.includes("berhasil terkirim")) {
                    formAlert.classList.remove('error');
                    formAlert.classList.add('success');
                    formAlert.textContent = 'Pesan Anda telah terkirim! Terima kasih.';
                    this.reset();
                } else {
                    formAlert.classList.remove('success');
                    formAlert.classList.add('error');
                    formAlert.textContent = 'Terjadi kesalahan: ' + data;
                }
                setTimeout(() => { // Hide alert after 5 seconds
                    formAlert.style.display = 'none';
                }, 5000);
            })
            .catch(error => {
                console.error('Error:', error);
                formAlert.style.display = 'block';
                formAlert.classList.remove('success');
                formAlert.classList.add('error');
                formAlert.textContent = 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.';
                setTimeout(() => {
                    formAlert.style.display = 'none';
                }, 5000);
            });
        });
    }
});