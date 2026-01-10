window.showEditSubtaskModal_Group = function (id) {
    const muc = window.subtaskData[id];
    if (!muc) return alert("Không tìm thấy mục công việc.");

    document.getElementById(
        "formEditSubTask"
    ).action = `/group/${id}/update-subtask`;
    document.getElementById("editTenMuc").value = muc.TEN_MUC || "";
    document.getElementById("editNoiDung").value = muc.NOI_DUNG_CHI_TIET || "";
    document.getElementById("editDeadline").value =
        muc.THOI_HAN_HOAN_THANH?.replace(" ", "T") || "";
    document.getElementById("editUuTien").value = muc.DO_UU_TIEN_MUC || "";

    // 👇 Thêm dòng này để xử lý checkbox hoàn thành
    document.getElementById("editTrangThai").checked = muc.TRANG_THAI == 1;

    showModal("modalEditSubTask");
};

window.showViewSubtaskModal_Group = function (id) {
    const muc = window.subtaskData[id];
    if (!muc) return alert("Không tìm thấy dữ liệu.");
    document.getElementById("viewTenMuc").textContent = muc.TEN_MUC || "";
    document.getElementById("viewNoiDung").textContent =
        muc.NOI_DUNG_CHI_TIET || "";
    document.getElementById("viewDeadline").textContent =
        muc.THOI_HAN_HOAN_THANH || "";
    document.getElementById("viewUuTien").textContent =
        muc.DO_UU_TIEN_MUC || "";
    showModal("modalViewSubTask");
};

window.confirmDelete_Group = function (actionUrl, title, message) {
    document.getElementById("formConfirmDelete").action = actionUrl;
    document.getElementById("modalDeleteTitle").textContent = title;
    document.getElementById("modalDeleteMessage").textContent = message;
    showModal("modalConfirmDelete");
};

function handleDelete(event) {
    event.preventDefault();

    const form = event.target;
    const actionUrl = form.action;

    fetch(actionUrl, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": form.querySelector('input[name="_token"]').value,
            "X-Requested-With": "XMLHttpRequest",
        },
        body: new FormData(form),
    }).then((response) => {
        if (response.ok) {
            hideModal("modalConfirmDelete");

            // Xoá phần tử có data-id tương ứng
            const deletedId = actionUrl.split("/").pop();
            const element = document.querySelector(`[data-id="${deletedId}"]`);
            if (element) {
                element.remove();
            }
        } else {
            alert("Lỗi khi xoá. Vui lòng thử lại.");
        }
    });

    return false;
}

window.setupInlineEditing = function (csrfToken) {
    document.querySelectorAll(".editable").forEach((el) => {
        el.addEventListener("dblclick", () => {
            const type = el.classList.contains("plan-title")
                ? "plan"
                : el.classList.contains("task-title")
                ? "task"
                : el.classList.contains("task-progress")
                ? "task-progress"
                : el.classList.contains("task-priority")
                ? "task-priority"
                : el.classList.contains("subtask-title")
                ? "subtask"
                : el.classList.contains("subtask-deadline")
                ? "deadline"
                : el.classList.contains("subtask-priority")
                ? "priority"
                : null;
            const id = el.dataset.id;
            const current = el.textContent.trim();
            const input = document.createElement("input");
            input.className = "p-1 border";

            if (type === "deadline") {
                input.type = "datetime-local";
            } else if (["priority", "task-priority"].includes(type)) {
                input.type = "number";
                input.min = 1;
                input.max = 10;
            } else if (type === "task-progress") {
                input.type = "number";
                input.min = 0;
                input.max = 100;
            }

            input.value = current;
            el.replaceWith(input);
            input.focus();

            input.addEventListener("blur", () => {
                const newValue = input.value;
                let url, field;
                switch (type) {
                    case "plan":
                        url = `/group/${id}/update-plan`;
                        field = "TEN_KE_HOACH";
                        break;
                    case "task":
                        url = `/group/${id}/update-task`;
                        field = "TEN_CV";
                        break;
                    case "task-progress":
                        url = `/group/${id}/update-task`;
                        field = "TIEN_DO";
                        break;
                    case "task-priority":
                        url = `/group/${id}/update-task`;
                        field = "DO_UU_TIEN";
                        break;
                    case "deadline":
                        url = `/group/${id}/update-subtask`;
                        field = "THOI_HAN_HOAN_THANH";
                        break;
                    case "priority":
                        url = `/group/${id}/update-subtask`;
                        field = "DO_UU_TIEN_MUC";
                        break;
                    case "subtask":
                        url = `/group/${id}/update-subtask`;
                        field = "TEN_MUC";
                        break;
                }

                fetch(url, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrfToken,
                    },
                    body: JSON.stringify({ [field]: newValue }),
                })
                    .then(() => location.reload())
                    .catch((err) => {
                        alert("Lỗi khi cập nhật: " + err.message);
                        location.reload();
                    });
            });
        });
    });
};

