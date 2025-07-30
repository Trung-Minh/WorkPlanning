// document.addEventListener('DOMContentLoaded', () => {
//     const toggleButton = document.getElementById('darkModeToggle');
//     const icon = document.getElementById('darkModeIcon');
//     const iconPath = icon?.querySelector('path');
//     const html = document.documentElement;
//     const footer = document.querySelector('footer');
//     const h2Links = document.querySelectorAll('div h2');
//     const pLinks = document.querySelectorAll('div p');
//     const ulLinks = document.querySelectorAll('div ul');

//     const sunPath = "M12 3v1m0 16v1m8.66-8.66h-1M4.34 12h-1m15.36-4.95l-.7.7M6.34 17.66l-.7.7m0-13.72l.7.7M17.66 17.66l.7.7M12 5a7 7 0 000 14a7 7 0 000-14z";
//     const moonPath = "M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z";

//     function applyTheme(theme) {
//         const isDark = theme === 'dark';
//         html.classList.toggle('dark', isDark);
//         if (iconPath) iconPath.setAttribute('d', isDark ? moonPath : sunPath);

//         // Cập nhật header
//         if (footer) {
//             footer.classList.toggle('bg-gray-950', isDark);
//             footer.classList.toggle('bg-white', !isDark);
//         }

//         // Cập nhật link màu
//         h2Links.forEach(link => {
//             link.classList.toggle('text-white', isDark);
//             link.classList.toggle('hover:text-blue-400', isDark);
//             link.classList.toggle('text-black', !isDark);
//             link.classList.toggle('hover:text-blue-600', !isDark);
//         });

//         pLinks.forEach(link => {
//             link.classList.toggle('text-white', isDark);
//             link.classList.toggle('hover:text-blue-400', isDark);
//             link.classList.toggle('text-black', !isDark);
//             link.classList.toggle('hover:text-blue-600', !isDark);
//         });

//         ulLinks.forEach(link => {
//             link.classList.toggle('text-white', isDark);
//             link.classList.toggle('hover:text-blue-400', isDark);
//             link.classList.toggle('text-black', !isDark);
//             link.classList.toggle('hover:text-blue-600', !isDark);
//         });
//     }

//     // Ban đầu load theme từ localStorage
//     const storedTheme = localStorage.getItem('theme');
//     const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
//     const initialTheme = storedTheme || (prefersDark ? 'dark' : 'light');
//     applyTheme(initialTheme);

//     // Bắt sự kiện click
//         if (toggleButton) {
//       toggleButton.addEventListener('click', applyHeaderTheme);
//     }
// });

