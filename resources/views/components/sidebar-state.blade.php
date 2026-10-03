<script>
    try {
        document.documentElement.classList.toggle(
            'sidebar-expanded',
            localStorage.getItem('kasir-kafe.sidebar-expanded') === 'true'
        );
    } catch {
        // Use the collapsed layout when browser storage is unavailable.
    }
</script>
