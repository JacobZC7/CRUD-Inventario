import Alpine from 'alpinejs';

window.Alpine = Alpine;

const getInitialDarkState = () => {
    try {
        const saved = localStorage.getItem('inventario-theme');

        if (saved === 'dark') {
            return true;
        }

        if (saved === 'light') {
            return false;
        }

        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    } catch {
        return document.documentElement.classList.contains('dark');
    }
};

const applyTheme = (isDark) => {
    document.documentElement.classList.toggle('dark', isDark);

    try {
        localStorage.setItem('inventario-theme', isDark ? 'dark' : 'light');
    } catch {
        // Storage might be disabled in private browsing contexts.
    }
};

Alpine.store('theme', {
    dark: getInitialDarkState(),
    toggle() {
        this.dark = !this.dark;
        applyTheme(this.dark);
    },
});

Alpine.start();