window.editCvPriority = function (span, idCv) {
    const valueSpan = span.querySelector(".priority-value");
    const current = valueSpan.innerText.trim();

    const input = document.createElement("input");
    input.type = "number";
    input.value = current;
    input.className = "w-12 text-center border border-gray-400 rounded";
    input.style.fontSize = "0.875rem";

    input.addEventListener("blur", () => saveCvPriority(input, idCv));
    input.addEventListener("keydown", (e) => {
        if (e.key === "Enter") input.blur();
    });

    valueSpan.replaceWith(input);
    input.focus();
    input.select();
};

function saveCvPriority(input, idCv) {
    const newValue = input.value.trim();

    fetch(`/group/${idCv}/update-priority`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ DO_UU_TIEN: newValue }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                location.reload();
            } else {
                alert("Lỗi cập nhật độ ưu tiên công việc!");
            }
        })
        .catch((err) => {
            alert("Lỗi kết nối máy chủ");
        });
}

document.querySelectorAll(".editable-priority").forEach((el) => {
    el.ondblclick = function () {
        const id = el.dataset.id;
        const oldValue = el.querySelector(".priority-value").innerText;
        const input = document.createElement("input");
        input.type = "number";
        input.value = oldValue;
        input.classList.add("w-10", "text-xs");
        el.innerHTML = "⭐ ";
        el.appendChild(input);
        input.focus();

        const submitPriority = () => {
            const newValue = parseInt(input.value);
            if (isNaN(newValue)) {
                location.reload();
                return;
            }

            fetch(`/group/${id}/update-priority`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ DO_UU_TIEN: newValue }),
            })
                .then((res) => res.json())
                .then((data) => {
                    location.reload(); // ✅ Reload lại trang khi cập nhật thành công
                })
                .catch(() => location.reload()); // reload luôn nếu lỗi
        };

        input.addEventListener("blur", submitPriority); // Khi mất focus
        input.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                submitPriority(); // Khi nhấn Enter
            }
        });
    };
});

window.editMcvPriority = function (span, idMcv) {
    const valueSpan = span.querySelector(".priority-muc-value");
    const current = valueSpan.innerText.trim();

    const input = document.createElement("input");
    input.type = "number";
    input.value = current;
    input.className = "w-12 text-center border border-gray-400 rounded";
    input.style.fontSize = "0.875rem";

    input.addEventListener("blur", () => saveMcvPriority(input, idMcv));
    input.addEventListener("keydown", (e) => {
        if (e.key === "Enter") input.blur();
    });

    valueSpan.replaceWith(input);
    input.focus();
    input.select();
};

function saveMcvPriority(input, idMcv) {
    const newValue = input.value.trim();

    fetch(`/group/${idMcv}/update-subtask-priority`, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ DO_UU_TIEN_MUC: newValue }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                location.reload();
            } else {
                alert("Lỗi cập nhật độ ưu tiên mục công việc!");
            }
        })
        .catch((err) => {
            alert("Lỗi kết nối máy chủ");
        });
}

document.querySelectorAll(".editable-priority").forEach((el) => {
    el.ondblclick = function () {
        const id = el.dataset.id;
        const oldValue = el.querySelector(".priority-muc-value").innerText;
        const input = document.createElement("input");
        input.type = "number";
        input.value = oldValue;
        input.classList.add("w-10", "text-xs");
        el.innerHTML = "🎯 ";
        el.appendChild(input);
        input.focus();

        const submitPriority = () => {
            const newValue = parseInt(input.value);
            if (isNaN(newValue)) {
                location.reload();
                return;
            }

            fetch(`/group/${id}/update-subtask-priority`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                },
                body: JSON.stringify({ DO_UU_TIEN_MUC: newValue }),
            })
                .then((res) => res.json())
                .then((data) => {
                    location.reload(); // ✅ Reload lại trang khi cập nhật thành công
                })
                .catch(() => location.reload()); // reload luôn nếu lỗi
        };

        input.addEventListener("blur", submitPriority); // Khi mất focus
        input.addEventListener("keydown", (e) => {
            if (e.key === "Enter") {
                submitPriority(); // Khi nhấn Enter
            }
        });
    };
});
