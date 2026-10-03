

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('appSidebar', () => ({
    sidebarExpanded: false,

    init() {
        this.sidebarExpanded = document.documentElement.classList.contains('sidebar-expanded');
    },

    toggleSidebar() {
        this.sidebarExpanded = !this.sidebarExpanded;
        document.documentElement.classList.toggle('sidebar-expanded', this.sidebarExpanded);

        try {
            localStorage.setItem('kasir-kafe.sidebar-expanded', String(this.sidebarExpanded));
        } catch {
            // Toggling still works for the current page without storage.
        }
    },
}));

Alpine.start();
