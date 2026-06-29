// Поведение бокового меню: сворачивание до иконок с персистом в localStorage
// и off-canvas на узких экранах.

const COLLAPSE_KEY = 'ap-sidebar-collapsed';

function initSidebar() {
    const root = document.documentElement;

    // Сворачивание/разворачивание (десктоп) + персист.
    document.querySelectorAll('[data-ap-sidebar-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const collapsed = root.classList.toggle('ap-sidebar-collapsed');
            try {
                localStorage.setItem(COLLAPSE_KEY, collapsed ? '1' : '0');
            } catch (e) {}
        });
    });

    // Off-canvas на мобильных: и гамбургер, и backdrop переключают открытие.
    document.querySelectorAll('[data-ap-sidebar-mobile]').forEach((el) => {
        el.addEventListener('click', () => {
            root.classList.toggle('ap-sidebar-open');
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebar);
} else {
    initSidebar();
}
