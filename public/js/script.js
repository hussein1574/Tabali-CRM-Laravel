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

let btnsDeleteUser = selectAll(".btn-user-delete");
const participantsText = select(".participants");
const btnAddUser = select(".btn-add-parti");

const languageSelect = select(".language-box");

const body = document.body;

const addEventListenerIf = (element, event, callback) => {
    if (element) {
        element.addEventListener(event, callback);
    }
};
addEventListenerIf(languageSelect, "change", (e) => {
    const form = languageSelect.closest("form");
    const requestLink = form.action;
    const locale = languageSelect.value;
    const csrfToken = form.querySelector("input[name='_token']").value;
    fetch(requestLink, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({
            locale: locale,
        }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            window.location.reload();
        })
        .catch((error) => {
            console.error(
                "There was a problem with the fetch operation:",
                error
            );
        });
});
addEventListenerIf(btnAddUser, "click", (e) => {
    e.preventDefault();
    const form = btnAddUser.closest("form");
    const modal = btnAddUser.closest(".modal");
    let ulElement = modal.querySelector("ul");

    const requestLink = form.action;
    const url = new URL(requestLink);
    const baseUrl = url.origin;
    const taskId = form.querySelector("input[name='task_id']").value;
    let teamId = "",
        userId = "";
    if (form.querySelector("select[name='team_id']"))
        teamId = form.querySelector("select[name='team_id']").value;
    if (form.querySelector("select[name='user_id']"))
        userId = form.querySelector("select[name='user_id']").value;
    const csrfToken = form.querySelector("input[name='_token']").value;
    let body = "";
    if (teamId === "") {
        body = JSON.stringify({
            task_id: taskId,
            user_id: userId,
        });
    }
    if (userId === "") {
        body = JSON.stringify({
            task_id: taskId,
            team_id: teamId,
        });
    }
    fetch(requestLink, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            if (!ulElement) {
                const modalMessage = modal.querySelector(".no-box-shadow");
                ulElement = document.createElement("ul");
                ulElement.className = "user-lines";
                if (data.users.length >= 4) {
                    ulElement.classList.add("scrollable");
                }
                modalMessage.replaceWith(ulElement);
            }
            data.users.forEach((user) => {
                const liElementHtml = `
                <span class="user-line-name">${user.name}</span>
                <form method="post" action="${baseUrl}/delete-task-member">
                    <input type="hidden" name="_token" value="${csrfToken}">                    
                    <input type="hidden" name="_method" value="DELETE">                    
                    <input hidden="" id="task_id" name="task_id" value="${taskId}">
                    <input hidden="" id="user_id" name="user_id" value="${user.id}">
                    <button type="submit" class="btn btn-user-delete">
                        <ion-icon class="task-btn-icon md hydrated" name="trash-outline" role="img"></ion-icon>
                    </button>
                </form>
            `;
                const liElement = document.createElement("li");
                liElement.classList.add("user-line");
                liElement.innerHTML = liElementHtml;
                ulElement.appendChild(liElement);
            });
            const usersNames = Array.from(selectAll(".user-line-name")).reduce(
                function (names, nameElement) {
                    return (names += `${names ? "," : ""} ${
                        nameElement.textContent
                    }`);
                },
                ""
            );
            participantsText.textContent = usersNames;
            btnsDeleteUser = selectAll(".btn-user-delete");
            btnsDeleteUser.forEach((btnDeleteUser) => {
                addEventListenerIf(btnDeleteUser, "click", function (e) {
                    e.preventDefault();
                    const form = btnDeleteUser.closest("form");
                    const modal = btnDeleteUser.closest(".modal");
                    const formTitle = modal.querySelector(".form-title");
                    const isArabic =
                        formTitle.textContent === "الاعضاء الحاليين";
                    const liElement = form.closest("li");
                    const ulElement = liElement.closest("ul");
                    const requestLink = form.action;
                    const taskId = form.querySelector(
                        "input[name='task_id']"
                    ).value;
                    const userId = form.querySelector(
                        "input[name='user_id']"
                    ).value;
                    const csrfToken = form.querySelector(
                        "input[name='_token']"
                    ).value;

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
                            liElement.remove();
                            const usersNames = Array.from(
                                selectAll(".user-line-name")
                            ).reduce(function (names, nameElement) {
                                return (names += `${names ? "," : ""} ${
                                    nameElement.textContent
                                }`);
                            }, "");
                            participantsText.textContent = usersNames;
                            if (ulElement.childElementCount === 0) {
                                if (!isArabic)
                                    participantsText.textContent =
                                        "No participants";
                                else
                                    participantsText.textContent =
                                        "لا يوجد اعضاء";
                                const divElement =
                                    document.createElement("div");
                                divElement.classList.add(
                                    "modal",
                                    "no-box-shadow"
                                );
                                if (!isArabic)
                                    divElement.innerHTML = `
        <ion-icon class='orange-icon' name="alert-outline"></ion-icon>
        <h3 class="form-title lighter-font">No participants Yet</h3>
    `;
                                else
                                    divElement.innerHTML = `
        <ion-icon class='orange-icon' name="alert-outline"></ion-icon>
        <h3 class="form-title lighter-font">لا يوجد اعضاء</h3>
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
                });
            });
        })
        .catch((error) => {
            console.error(
                "There was a problem with the fetch operation:",
                error
            );
        });
});
btnsDeleteUser.forEach((btnDeleteUser) => {
    addEventListenerIf(btnDeleteUser, "click", function (e) {
        e.preventDefault();
        const form = btnDeleteUser.closest("form");
        const modal = btnDeleteUser.closest(".modal");
        const liElement = form.closest("li");
        const ulElement = liElement.closest("ul");
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
                liElement.remove();
                const usersNames = Array.from(
                    selectAll(".user-line-name")
                ).reduce(function (names, nameElement) {
                    return (names += `${names ? "," : ""} ${
                        nameElement.textContent
                    }`);
                }, "");
                participantsText.textContent = usersNames;
                if (ulElement.childElementCount === 0) {
                    participantsText.textContent = "No participants";
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
    });
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
