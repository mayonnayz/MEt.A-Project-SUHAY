document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById("eventDate");

    if (dateInput) {
        const today = new Date().toISOString().split("T")[0];
        dateInput.setAttribute("min", today);
    }
    setTimeout(() => {

        const err = document.getElementById("toastError");
        const ok = document.getElementById("toastSuccess");

        if (err) err.remove();
        if (ok) ok.remove();

    }, 3000);

    const addForm = document.getElementById("addEventForm");

    if (addForm) {

        addForm.addEventListener("submit", function (e) {

            const activityNames = document.querySelectorAll(
                '#activities-container input[name*="[name]"]'
            );

            const activityRemarks = document.querySelectorAll(
                '#activities-container textarea[name*="[remarks]"]'
            );

            let hasValidActivity = false;

            for (let i = 0; i < activityNames.length; i++) {

                if (
                    activityNames[i].value.trim() !== "" &&
                    activityRemarks[i] &&
                    activityRemarks[i].value.trim() !== ""
                ) {
                    hasValidActivity = true;
                    break;
                }
            }

            if (!hasValidActivity) {

                e.preventDefault();

                alert(
                    "Please add at least 1 activity with name and remarks."
                );
            }
        });
    }

});

let activityIndex = 1;
let editActivityIndex = 1000;
let originalName = "";
let originalDescription = "";
let originalDate = "";
let originalActivities = [];
let currentId = null;

function openLogoutModal() {

    const modal = document.getElementById("logoutModal");

    if (!modal) return;

    modal.classList.remove("hidden");
    modal.classList.add("flex");
}


