const btnNav = document.querySelector(".btn-mobile-nav");
const header = document.querySelector(".left-heading");
const btnAdd = document.querySelector(".btn--new");
const modals = document.querySelectorAll(".modal-holder");
const btnsCloseModal = document.querySelectorAll(".btn-close");
const btnUserModal = document.querySelector(".user-settings");

const btnAddParti = document.querySelector(".btn--parti");
const addModal = document.querySelector(".modal--add");

const btnEditTask = document.querySelector(".btn--edit");
const editModal = document.querySelector(".modal--edit");

const btnDeleteTask = document.querySelector(".btn--delete");
const deleteModal = document.querySelector(".modal--delete");

const typeSelect = document.querySelector(".type-select");
const usersForm = document.querySelector(".input-users");
const teamsForm = document.querySelector(".input-teams");

const topHeader = document.querySelector("header");

const settingsBtns = document.querySelectorAll(".settings-open-list");
const settingsNavs = document.querySelectorAll(".settings-nav");

const body = document.body;

if (settingsBtns)
  settingsBtns.forEach((btn, i) =>
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      settingsNavs[i].classList.toggle("nav-open");
    })
  );
if (typeSelect)
  typeSelect.addEventListener("change", function () {
    if (this.value === "user") {
      usersForm.classList.remove("hidden");
      teamsForm.classList.add("hidden");
    }
    if (this.value === "team") {
      teamsForm.classList.remove("hidden");
      usersForm.classList.add("hidden");
    }
  });
if (btnAddParti)
  btnAddParti.addEventListener("click", function (e) {
    e.preventDefault();
    addModal.classList.add("appear");
  });

if (btnEditTask)
  btnEditTask.addEventListener("click", function (e) {
    e.preventDefault();
    editModal.classList.add("appear");
  });

if (btnDeleteTask)
  btnDeleteTask.addEventListener("click", function (e) {
    e.preventDefault();
    deleteModal.classList.add("appear");
  });
if (btnNav)
  btnNav.addEventListener("click", function (e) {
    e.preventDefault();
    header.classList.toggle("nav-open");
  });

if (btnAdd)
  btnAdd.addEventListener("click", function (e) {
    e.preventDefault();
    modals[0].classList.add("appear");
  });

if (btnsCloseModal)
  btnsCloseModal.forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const currModal = this.closest(".modal-holder");
      currModal.classList.remove("appear");
    });
  });

if (btnUserModal)
  btnUserModal.addEventListener("click", function (e) {
    e.preventDefault();
    modals[0].classList.add("appear");
  });

if (modals)
  modals.forEach((modal) =>
    modal.addEventListener("click", function (e) {
      if (e.target.classList.contains("modal-holder"))
        modal.classList.remove("appear");
    })
  );
