tailwind.config = {
    theme: {
        extend: {
            fontFamily: { sans: ['Poppins', 'sans-serif'] },
                colors: {
                    brand: {
                        blue: '#2563eb',
                        light: '#3b82f6',
                        dark: '#1e40af',
                        red: '#dc2626',
                        gray: '#64748b'
               }
            }
        }
    }
}
        window.toggleMenu = function (menuId) {
            const submenu = document.getElementById(menuId + '-submenu');
            const arrow = document.getElementById(menuId + '-arrow');
            
            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                submenu.classList.add('flex');
                arrow.style.transform = 'rotate(180deg)';
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('flex');
                arrow.style.transform = 'rotate(0deg)';
            }
        };