function closeLogoutModal() {

    const modal = document.getElementById("logoutModal");

    if (!modal) return;

    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

function openEditModal(row) {

    const id = row.dataset.id;
    const name = row.dataset.name || "";
    const description = row.dataset.description || "";
    const date = row.dataset.date || "";

    let activities = [];

    try {

        activities = JSON.parse(
            row.dataset.activities || "[]"
        );

    } catch (error) {

        console.error(
            "Unable to read activities:",
            error
        );

        activities = [];
    }


    currentId = id;

    originalName = name;
    originalDescription = description;
    originalDate = date;

    originalActivities = JSON.parse(
        JSON.stringify(activities)
    );

    const editId =
        document.getElementById("editId");

    const editName =
        document.getElementById("editName");

    const editDescription =
        document.getElementById("editDescription");

    const editDate =
        document.getElementById("editDate");

    const editForm =
        document.getElementById("editForm");


    if (editId) {
        editId.value = id;
    }


    if (editName) {
        editName.value = name;
    }


    if (editDescription) {
        editDescription.value = description;
    }


    if (editDate) {

        editDate.value = date;

        const today =
            new Date().toISOString().split("T")[0];

        editDate.setAttribute("min", today);
    }


    if (editForm) {
        editForm.action = `/events/${id}`;
    }

    document
        .querySelectorAll(
            '#editForm input[name="activities_delete[]"]'
        )
        .forEach(input => input.remove());

    const container =
        document.getElementById(
            "edit-activities-container"
        );

    if (!container) return;

    container.innerHTML = "";

    if (activities.length === 0) {

        container.innerHTML = `
            <p class="text-gray-300 text-sm">
                No activities loaded yet.
            </p>
        `;

    } else {

        activities.forEach((act, index) => {

            const activityId =
                act.id ?? "";

            const activityName =
                escapeHtml(act.name ?? "");

            const activityRemarks =
                escapeHtml(act.remarks ?? "");


            container.insertAdjacentHTML(
                "beforeend",
                `
                <div class="activity-item mb-3 flex gap-2 items-start">

                    <input
                        type="hidden"
                        name="activities[${index}][id]"
                        value="${activityId}"
                    >

                    <div class="flex-1">

                        <input
                            type="text"
                            name="activities[${index}][name]"
                            value="${activityName}"
                            class="w-full p-2 rounded-md text-[#0e243a] mb-2"
                            required
                        >

                        <textarea
                            name="activities[${index}][remarks]"
                            class="w-full p-2 rounded-md text-[#0e243a]"
                            required
                        >${activityRemarks}</textarea>

                    </div>

                    <button
                        type="button"
                        onclick="markDeleteActivity(this)"
                        class="bg-red-500 px-3 py-2 rounded text-white"
                    >
                        X
                    </button>

                </div>
                `
            );
        });
    }

    const modal =
        document.getElementById("editModal");

    if (modal) {

        modal.classList.remove("hidden");
        modal.classList.add("flex");
    }
}

function closeEditModal() {

    const modal =
        document.getElementById("editModal");

    if (!modal) return;

    const nameElement =
        document.getElementById("editName");

    const descriptionElement =
        document.getElementById("editDescription");

    const dateElement =
        document.getElementById("editDate");


    const currentName =
        nameElement
            ? nameElement.value.trim()
            : "";

    const currentDescription =
        descriptionElement
            ? descriptionElement.value.trim()
            : "";

    const currentDate =
        dateElement
            ? dateElement.value
            : "";

    const detailsChanged =
        currentName !== originalName.trim() ||
        currentDescription !== originalDescription.trim() ||
        currentDate !== originalDate;

    const deleteInputs =
        document.querySelectorAll(
            '#editForm input[name="activities_delete[]"]'
        );

    const activitiesDeleted =
        deleteInputs.length > 0;

    let activitiesChanged = false;

    const activityItems =
        document.querySelectorAll(
            "#edit-activities-container .activity-item"
        );

    const currentActivities = [];

    activityItems.forEach(item => {

        if (
            item.style.display === "none" ||
            item.classList.contains("marked-delete")
        ) {
            return;
        }


        const idInput =
            item.querySelector(
                'input[type="hidden"]'
            );

        const nameInput =
            item.querySelector(
                'input[name*="[name]"]'
            );

        const remarksInput =
            item.querySelector(
                'textarea[name*="[remarks]"]'
            );


        currentActivities.push({

            id: idInput
                ? idInput.value
                : "",

            name: nameInput
                ? nameInput.value.trim()
                : "",

            remarks: remarksInput
                ? remarksInput.value.trim()
                : ""
        });
    });

    if (
        currentActivities.length !==
        originalActivities.length
    ) {

        activitiesChanged = true;

    } else {

        for (
            let i = 0;
            i < originalActivities.length;
            i++
        ) {

            const original =
                originalActivities[i];

            const current =
                currentActivities[i];


            if (!current) {

                activitiesChanged = true;
                break;
            }


            if (
                String(current.id || "") !==
                String(original.id || "") ||

                current.name !==
                String(original.name || "").trim() ||

                current.remarks !==
                String(original.remarks || "").trim()
            ) {

                activitiesChanged = true;
                break;
            }
        }
    }

    const hasChanges =
        detailsChanged ||
        activitiesChanged ||
        activitiesDeleted;

    if (!hasChanges) {

        modal.classList.add("hidden");
        modal.classList.remove("flex");

        return;
    }

    if (
        confirm(
            "You have unsaved changes. Close anyway?"
        )
    ) {

        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
}

function openAddModal() {

    const modal =
        document.getElementById("addModal");

    if (!modal) return;

    modal.classList.remove("hidden");
    modal.classList.add("flex");
}


function closeAddModal() {

    const modal =
        document.getElementById("addModal");

    if (!modal) return;

    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

function addActivity() {

    const container =
        document.getElementById(
            "activities-container"
        );

    if (!container) return;


    const html = `
        <div class="activity-item mb-3 flex gap-2 items-start">

            <div class="flex-1">

                <input
                    type="text"
                    name="activities[${activityIndex}][name]"
                    placeholder="Activity Name"
                    class="w-full p-2 rounded-md text-[#0e243a] mb-2"
                    required
                >

                <textarea
                    name="activities[${activityIndex}][remarks]"
                    placeholder="Remarks"
                    class="w-full p-2 rounded-md text-[#0e243a]"
                    required
                ></textarea>

            </div>

            <button
                type="button"
                onclick="this.closest('.activity-item').remove()"
                class="bg-red-500 px-3 py-2 rounded text-white mb-2"
            >
                X
            </button>

        </div>
    `;


    container.insertAdjacentHTML(
        "beforeend",
        html
    );

    activityIndex++;
}

function addEditActivity() {

    const container =
        document.getElementById(
            "edit-activities-container"
        );

    if (!container) return;

    const emptyMessage =
        container.querySelector("p");

    if (emptyMessage) {
        emptyMessage.remove();
    }


    const html = `
        <div class="activity-item mb-3 flex gap-2 items-start">

            <div class="flex-1">

                <input
                    type="text"
                    name="activities[${editActivityIndex}][name]"
                    placeholder="Activity Name"
                    class="w-full p-2 rounded-md text-[#0e243a] mb-2"
                    required
                >

                <textarea
                    name="activities[${editActivityIndex}][remarks]"
                    placeholder="Remarks"
                    class="w-full p-2 rounded-md text-[#0e243a]"
                    required
                ></textarea>

            </div>

            <button
                type="button"
                onclick="this.closest('.activity-item').remove()"
                class="bg-red-500 px-3 py-2 rounded text-white mb-2"
            >
                X
            </button>

        </div>
    `;


    container.insertAdjacentHTML(
        "beforeend",
        html
    );

    editActivityIndex++;
}

function markDeleteActivity(button) {

    const item =
        button.closest(".activity-item");

    if (!item) return;


    const allItems =
        document.querySelectorAll(
            "#edit-activities-container .activity-item"
        );

    const visibleItems =
        Array.from(allItems).filter(
            activity =>
                activity.style.display !== "none"
        );


    if (visibleItems.length <= 1) {

        alert(
            "At least 1 activity is required."
        );

        return;
    }


    const idInput =
        item.querySelector(
            'input[type="hidden"]'
        );

    if (
        idInput &&
        idInput.value !== ""
    ) {

        item.style.display = "none";

        item.classList.add(
            "marked-delete"
        );

        const existingDelete =
            document.querySelector(
                `#editForm input[name="activities_delete[]"][value="${CSS.escape(idInput.value)}"]`
            );


        if (!existingDelete) {

            const deleteInput =
                document.createElement("input");

            deleteInput.type = "hidden";

            deleteInput.name =
                "activities_delete[]";

            deleteInput.value =
                idInput.value;


            const editForm =
                document.getElementById(
                    "editForm"
                );


            if (editForm) {

                editForm.appendChild(
                    deleteInput
                );
            }
        }


    } else {
        item.remove();
    }
}

function escapeHtml(value) {

    const div =
        document.createElement("div");

    div.textContent =
        String(value);

    return div.innerHTML;
}

function openEventModal(row) {

    if (!row) return;


    const id =
        row.dataset.id;

    const name =
        row.dataset.name || "";

    const description =
        row.dataset.description || "";

    const date =
        row.dataset.date || "";

    const status =
        row.dataset.status;


    let activities = [];


    try {

        activities = JSON.parse(
            row.dataset.activities || "[]"
        );

    } catch (error) {

        console.error(
            "Unable to read activities:",
            error
        );

        activities = [];
    }

    const nameElement =
        document.getElementById(
            "eventModalName"
        );

    const dateElement =
        document.getElementById(
            "eventModalDate"
        );

    const descriptionElement =
        document.getElementById(
            "eventModalDescription"
        );


    if (nameElement) {
        nameElement.textContent = name;
    }


    if (dateElement) {
        dateElement.textContent = date;
    }


    if (descriptionElement) {
        descriptionElement.textContent =
            description;
    }

    const statusElement =
        document.getElementById(
            "eventModalStatus"
        );


    if (statusElement) {

        if (status == "1") {

            statusElement.textContent =
                "Active";

            statusElement.className =
                "bg-white text-green-600 p-3 rounded-md mt-1 font-bold";

        } else {

            statusElement.textContent =
                "Archived";

            statusElement.className =
                "bg-white text-gray-500 p-3 rounded-md mt-1 font-bold";
        }
    }

    const activitiesContainer =
        document.getElementById(
            "eventModalActivities"
        );


    if (activitiesContainer) {

        activitiesContainer.innerHTML = "";


        if (activities.length === 0) {

            activitiesContainer.innerHTML = `
                <div class="bg-white text-gray-500 p-3 rounded-md">
                    No activities available.
                </div>
            `;

        } else {

            activities.forEach(
                (activity, index) => {

                    const activityName =
                        activity.name ||
                        "Unnamed Activity";

                    const remarks =
                        activity.remarks ||
                        "No remarks";


                    activitiesContainer.insertAdjacentHTML(
                        "beforeend",
                        `
                        <div class="bg-white text-[#0e243a] p-3 rounded-md mb-2">

                            <div class="font-semibold">
                                ${index + 1}.
                                ${escapeHtml(activityName)}
                            </div>

                            <div class="text-sm text-gray-600 mt-1">
                                ${escapeHtml(remarks)}
                            </div>

                        </div>
                        `
                    );
                }
            );
        }
    }

    const archiveForm =
        document.getElementById(
            "eventArchiveForm"
        );

    const reactivateForm =
        document.getElementById(
            "eventReactivateForm"
        );

    if (status == "1") {
        if (archiveForm) {

            archiveForm.action =
                `/events/${id}/archive`;

            archiveForm.classList.remove(
                "hidden"
            );
        }


        if (reactivateForm) {

            reactivateForm.classList.add(
                "hidden"
            );
        }

    } else {
        if (reactivateForm) {

            reactivateForm.action =
                `/events/${id}/reactivate`;

            reactivateForm.classList.remove(
                "hidden"
            );
        }


        if (archiveForm) {

            archiveForm.classList.add(
                "hidden"
            );
        }
    }

    const editButton =
        document.getElementById(
            "eventEditButton"
        );


    if (editButton) {

        editButton.onclick = function () {

            closeEventModal();

            openEditModal(row);
        };
    }
    const modal =
        document.getElementById(
            "eventModal"
        );


    if (modal) {

        modal.classList.remove(
            "hidden"
        );

        modal.classList.add(
            "flex"
        );
    }
}

function closeEventModal() {

    const modal =
        document.getElementById(
            "eventModal"
        );

    if (!modal) return;


    modal.classList.add(
        "hidden"
    );

    modal.classList.remove(
        "flex"
    );
}