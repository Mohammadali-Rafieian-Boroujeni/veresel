document.addEventListener("DOMContentLoaded", () => {

    function closeModal() {
        document.querySelector(".vsl-modal")?.remove();
        document.body.style.overflow = "";
    }

    document.addEventListener("click", function (e) {

        /*
        |--------------------------------------------------------------------------
        | Quick View
        |--------------------------------------------------------------------------
        */

        const quick = e.target.closest(".vsl-quick");

        if (quick) {

            e.preventDefault();

            const product = quick.dataset.product;

            fetch(
                `${vsl_ajax.url}?action=vsl_quick_view&product_id=${product}&nonce=${vsl_ajax.nonce}`
            )
            .then(response => response.text())
            .then(html => {

                closeModal();

                document.body.insertAdjacentHTML(
                    "beforeend",
                    html
                );

                document
                    .querySelector(".vsl-modal")
                    .classList.add("active");

                document.body.style.overflow = "hidden";

                // فعال‌سازی فرم‌های ووکامرس
                jQuery(document.body).trigger("wc_fragment_refresh");

            });

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Close
        |--------------------------------------------------------------------------
        */

        if (
            e.target.classList.contains("vsl-modal-overlay") ||
            e.target.classList.contains("vsl-close")
        ) {
            closeModal();
        }

    });

    /*
    |--------------------------------------------------------------------------
    | ESC
    |--------------------------------------------------------------------------
    */

    document.addEventListener("keydown", function (e) {

        if (e.key === "Escape") {
            closeModal();
        }

    });

});