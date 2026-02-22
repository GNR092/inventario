document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('appleTransition');
    if (!overlay) return;

    const transitionDuration = 620;

    function startTransition() {
        overlay.classList.add('active');
    }

    function hideTransition() {
        setTimeout(() => {
            overlay.classList.remove('active');
        }, 100);
    }

    // Always ensure overlay is not active when page first loads (useful for direct URL access)
    overlay.classList.remove('active');

    // Check if Inertia.js is active on this page (by looking for its root element)
    const inertiaRoot = document.getElementById('app'); 

    if (inertiaRoot) {
        // Inertia.js is active, use its event system
        // Inertia dispatches events on `document`
        document.addEventListener('inertia:start', startTransition);
        document.addEventListener('inertia:finish', hideTransition);
        document.addEventListener('inertia:cancel', hideTransition); // In case a visit is cancelled
        document.addEventListener('inertia:error', hideTransition); // In case of an error
    } else {
        // Inertia.js is NOT active (e.g., for welcome.blade.php or other non-Inertia Blade views)
        // Revert to preventing default and force full page reload with animation

        // Intercept clicks on internal links
        document.body.addEventListener('click', function(e) {
            let target = e.target;
            while (target && target.tagName !== 'A') {
                target = target.parentNode;
            }

            if (target && target.tagName === 'A') {
                const href = target.getAttribute('href');

                // Check if it's an internal link and not a hash link or external link
                if (href && !href.startsWith('#') && !href.startsWith('mailto:') && !href.startsWith('tel:') && href.startsWith(window.location.origin)) {
                    e.preventDefault();
                    startTransition();
                    setTimeout(() => {
                        window.location.href = href;
                    }, transitionDuration);
                }
            }
        });

        // Intercept internal form submissions
        document.body.addEventListener('submit', function(e) {
            const form = e.target;
            const action = form.getAttribute('action');

            if (action && !action.startsWith('#') && action.startsWith(window.location.origin) && form.dataset.noTransition !== 'true') {
                if (!form.dataset.transitioning) {
                    form.dataset.transitioning = 'true';
                    e.preventDefault();
                    startTransition();
                    setTimeout(() => {
                        form.submit();
                    }, transitionDuration);
                }
            }
        });
    }
});
