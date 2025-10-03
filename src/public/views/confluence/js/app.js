function toggleMenu(menuId) {
    const menu = document.getElementById(menuId);
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

function toggleSubmenu(element) {
    const parent = element.closest('.has-children');
    const submenu = parent.querySelector('.submenu');
    const icon = element.querySelector('.menu-toggle');

    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
    icon.classList.toggle('fa-chevron-down');
    icon.classList.toggle('fa-chevron-up');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.dropdown-wrapper')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.style.display = 'none';
        });
    }
});
