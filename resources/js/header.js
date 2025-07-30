// PT Giao dien swap sang <-> toi
document.addEventListener('DOMContentLoaded', () => {
    const toggleButton = document.getElementById('darkModeToggle');
    const icon = document.getElementById('darkModeIcon');
    const iconPath = icon.querySelector('path');
    const html = document.documentElement;

    const sunPath = "M12 3v1m0 16v1m8.66-8.66h-1M4.34 12h-1m15.36-4.95l-.7.7M6.34 17.66l-.7.7m0-13.72l.7.7M17.66 17.66l.7.7M12 5a7 7 0 000 14a7 7 0 000-14z";
    const moonPath = "M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z";

    if (localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        html.classList.add('dark');
        iconPath.setAttribute("d", moonPath);
    } else {
        html.classList.remove('dark');
        iconPath.setAttribute("d", sunPath);
    }

    toggleButton.addEventListener('click', () => {
        html.classList.toggle('dark');
        const isDark = html.classList.contains('dark');
        iconPath.setAttribute("d", isDark ? moonPath : sunPath);
        localStorage.theme = isDark ? 'dark' : 'light';
    });
});


// // Cập nhật màu header theo theme đã lưu
// header
document.addEventListener('DOMContentLoaded', () => {
  const header = document.querySelector('header');
  const toggleButton = document.getElementById('darkModeToggle'); // nếu bạn có nút toggle
  const navLinks = document.querySelectorAll('.a');
  const link1 = document.querySelectorAll('.b')
  
  if (header) {
    const applyHeaderTheme = () => {
      const isDark = localStorage.theme === 'dark';
      const isLight = localStorage.theme === 'light';
      if (isDark) {
        header.classList.add('bg-gray-950');
        header.classList.remove('bg-white');
      };
      if(isLight) {
        header.classList.add('bg-white');
        header.classList.remove('bg-gray-950');
      }

      navLinks.forEach(link => {
      if (isDark) {
        link.classList.add( 'text-white', 'hover:text-blue-400');
        link.classList.remove( 'text-black', 'hover:text-blue-600');
      } else {
        link.classList.add( 'text-black', 'hover:text-blue-600');
        link.classList.remove( 'text-white', 'hover:text-blue-400');
      }
    });

         link1.forEach(link => {
      if (isDark) {
        header.classList.add('bg-gray-950');
        header.classList.remove('bg-white');
      } else {
        header.classList.add('bg-white');
        header.classList.remove('bg-gray-950');
      }
    });

    };

    applyHeaderTheme();

    if (toggleButton) {
      toggleButton.addEventListener('click', applyHeaderTheme);
    }
  }
});


// footer
document.addEventListener('DOMContentLoaded', () => {
  const footer = document.querySelector('footer');
  const toggleButton = document.getElementById('darkModeToggle'); // nếu bạn có nút toggle

  const divLinks = document.querySelectorAll('.a')
  
  if (footer) {
    const applyHeaderTheme = () => {
      const isDark = localStorage.theme === 'dark';
      const isLight = localStorage.theme === 'light';
      if (isDark) {
        footer.classList.add('bg-gray-950');
        footer.classList.remove('bg-white');
      };
      if(isLight) {
        footer.classList.add('bg-white');
        footer.classList.remove('bg-gray-950');
      }

      divLinks.forEach(link => {
      if (isDark) {
        link.classList.add( 'text-white', 'hover:text-blue-400');
        link.classList.remove( 'text-black', 'hover:text-blue-600');
      } else {
        link.classList.add( 'text-black', 'hover:text-blue-600');
        link.classList.remove( 'text-white', 'hover:text-blue-400');
      }
    });


    };

    applyHeaderTheme();

    if (toggleButton) {
      toggleButton.addEventListener('click', applyHeaderTheme);
    }
  }
});