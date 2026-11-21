
document.addEventListener("DOMContentLoaded", function () {

    // ==========================================
    // SEARCH AND FILTER
    // ==========================================

    const searchInput = document.getElementById("searchInput");
    const filterType = document.getElementById("filterType");
    const cards = document.querySelectorAll(".donation-card");
    const noResults = document.getElementById("noResults");

    function filterDonations() {

        const search = searchInput.value.toLowerCase().trim();
        const filter = filterType.value.toLowerCase();

        let visibleCount = 0;

        cards.forEach(card => {

            const name = card.dataset.name || "";
            const description = card.dataset.description || "";
            const source = card.dataset.source || "";
            const type = card.dataset.type || "";
            const reference = card.dataset.reference || "";

            const matchSearch =
                name.includes(search) ||
                description.includes(search) ||
                source.includes(search) ||
                type.includes(search) ||
                reference.includes(search);

            const matchFilter =
                filter === "" || type === filter;

            if (matchSearch && matchFilter) {

                card.classList.remove("hidden");
                visibleCount++;

            } else {

                card.classList.add("hidden");

            }

        });

        if (noResults) {
            noResults.classList.toggle(
                "hidden",
                visibleCount > 0 || cards.length === 0
            );
        }

    }

    searchInput.addEventListener("input", filterDonations);
    filterType.addEventListener("change", filterDonations);


    // ==========================================
    // DONATION DETAILS MODAL
    // ==========================================

    const modalBackdrop =
        document.getElementById("donationModalBackdrop");

    const modalContent =
        document.getElementById("donationModalContent");

    const modalBody =
        document.getElementById("donationModalBody");


    document.querySelectorAll(".view-donation-btn").forEach(button => {

        button.addEventListener("click", function (e) {

            e.stopPropagation();

            const ngo = this.dataset.ngo || "N/A";

            const type = (this.dataset.type || "").toUpperCase();

            const description = this.dataset.description || "N/A";

            const amount = this.dataset.amount || "";

            const unit = this.dataset.unit || "";

            const source = this.dataset.source || "N/A";

            const reference = this.dataset.reference || "N/A";

            const rawDate = this.dataset.date || "";

            const status = (this.dataset.status || "N/A")
                .replaceAll("_", " ");

            const typeLabels = {
                MONETARY: "Monetary",
                ONLINE_MONETARY: "Online Monetary",
                CONSUMABLE: "Consumable",
                REUSABLE: "Reusable"
            };

            const displayType = typeLabels[type] || type || "N/A";

            let formattedDate = "N/A";

            if (rawDate) {

                const parsedDate = new Date(rawDate + "T00:00:00");

                if (!isNaN(parsedDate.getTime())) {

                    formattedDate = parsedDate.toLocaleDateString(
                        "en-US",
                        {
                            month: "long",
                            day: "2-digit",
                            year: "numeric"
                        }
                    );

                }

            }

            let amountHTML = "";

            if (type === "MONETARY" || type === "ONLINE_MONETARY") {

                const formattedAmount = amount !== ""
                    ? "₱" + Number(amount).toLocaleString("en-PH", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })
                    : "N/A";

                amountHTML = `
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Amount</p>
                        <p class="text-sm font-semibold text-[#0e243a]">
                            ${escapeHTML(formattedAmount)}
                        </p>
                    </div>
                `;

            }

            let unitHTML = "";

            if (type === "CONSUMABLE" || type === "REUSABLE") {

                unitHTML = `
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Unit</p>
                        <p class="text-sm font-semibold text-[#0e243a]">
                            ${escapeHTML(unit || "N/A")}
                        </p>
                    </div>
                `;

            }

            modalBody.innerHTML = `

                <div class="bg-[#f7f9fb] rounded-2xl p-4">

                    <p class="text-xs text-gray-500 mb-1">
                        NGO Name
                    </p>

                    <p class="font-bold text-[#0e243a]">
                        ${escapeHTML(ngo)}
                    </p>

                </div>


                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <p class="text-xs text-gray-500 mb-1">
                            Donation Type
                        </p>

                        <p class="text-sm font-semibold text-[#0e243a]">
                            ${escapeHTML(displayType)}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs text-gray-500 mb-1">
                            Status
                        </p>

                        <p class="text-sm font-semibold text-[#0e243a]">
                            ${escapeHTML(status)}
                        </p>
                    </div>


                    ${amountHTML}

                    ${unitHTML}


                    <div>
                        <p class="text-xs text-gray-500 mb-1">
                            Date
                        </p>

                        <p class="text-sm font-semibold text-[#0e243a]">
                            ${escapeHTML(formattedDate)}
                        </p>
                    </div>


                    <div>
                        <p class="text-xs text-gray-500 mb-1">
                            Source
                        </p>

                        <p class="text-sm font-semibold text-[#0e243a]">
                            ${escapeHTML(source)}
                        </p>
                    </div>


                    <div class="col-span-2">
                        <p class="text-xs text-gray-500 mb-1">
                            Reference Number
                        </p>

                        <p class="text-sm font-semibold text-[#0e243a] break-all">
                            ${escapeHTML(reference)}
                        </p>
                    </div>

                </div>


                <div>

                    <p class="text-xs text-gray-500 mb-2">
                        Description
                    </p>

                    <div class="bg-[#f7f9fb] rounded-2xl p-4">

                        <p class="text-sm text-gray-700 break-words">
                            ${escapeHTML(description)}
                        </p>

                    </div>

                </div>

            `;


            // OPEN MODAL

            modalBackdrop.classList.remove("hidden");
            modalBackdrop.classList.add("flex");

            modalContent.classList.remove("modal-exit");
            modalContent.classList.add("modal-enter");

            document.body.style.overflow = "hidden";

        });

    });


    // ==========================================
    // CLOSE DONATION MODAL
    // ==========================================

    window.closeDonationModal = function () {

        modalContent.classList.remove("modal-enter");
        modalContent.classList.add("modal-exit");

        setTimeout(() => {

            modalBackdrop.classList.remove("flex");
            modalBackdrop.classList.add("hidden");

            modalContent.classList.remove("modal-exit");

            document.body.style.overflow = "";

        }, 200);

    };


    // CLOSE WHEN CLICKING OUTSIDE

    modalBackdrop.addEventListener("click", function (e) {

        if (e.target === modalBackdrop) {
            closeDonationModal();
        }

    });


    // CLOSE USING ESCAPE KEY

    document.addEventListener("keydown", function (e) {

        if (
            e.key === "Escape" &&
            !modalBackdrop.classList.contains("hidden")
        ) {

            closeDonationModal();

        }

    });


    // ==========================================
    // HTML ESCAPE
    // ==========================================

    function escapeHTML(value) {

        return String(value).replace(/[&<>"']/g, char => ({

            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#039;"

        })[char]);

    }

});


// ==========================================
// LOGOUT MODAL
// ==========================================

function openLogoutModal() {

    document.getElementById("logoutModal")
        .classList.remove("hidden");

    document.getElementById("logoutModal")
        .classList.add("flex");

}

function closeLogoutModal() {

    document.getElementById("logoutModal")
        .classList.add("hidden");

    document.getElementById("logoutModal")
        .classList.remove("flex");

}
