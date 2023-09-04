const select = (selector) => document.querySelector(selector);
const selectAll = (selector) => document.querySelectorAll(selector);

const btnNav = select(".btn-mobile-nav");
const header = select(".left-heading");
const btnAdd = select(".btn--new");
const modals = selectAll(".modal-holder");
const btnsCloseModal = selectAll(".btn-close");
const btnsCloseMessageModal = selectAll(".btn-close-message");
const btnUserModal = select(".user-settings");

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

const body = document.body;

const addEventListenerIf = (element, event, callback) => {
    if (element) {
        element.addEventListener(event, callback);
    }
};

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

addEventListenerIf(btnUserModal, "click", (e) => {
    e.preventDefault();
    modals[0].classList.add("appear");
});

modals.forEach((modal) => {
    addEventListenerIf(modal, "click", (e) => {
        if (e.target.classList.contains("modal-holder")) {
            modal.classList.remove("appear");
        }
    });
});
