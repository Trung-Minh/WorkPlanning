window.subtaskData = window.subtaskData || {};

window.showModal = function(id) {
    document.getElementById(id)?.classList.remove('hidden');
};

window.hideModal = function(id) {
    document.getElementById(id)?.classList.add('hidden');
};

window.showAddTaskModal = function(ID_KH) {
    document.getElementById('task_kehoach_id').value = ID_KH;
    showModal('modalAddTask');
};

window.showAddSubTaskModal = function(ID_CV) {
    document.getElementById('subtask_congviec_id').value = ID_CV;
    showModal('modalAddSubTask');
};

window.showViewSubtaskModal = function(id) {
    const muc = window.subtaskData[id];
    if (!muc) return alert("Không tìm thấy dữ liệu.");
    document.getElementById('viewTenMuc').textContent = muc.TEN_MUC || '';
    document.getElementById('viewNoiDung').textContent = muc.NOI_DUNG_CHI_TIET || '';
    document.getElementById('viewDeadline').textContent = muc.THOI_HAN_HOAN_THANH || '';
    document.getElementById('viewUuTien').textContent = muc.DO_UU_TIEN_MUC || '';
    showModal('modalViewSubTask');
};

window.showEditSubtaskModal1 = function(id) {
    const muc = window.subtaskData[id];
    if (!muc) return alert("Không tìm thấy mục công việc.");

    document.getElementById('formEditSubTask').action = `/muc-cong-viec/${id}/sua`;
    document.getElementById('editTenMuc').value = muc.TEN_MUC || '';
    document.getElementById('editNoiDung').value = muc.NOI_DUNG_CHI_TIET || '';
    document.getElementById('editDeadline').value = muc.THOI_HAN_HOAN_THANH?.replace(' ', 'T') || '';
    document.getElementById('editUuTien').value = muc.DO_UU_TIEN_MUC || '';

    // 👇 Thêm dòng này để xử lý checkbox hoàn thành
    document.getElementById('editTrangThai').checked = muc.TRANG_THAI == 1;

    showModal('modalEditSubTask');
};



window.confirmDelete = function(actionUrl, title, message) {
    document.getElementById('formConfirmDelete').action = actionUrl;
    document.getElementById('modalDeleteTitle').textContent = title;
    document.getElementById('modalDeleteMessage').textContent = message;
    showModal('modalConfirmDelete');
};

window.setupInlineEditing = function(csrfToken) {
    document.querySelectorAll('.editable').forEach(el => {
        el.addEventListener('dblclick', () => {
            const type = el.classList.contains('plan-title') ? 'plan' :
                         el.classList.contains('task-title') ? 'task' :
                         el.classList.contains('task-progress') ? 'task-progress' :
                         el.classList.contains('task-priority') ? 'task-priority' :
                         el.classList.contains('subtask-title') ? 'subtask' :
                         el.classList.contains('subtask-deadline') ? 'deadline' :
                         el.classList.contains('subtask-priority') ? 'priority' : null;
            const id = el.dataset.id;
            const current = el.textContent.trim();
            const input = document.createElement('input');
            input.className = 'border p-1';

            if (type === 'deadline') {
                input.type = 'datetime-local';
            } else if (['priority', 'task-priority'].includes(type)) {
                input.type = 'number';
                input.min = 1;
                input.max = 10;
            } else if (type === 'task-progress') {
                input.type = 'number';
                input.min = 0;
                input.max = 100;
            }

            input.value = current;
            el.replaceWith(input);
            input.focus();

            input.addEventListener('blur', () => {
                const newValue = input.value;
                let url, field;
                switch(type) {
                    case 'plan': url = `/ke-hoach/${id}`; field = 'TEN_KE_HOACH'; break;
                    case 'task': url = `/cong-viec/${id}`; field = 'TEN_CV'; break;
                    case 'task-progress': url = `/cong-viec/${id}`; field = 'TIEN_DO'; break;
                    case 'task-priority': url = `/cong-viec/${id}`; field = 'DO_UU_TIEN'; break;
                    case 'deadline': url = `/muc-cong-viec/${id}`; field = 'THOI_HAN_HOAN_THANH'; break;
                    case 'priority': url = `/muc-cong-viec/${id}`; field = 'DO_UU_TIEN_MUC'; break;
                    case 'subtask': url = `/muc-cong-viec/${id}`; field = 'TEN_MUC'; break;
                }

                fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ [field]: newValue })
                }).then(() => location.reload())
                  .catch(err => {
                      alert("Lỗi khi cập nhật: " + err.message);
                      location.reload();
                  });
            });
        });
    });
};

function handleDelete(event) {
    event.preventDefault();

    const form = event.target;
    const actionUrl = form.action;

    fetch(actionUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: new FormData(form)
    })
    .then(response => {
        if (response.ok) {
            hideModal('modalConfirmDelete');

            // Xoá phần tử có data-id tương ứng
            const deletedId = actionUrl.split('/').pop();
            const element = document.querySelector(`[data-id="${deletedId}"]`);
            if (element) {
                element.remove();
            }
        } else {
            alert('Lỗi khi xoá. Vui lòng thử lại.');
        }
    });

    return false;
}

