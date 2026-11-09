if (typeof AOS !== 'undefined') {
    AOS.init({
        duration: 800,
        once: false,
        offset: 100,
        easing: 'ease-out-cubic'
    });
}

const ngoData = window.ngoData || [];
const assets = window.suhayAssets || {};

function escapeHtml(value) {
    return String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function closeResultModal() {

    const modal =
        document.getElementById('resultModal');

    if (modal) {

        modal.remove();

    }

}


function showDonationResult(success, message) {
    closeModal();

    closeResultModal();

    const resultModal = document.createElement('div');
    resultModal.id = 'resultModal';

    resultModal.className =
        'fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4';

    resultModal.innerHTML = `
        <div class="bg-white rounded-2xl p-8 max-w-md w-full text-center shadow-xl">

            <div class="text-5xl mb-4">
                ${success ? '&#10003;' : '&#10007;'}
            </div>

            <h2 class="text-2xl font-bold mb-3 text-[#0e243a]">
                ${success ? 'Donation Submitted!' : 'Submission Failed'}
            </h2>

            <p class="text-gray-600 mb-6">
                ${message}
            </p>

            <button
                type="button"
                onclick="closeResultModal()"
                class="px-8 py-3 rounded-full bg-[#f2c94c] text-[#0e243a] font-bold hover:bg-[#d39a11] transition"
            >
                ${success ? 'Done' : 'Close'}
            </button>

        </div>
    `;

    document.body.appendChild(resultModal);

    resultModal.addEventListener('click', function (e) {
        if (e.target === resultModal) {
            closeResultModal();
        }
    });
}

function closeLetterModal() {

    const modal =
        document.getElementById('letterModal');

    if (modal) {

        modal.remove();

    }

}


function buildDonorName(details) {

    if (details.anonymous) {
        return 'Generous Friend';
    }

    const cleaned = (details.source || '')
        .trim()
        .replace(/^from\s+/i, '')
        .trim();

    return cleaned !== '' ? cleaned : 'Generous Friend';
}


function buildDonationPhrase(details) {

    const isMonetary =
        details.type === 'MONETARY' ||
        details.type === 'ONLINE_MONETARY';

    const description =
        escapeHtml(details.description);

    if (isMonetary) {

        const amount = Number(details.amount || 0).toLocaleString(
            'en-PH',
            { minimumFractionDigits: 2, maximumFractionDigits: 2 }
        );

        return `your generous monetary gift of
            <strong>&#8369;${amount}</strong>
            (<strong>${description}</strong>)`;
    }

    const quantity = escapeHtml(details.amount);
    const unit = escapeHtml(details.unit || '');

    return `your kind donation of
        <strong>${quantity} ${unit}</strong>
        of <strong>${description}</strong>`;
}


function showAcknowledgmentLetter(details, onContinue) {
    closeLetterModal();

    const donorName = escapeHtml(buildDonorName(details));
    const ngoName = escapeHtml(details.ngoName || 'our partner NGO');
    const donationPhrase = buildDonationPhrase(details);

    const dateText = new Date().toLocaleDateString('en-PH', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        timeZone: 'Asia/Manila'
    });

    const modal = document.createElement('div');
    modal.id = 'letterModal';

    modal.className =
        'fixed inset-0 z-[110] flex items-center justify-center bg-black/60 p-4';

    modal.innerHTML = `
        <div class="modal-enter bg-gray-100 w-full max-w-2xl max-h-[92vh] overflow-y-auto rounded-2xl shadow-2xl">

            <!-- ================= LETTER ================= -->

            <div
                id="donationLetter"
                class="bg-white"
                style="font-family: 'Poppins', sans-serif; border-top: 8px solid #f2c94c; padding: 40px 48px;"
            >

                <!-- LOGO -->

                <div style="text-align:center; margin-bottom: 8px;">

                    <img
                        src="${assets.logo}"
                        alt="Project SUHAY Logo"
                        style="height: 90px; margin: 0 auto; object-fit: contain;"
                        crossorigin="anonymous"
                    >

                    <div style="font-family: 'Poppins', sans-serif; font-weight: 800; letter-spacing: 4px; color: #0e243a; font-size: 18px; margin-top: 8px;">
                        PROJECT SUHAY
                    </div>

                    <div style="width: 70px; height: 3px; background: #f2c94c; margin: 12px auto 0;"></div>

                </div>


                <!-- DATE -->

                <p style="text-align:right; color:#6b7280; font-size: 14px; margin: 24px 0;">
                    ${dateText}
                </p>


                <!-- SALUTATION -->

                <p style="font-size: 18px; color:#0e243a; margin-bottom: 18px;">
                    Dear <strong>${donorName}</strong>,
                </p>


                <!-- BODY -->

                <p style="font-size: 15px; line-height: 1.8; color:#1f2937; margin-bottom: 14px; text-align: justify;">
                    From the bottom of our hearts, thank you for ${donationPhrase}
                    to <strong>${ngoName}</strong>. Your generosity reached us at
                    exactly the right moment, and it will go a long way in
                    helping families and communities who need it most.
                </p>

                <p style="font-size: 15px; line-height: 1.8; color:#1f2937; margin-bottom: 14px; text-align: justify;">
                    Behind every gift is a person who chose to care, and that
                    is what makes the difference. Because of you, someone will
                    feel less alone, someone will have something to hope for,
                    and someone will be reminded that kindness is still alive.
                    You are not just giving; you are walking beside those we serve.
                </p>

                <p style="font-size: 15px; line-height: 1.8; color:#1f2937; margin-bottom: 14px; text-align: justify;">
                    Your donation is currently <strong>pending review</strong>
                    and will be confirmed by our team shortly. Please keep this
                    letter as a token of our sincere gratitude.
                </p>

                <p style="font-size: 15px; line-height: 1.8; color:#1f2937; margin-bottom: 28px; text-align: justify;">
                    Maraming, maraming salamat po. Together, we move
                    <em>Suhay</em>, side by side, toward a brighter tomorrow.
                </p>


                <!-- SIGN-OFF -->

                <p style="font-size: 15px; color:#1f2937; margin-bottom: 4px;">
                    With heartfelt gratitude,
                </p>

                <p style="font-size: 20px; font-weight: bold; color:#0e243a; margin-bottom: 2px;">
                    Project SUHAY
                </p>

                <p style="font-size: 13px; color:#6b7280;">
                    on behalf of ${ngoName}
                </p>

            </div>


            <!-- ================= BUTTONS ================= -->

            <div class="flex flex-col sm:flex-row justify-end gap-3 p-5 bg-gray-100">

                <button
                    type="button"
                    id="downloadLetterBtn"
                    class="px-6 py-3 rounded-full bg-[#0e243a] text-white font-semibold hover:bg-[#1b3b5a] transition"
                >
                    &#11015; Download Letter (PDF)
                </button>

                <button
                    type="button"
                    id="continueLetterBtn"
                    class="px-8 py-3 rounded-full bg-[#f2c94c] text-[#0e243a] font-bold hover:bg-[#d39a11] transition"
                >
                    Continue
                </button>

            </div>

        </div>
    `;

    document.body.appendChild(modal);

    document
        .getElementById('downloadLetterBtn')
        .addEventListener('click', function () {

            const button = this;
            const letter = document.getElementById('donationLetter');

            if (typeof html2pdf === 'undefined') {
                alert('PDF generator failed to load. Please refresh the page and try again.');
                return;
            }

            const originalText = button.innerHTML;
            button.disabled = true;
            button.innerHTML = 'Preparing PDF...';

            const safeName = buildDonorName(details)
                .replace(/[^a-z0-9]+/gi, '-')
                .toLowerCase();

            html2pdf()
                .set({
                    margin: 0,
                    filename: `SUHAY-Acknowledgment-Letter-${safeName}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                })
                .from(letter)
                .save()
                .catch(() => {
                    alert('Failed to generate the PDF. Please try again.');
                })
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
        });

    document
        .getElementById('continueLetterBtn')
        .addEventListener('click', function () {

            closeLetterModal();

            if (typeof onContinue === 'function') {
                onContinue();
            }
        });
}

function openModal(id) {

    const ngo = ngoData.find(n => n.id == id) || {};

    const accounts = ngo.bank_accounts || [];

    let accountDisplay = '';

    if (accounts.length > 0) {

        accounts.forEach(account => {

            accountDisplay += `
                <div class="border rounded-xl p-4">

                    <div class="font-bold text-[#0e243a]">
                        ${account.type}
                    </div>

                    <div class="text-sm mt-2">

                        <p>
                            <span class="text-gray-500">
                                Account Name:
                            </span>

                            ${account.account_name}
                        </p>

                        <p>
                            <span class="text-gray-500">
                                Account Number:
                            </span>

                            ${account.account_number}
                        </p>

                    </div>

                </div>
            `;

        });

    } else {

        accountDisplay = `
            <p class="text-gray-500 text-center">
                No online payment accounts available.
            </p>
        `;
    }

    const logoUrl = ngo.logo_url || assets.logo;

    document.getElementById('modalContent').innerHTML = `

        <div class="p-6 md:p-8 relative">


            <!-- CLOSE BUTTON -->

            <button
                type="button"
                onclick="closeModal()"
                class="absolute top-4 right-5 text-gray-500 hover:text-gray-800 text-3xl font-bold"
            >
                &times;
            </button>


            <!-- NGO HEADER -->

            <div class="text-center mb-8">

                <img
                    src="${logoUrl}"
                    class="h-20 mx-auto mb-3 object-contain"
                    alt="NGO Logo"
                    onerror="this.src='${assets.logo}'"
                >

                <h2 class="text-2xl font-bold text-[#0e243a]">
                    ${ngo.name || ''}
                </h2>

                <p class="text-sm text-gray-600 mt-1">
                    ${ngo.description || ''}
                </p>

            </div>


            <!-- ================================= -->
            <!-- NGO CONTACT -->
            <!-- ================================= -->

            <div class="grid md:grid-cols-2 gap-6 mb-8 text-sm bg-gray-50 p-5 rounded-xl">

                <div class="flex flex-col items-center text-center gap-2">

                    <div class="flex items-center gap-2 font-semibold text-[#0e243a]">

                        <img
                            src="${assets.phoneIcon}"
                            class="w-4 h-4"
                            alt="Phone"
                        >

                        Contact

                    </div>

                    <p>
                        ${ngo.contact_number || 'N/A'}
                    </p>

                </div>


                <div class="flex flex-col items-center text-center gap-2">

                    <div class="flex items-center gap-2 font-semibold text-[#0e243a]">

                        <img
                            src="${assets.locationIcon}"
                            class="w-4 h-4"
                            alt="Location"
                        >

                        Location

                    </div>

                    <p>
                        ${ngo.address || 'N/A'}
                    </p>

                </div>

            </div>


            <!-- ================================= -->
            <!-- ONLINE PAYMENT ACCOUNTS -->
            <!-- ================================= -->

            <div class="mb-8">

                <h3 class="text-xl font-bold text-[#0e243a] mb-4">
                    Online Donation Accounts
                </h3>

                <div class="grid md:grid-cols-2 gap-4">

                    ${accountDisplay}

                </div>

            </div>


            <!-- ================================= -->
            <!-- DONATION FORM -->
            <!-- ================================= -->

            <div class="border-2 border-[#0e243a] rounded-2xl p-6">

                <h3 class="text-xl font-bold text-[#0e243a] mb-6">
                    Make a Donation
                </h3>


                <form
                    method="POST"
                    action="/submit-donation"
                    id="donationForm"
                    enctype="multipart/form-data"
                >

                    <input
                        type="hidden"
                        name="_token"
                        value="${getCsrfToken()}"
                    >


                    <!-- NGO ID -->

                    <input
                        type="hidden"
                        name="ngo_id"
                        value="${ngo.id}"
                    >


                    <!-- ================================= -->
                    <!-- ANONYMOUS DONATION -->
                    <!-- ================================= -->

                    <div class="bg-gray-50 rounded-xl p-4 mb-6">

                        <label class="flex items-center gap-3 cursor-pointer">

                            <input
                                type="checkbox"
                                name="anonymous"
                                value="1"
                                id="anonymousDonation"
                                class="w-5 h-5"
                                onchange="toggleDonorSource()"
                            >

                            <span class="font-semibold text-[#0e243a]">
                                Make this donation anonymous
                            </span>

                        </label>

                        <p class="text-sm text-gray-500 mt-2 ml-8">
                            Your name will not be recorded as the donation source.
                        </p>

                    </div>


                    <!-- ================================= -->
                    <!-- DONOR SOURCE -->
                    <!-- ================================= -->

                    <div class="mb-5">

                        <label
                            for="source"
                            class="block font-semibold text-[#0e243a] mb-2"
                        >
                            Donor / Source
                        </label>

                        <input
                            type="text"
                            name="source"
                            id="source"
                            placeholder="e.g. From Juan Dela Cruz"
                            class="w-full border rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#d4a017]"
                        >

                    </div>


                    <!-- ================================= -->
                    <!-- DONATION TYPE -->
                    <!-- ================================= -->

                    <div class="mb-5">

                        <label
                            for="donationType"
                            class="block font-semibold text-[#0e243a] mb-2"
                        >
                            Donation Type
                        </label>

                        <select
                            name="type"
                            id="donationType"
                            onchange="updateDonationFields()"
                            required
                            class="w-full border rounded-xl px-4 py-3"
                        >

                            <option value="">
                                Select Donation Type
                            </option>

                          

                            <option value="ONLINE_MONETARY">
                                Online Monetary
                            </option>

                            <option value="CONSUMABLE">
                                Consumable
                            </option>

                            <option value="REUSABLE">
                                Reusable
                            </option>

                        </select>

                    </div>


                    <!-- ================================= -->
                    <!-- DESCRIPTION -->
                    <!-- ================================= -->

                    <div class="mb-5">

                        <label
                            for="description"
                            class="block font-semibold text-[#0e243a] mb-2"
                        >
                            Description
                        </label>

                        <textarea
                            name="description"
                            id="description"
                            rows="3"
                            required
                            placeholder="e.g. Financial donation, canned goods, clothes..."
                            class="w-full border rounded-xl px-4 py-3"
                        ></textarea>

                    </div>


                    <!-- ================================= -->
                    <!-- AMOUNT -->
                    <!-- ================================= -->

                    <div class="grid md:grid-cols-2 gap-5 mb-5">

                        <div>

                            <label
                                for="amount"
                                class="block font-semibold text-[#0e243a] mb-2"
                            >
                                Amount / Quantity
                            </label>

                            <input
                                type="number"
                                name="amount"
                                id="amount"
                                min="0"
                                step="0.01"
                                required
                                class="w-full border rounded-xl px-4 py-3"
                            >

                        </div>


                        <div>

                            <label
                                for="unit"
                                class="block font-semibold text-[#0e243a] mb-2"
                            >
                                Unit
                            </label>

                            <input
                                type="text"
                                name="unit"
                                id="unit"
                                placeholder="e.g. piece, sack, can"
                                class="w-full border rounded-xl px-4 py-3"
                            >

                        </div>

                    </div>


                    <!-- ================================= -->
                    <!-- PROOF OF PAYMENT -->
                    <!-- ================================= -->

                    <div
                        id="proofOfPaymentField"
                        class="mb-6 hidden"
                    >

                        <label
                            for="proof_of_payment"
                            class="block font-semibold text-[#0e243a] mb-2"
                        >
                            Proof of Payment
                        </label>

                        <input
                            type="file"
                            name="proof_of_payment"
                            id="proof_of_payment"
                            accept="image/jpeg,image/png,image/jpg,application/pdf"
                            class="w-full border rounded-xl px-4 py-3 bg-white"
                        >

                        <p class="text-sm text-gray-500 mt-2">
                            Upload a screenshot or receipt of your online payment.
                            Accepted files: JPG, JPEG, PNG, PDF.
                        </p>

                    </div>


                    <!-- ================================= -->
                    <!-- SUBMIT -->
                    <!-- ================================= -->

                    <div class="flex justify-end gap-3">

                        <button
                            type="button"
                            onclick="closeModal()"
                            class="px-6 py-3 rounded-full bg-gray-400 text-white hover:bg-gray-500 transition"
                        >
                            Cancel
                        </button>

                        <button
                            type="submit"
                            class="px-8 py-3 rounded-full bg-[#f2c94c] text-[#0e243a] font-bold hover:bg-[#d39a11] transition"
                        >
                            Submit Donation
                        </button>

                    </div>


                </form>

            </div>

        </div>

    `;

    const modal = document.getElementById('ngoModal');
    const content = document.getElementById('modalContent');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    content.classList.remove('modal-exit');
    content.classList.add('modal-enter');

    const donationForm = document.getElementById('donationForm');

    if (donationForm) {
        donationForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitButton = donationForm.querySelector(
                'button[type="submit"]'
            );

            const originalText = submitButton.textContent;

            const letterDetails = {
                ngoName: ngo.name || '',
                anonymous: document.getElementById('anonymousDonation').checked,
                source: document.getElementById('source').value,
                type: document.getElementById('donationType').value,
                description: document.getElementById('description').value.trim(),
                amount: document.getElementById('amount').value,
                unit: document.getElementById('unit').value.trim()
            };

            try {
                submitButton.disabled = true;
                submitButton.textContent = 'Submitting...';

                const response = await fetch(donationForm.action, {
                    method: 'POST',
                    body: new FormData(donationForm),
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    let message = 'Failed to submit donation. Please check your information.';

                    try {
                        const result = await response.json();

                        if (result.message) {
                            message = result.message;
                        }

                        if (result.errors) {
                            message = Object.values(result.errors)
                                .flat()
                                .join('\n');
                        }
                    } catch (_) {

                    }

                    throw new Error(message);
                }

                const result = await response.json();

                if (!result.success) {
                    throw new Error(
                        result.message || 'Failed to submit donation.'
                    );
                }

                showAcknowledgmentLetter(letterDetails, function () {
                    showDonationResult(
                        true,
                        result.message || 'Your donation has been submitted successfully and is pending review.'
                    );
                });

            } catch (error) {
                alert(error.message || 'Something went wrong. Please try again.');
            } finally {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        });
    }

    document.body.style.overflow = 'hidden';

}

function getCsrfToken() {

    const token =
        document.querySelector('meta[name="csrf-token"]');

    return token
        ? token.getAttribute('content')
        : '';
}

function updateDonationFields() {

    const type =
        document.getElementById('donationType').value;

    const unit =
        document.getElementById('unit');

    const proofOfPayment =
        document.getElementById('proof_of_payment');

    const proofOfPaymentField =
        document.getElementById('proofOfPaymentField');

    if (type === 'ONLINE_MONETARY') {

        proofOfPaymentField.classList.remove('hidden');

        proofOfPayment.required = true;

        unit.value = '';
        unit.placeholder = 'Not applicable';
        unit.disabled = true;

    }

    else {

        proofOfPaymentField.classList.add('hidden');

        proofOfPayment.required = false;
        proofOfPayment.value = '';

        unit.disabled = false;

        unit.placeholder =
            'e.g. piece, sack, can';
    }
}

function toggleDonorSource() {

    const anonymous =
        document.getElementById('anonymousDonation');

    const source =
        document.getElementById('source');


    if (anonymous.checked) {

        source.value = 'Anonymous';

        source.disabled = true;

    } else {

        source.value = '';

        source.disabled = false;
    }
}

function closeModal() {

    const modal =
        document.getElementById('ngoModal');

    const content =
        document.getElementById('modalContent');


    content.classList.remove('modal-enter');

    content.classList.add('modal-exit');


    setTimeout(() => {

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        document.body.style.overflow = 'auto';

    }, 250);
}
function openLogoutModal() {

    const modal =
        document.getElementById(
            'logoutModal'
        );


    if (!modal) {
        return;
    }


    modal.classList.remove(
        'hidden'
    );

    modal.classList.add(
        'flex'
    );

}


function closeLogoutModal() {

    const modal =
        document.getElementById(
            'logoutModal'
        );


    if (!modal) {
        return;
    }


    modal.classList.add(
        'hidden'
    );

    modal.classList.remove(
        'flex'
    );

}

const ngoModal =
    document.getElementById('ngoModal');

if (ngoModal) {

    ngoModal.addEventListener('click', function (e) {

        if (e.target.id === 'ngoModal') {

            closeModal();

        }

    });

}