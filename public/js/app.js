(() => {
    const navLinks = document.querySelectorAll('nav a[href^="#"]');

    navLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            const href = link.getAttribute('href');
            if (!href) return;
            const target = document.querySelector(href);
            if (!target) return;

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    const slugInputs = document.querySelectorAll('input[name="slug"]');
    slugInputs.forEach((input) => {
        input.addEventListener('blur', () => {
            input.value = input.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-');
        });
    });
})();
