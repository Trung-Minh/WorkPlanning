// PT Giao dien swap sang <-> toi
    const toggleButton = document.getElementById('darkModeToggle');
    const icon = document.getElementById('darkModeIcon');
    const iconPath = icon.querySelector('path');
    const html = document.documentElement;

    const sunPath = "M12 3v1m0 16v1m8.66-8.66h-1M4.34 12h-1m15.36-4.95l-.7.7M6.34 17.66l-.7.7m0-13.72l.7.7M17.66 17.66l.7.7M12 5a7 7 0 000 14a7 7 0 000-14z";
    const moonPath = "M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z";

    // Khởi tạo theo localStorage hoặc system
    if (localStorage.theme === 'dark' ||
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        iconPath.setAttribute("d", moonPath);
    } else {
        html.classList.remove('dark');
        iconPath.setAttribute("d", sunPath);
    }

    // Bấm để chuyển
    toggleButton.addEventListener('click', () => {
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        iconPath.setAttribute("d", isDark ? moonPath : sunPath);
        localStorage.theme = isDark ? 'dark' : 'light';
    });