(function () {
    var header = document.getElementById('siteHeader');
    if (!header) return;

    var ticking = false;

    function updateHeader() {
        header.classList.toggle('is-scrolled', window.scrollY > 18);
        ticking = false;
    }

    function requestUpdate() {
        if (ticking) return;
        ticking = true;
        window.requestAnimationFrame(updateHeader);
    }

    window.addEventListener('scroll', requestUpdate, { passive: true });
    updateHeader();
})();
