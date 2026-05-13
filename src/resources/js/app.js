document.addEventListener('DOMContentLoaded', () => {
    const root = document.querySelector('[data-dashboard-root]');

    if (!root) {
        return;
    }

    const overlay = root.querySelector('[data-sidebar-overlay]');
    const drawer = root.querySelector('[data-sidebar-drawer]');
    const toggles = root.querySelectorAll('[data-sidebar-toggle]');

    const closeSidebar = () => {
        drawer?.classList.add('-translate-x-full');
        overlay?.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    const openSidebar = () => {
        drawer?.classList.remove('-translate-x-full');
        overlay?.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            const isClosed = drawer?.classList.contains('-translate-x-full');

            if (isClosed) {
                openSidebar();
                return;
            }

            closeSidebar();
        });
    });

    overlay?.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            overlay?.classList.add('hidden');
            drawer?.classList.remove('-translate-x-full');
            document.body.classList.remove('overflow-hidden');
        } else {
            drawer?.classList.add('-translate-x-full');
        }
    });
});
