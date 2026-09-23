/* =========================================================
   SUHAY PAGE TRANSITION
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    /*
     * Get the page content only.
     * Navbar is NOT included.
     */
    const page = document.querySelector('.page-transition');


    /*
     * If there is no page-transition element,
     * stop the script.
     */
    if (!page) {
        return;
    }


    /*
     * PAGE ENTER
     * Fade in and move the content upward.
     */
    requestAnimationFrame(() => {
        page.classList.add('page-loaded');
    });


    /*
     * PAGE EXIT
     * When clicking an internal link, animate only
     * the page content before navigating.
     */
    const links = document.querySelectorAll('a');


    links.forEach(function (link) {

        link.addEventListener('click', function (event) {

            const href = link.getAttribute('href');


            /*
             * Ignore links that:
             * - have no href
             * - use #
             * - use javascript:
             * - open in a new tab
             */
            if (
                !href ||
                href === '#' ||
                href.startsWith('#') ||
                href.startsWith('javascript:') ||
                link.target === '_blank'
            ) {
                return;
            }


            /*
             * Ignore external websites.
             */
            if (
                link.hostname &&
                link.hostname !== window.location.hostname
            ) {
                return;
            }


            /*
             * Ignore modified clicks:
             * Ctrl + Click
             * Cmd + Click
             * Shift + Click
             * Alt + Click
             * Middle mouse button
             */
            if (
                event.ctrlKey ||
                event.metaKey ||
                event.shiftKey ||
                event.altKey ||
                event.button !== 0
            ) {
                return;
            }


            /*
             * Prevent the browser from navigating immediately.
             */
            event.preventDefault();


            /*
             * Add exit animation ONLY to the content.
             */
            page.classList.add('page-exit');


            /*
             * Navigate after the animation.
             */
            setTimeout(function () {
                window.location.href = href;
            }, 250);

        });

    });

});