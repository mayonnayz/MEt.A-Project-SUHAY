document.addEventListener('DOMContentLoaded', function () {
    const page = document.querySelector('.page-transition');

    if (!page) {
        return;
    }

    requestAnimationFrame(() => {
        page.classList.add('page-loaded');
    });

    const links = document.querySelectorAll('a');


    links.forEach(function (link) {

        link.addEventListener('click', function (event) {

            const href = link.getAttribute('href');

            if (
                !href ||
                href === '#' ||
                href.startsWith('#') ||
                href.startsWith('javascript:') ||
                link.target === '_blank'
            ) {
                return;
            }

            if (
                link.hostname &&
                link.hostname !== window.location.hostname
            ) {
                return;
            }

            if (
                event.ctrlKey ||
                event.metaKey ||
                event.shiftKey ||
                event.altKey ||
                event.button !== 0
            ) {
                return;
            }

            event.preventDefault();

            page.classList.add('page-exit');

            setTimeout(function () {
                window.location.href = href;
            }, 250);

        });

    });

});