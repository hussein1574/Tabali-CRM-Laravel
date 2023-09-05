const select = (selector) => document.querySelector(selector);
const selectAll = (selector) => document.querySelectorAll(selector);

const btnNav = select(".btn-mobile-nav");
const header = select(".left-heading");
const btnAdd = select(".btn--new");
const modals = selectAll(".modal-holder");
const btnsCloseModal = selectAll(".btn-close");
const btnsCloseMessageModal = selectAll(".btn-close-message");
const btnsUserModal = selectAll(".user-settings");

const btnAddParti = select(".btn--parti");
const addModal = select(".modal--add");

const btnEditTask = select(".btn--edit");
const editModal = select(".modal--edit");

const btnDeleteTask = select(".btn--delete");
const deleteModal = select(".modal--delete");

const typeSelect = select(".type-select");
const usersForm = select(".input-users");
const teamsForm = select(".input-teams");

const topHeader = select("header");

const settingsBtns = selectAll(".settings-open-list");
const settingsNavs = selectAll(".settings-nav");

const btnDeleteUser = select(".btn-user-delete");

const body = document.body;

const addEventListenerIf = (element, event, callback) => {
    if (element) {
        element.addEventListener(event, callback);
    }
};
addEventListenerIf(btnDeleteUser, "click", function (e) {
    e.preventDefault();
    const form = btnDeleteUser.closest("form");
    const modal = btnDeleteUser.closest(".modal");
    const formElements = form.closest("li");
    const ulElement = formElements.closest("ul");
    const requestLink = form.action;
    const taskId = form.querySelector("input[name='task_id']").value;
    const userId = form.querySelector("input[name='user_id']").value;
    const csrfToken = form.querySelector("input[name='_token']").value;

    fetch(requestLink, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            task_id: taskId,
            user_id: userId,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            formElements.remove();
            if (ulElement.childElementCount === 0) {
                const divElement = document.createElement("div");
                divElement.classList.add("modal", "no-box-shadow");
                divElement.innerHTML = `
        <ion-icon class='orange-icon' name="alert-outline"></ion-icon>
        <h3 class="form-title lighter-font">No participants Yet</h3>
    `;
                modal.insertBefore(divElement, ulElement);
                ulElement.remove();
            }
        })
        .catch((error) => {
            console.error(
                "There was a problem with the fetch operation:",
                error
            );
        });

    console.log(form, formElements, taskId);
});

settingsBtns.forEach((btn, i) => {
    addEventListenerIf(btn, "click", (e) => {
        e.preventDefault();
        settingsNavs[i].classList.toggle("nav-settings-open");
    });
});

addEventListenerIf(typeSelect, "change", () => {
    usersForm.classList.toggle("hidden", typeSelect.value !== "user");
    teamsForm.classList.toggle("hidden", typeSelect.value !== "team");
});

addEventListenerIf(btnAddParti, "click", (e) => {
    e.preventDefault();
    addModal.classList.add("appear");
});

addEventListenerIf(btnEditTask, "click", (e) => {
    e.preventDefault();
    editModal.classList.add("appear");
});

addEventListenerIf(btnDeleteTask, "click", (e) => {
    e.preventDefault();
    deleteModal.classList.add("appear");
});

addEventListenerIf(btnNav, "click", (e) => {
    e.preventDefault();
    header.classList.toggle("nav-open");
});

addEventListenerIf(btnAdd, "click", (e) => {
    e.preventDefault();
    modals[0].classList.add("appear");
});

btnsCloseModal.forEach((btn) => {
    addEventListenerIf(btn, "click", (e) => {
        e.preventDefault();
        const currModal = btn.closest(".modal-holder");
        currModal.classList.remove("appear");
    });
});

btnsCloseMessageModal.forEach((btn) => {
    addEventListenerIf(btn, "click", (e) => {
        e.preventDefault();
        const currModal = btn.closest(".modal-holder-message");
        currModal.classList.remove("appear");
    });
});

btnsUserModal.forEach((btnUserModal) => {
    addEventListenerIf(btnUserModal, "click", (e) => {
        e.preventDefault();
        const form = btnUserModal.closest("form");
        const formElements = form.closest("li");
        const user = {
            id: form.querySelector("input[name='user_id']").value,
            name: formElements.querySelector(".data-title").textContent,
            email: formElements.querySelector(".data-desc").textContent,
            state: formElements
                .querySelector(".user-active")
                .querySelector(".data-role-desc")
                .textContent.trim(),
            role: formElements
                .querySelector(".user-role")
                .querySelector(".data-role-desc")
                .textContent.trim(),
        };

        const modalForm = modals[0].querySelector("form");
        modalForm.querySelector("input[name='user_id']").value = user.id;
        modalForm.querySelector("input[name='name']").value = user.name;
        modalForm.querySelector("input[name='email']").value = user.email;
        modalForm.querySelector("select[name='role']").value =
            user.role === "Admin" ? "Admin" : "User";
        modalForm.querySelector("select[name='active']").value =
            user.state === "Activated" ? "active" : "not-active";

        modals[0].classList.add("appear");
    });
});

modals.forEach((modal) => {
    addEventListenerIf(modal, "click", (e) => {
        if (e.target.classList.contains("modal-holder")) {
            modal.classList.remove("appear");
        }
    });
});
