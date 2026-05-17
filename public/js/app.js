const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// --- Toast notifications ---

function showToast(msg, type, duration) {
    if (!type) type = 'success';
    if (!duration) duration = 3200;

    const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', info: 'fa-circle-info' };
    const colors = { success: '#4dcfcf', error: '#ff6644', info: '#a78bfa' };

    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = 'toast t-' + type;
    toast.innerHTML =
        '<i class="fa-solid ' + (icons[type] || icons.success) + '" style="color:' + (colors[type] || colors.success) + '; font-size:14px; flex-shrink:0;"></i>' +
        '<span>' + msg + '</span>';
    container.appendChild(toast);

    setTimeout(function () { toast.classList.add('visible'); }, 10);
    setTimeout(function () {
        toast.classList.remove('visible');
        setTimeout(function () { toast.remove(); }, 350);
    }, duration);
}

window.showToast = showToast;

// --- Flash messages del servidor ---

if (window.__flash) {
    showToast(window.__flash.msg, window.__flash.type);
}

// --- Cambio de tema claro / oscuro ---

const themeForm = document.querySelector('form[action*="/tema"]');
if (themeForm) {
    themeForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const html = document.documentElement;
        const toLight = html.classList.contains('dark');

        html.classList.toggle('dark', !toLight);
        html.classList.toggle('light', toLight);

        const btn = themeForm.querySelector('button[type="submit"]');
        const textSpan = btn.querySelector('span:first-child');
        const symbolSpan = btn.querySelector('span:last-child');

        if (toLight) {
            textSpan.innerHTML = '<i class="fa-regular fa-moon" style="width:15px;text-align:center;"></i> Modo oscuro';
            symbolSpan.textContent = '☾';
            btn.title = 'Cambiar a modo oscuro';
        } else {
            textSpan.innerHTML = '<i class="fa-regular fa-sun" style="width:15px;text-align:center;"></i> Modo claro';
            symbolSpan.textContent = '☀';
            btn.title = 'Cambiar a modo claro';
        }

        fetch(themeForm.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF },
            body: new FormData(themeForm),
        });
    });
}

// --- Modal de confirmación de borrado ---

const confirmOverlay = document.getElementById('confirm-overlay');
const confirmMsg = document.getElementById('confirm-msg');
const confirmOk = document.getElementById('confirm-ok');
const confirmCancel = document.getElementById('confirm-cancel');
let formToDelete = null;

function openDeleteModal(msg, form) {
    confirmMsg.textContent = msg;
    formToDelete = form;
    confirmOverlay.classList.add('open');
}

function closeDeleteModal() {
    confirmOverlay.classList.remove('open');
    formToDelete = null;
}

confirmCancel.addEventListener('click', closeDeleteModal);

confirmOverlay.addEventListener('click', function (e) {
    if (e.target === confirmOverlay) closeDeleteModal();
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeDeleteModal();
});

confirmOk.addEventListener('click', function () {
    if (!formToDelete) return;
    const form = formToDelete;
    closeDeleteModal();
    HTMLFormElement.prototype.submit.call(form);
});

document.addEventListener('submit', function (e) {
    const form = e.target;
    if (!form.querySelector('input[name="_method"][value="DELETE"]')) return;
    e.preventDefault();
    openDeleteModal(form.dataset.confirm || '¿Estás seguro de que quieres eliminar este elemento?', form);
});

// --- Página de tareas: cambio de estado ---

const STATUS_COLORS = { pendiente: '#a78bfa', en_progreso: '#88aaff', completada: '#4dcfcf' };
const STATUS_LABELS = { pendiente: 'Pendiente', en_progreso: 'En progreso', completada: 'Completada' };

function updateTaskUI(taskId, newStatus) {
    const color = STATUS_COLORS[newStatus];
    const isDone = newStatus === 'completada';
    const isLight = document.documentElement.classList.contains('light');

    const row = document.getElementById('task-row-' + taskId);
    if (!row) return;

    const dot = document.getElementById('dot-' + taskId);
    const label = document.getElementById('status-label-' + taskId);
    const title = row.querySelector('.task-title');
    const select = row.querySelector('.status-select');
    const toggle = row.querySelector('.task-toggle');

    select.value = newStatus;
    select.style.color = color;
    select.style.borderColor = color + '55';

    dot.style.background = color;
    dot.style.boxShadow = '0 0 5px ' + color + '66';
    label.textContent = STATUS_LABELS[newStatus];

    row.style.opacity = isDone ? '0.5' : '1';
    title.style.textDecoration = isDone ? 'line-through' : 'none';

    toggle.dataset.estado = newStatus;
    toggle.innerHTML = isDone ? '✓' : '';
    toggle.title = 'Marcar como ' + (isDone ? 'pendiente' : 'completada');
    toggle.style.background = isDone ? 'rgba(77,207,207,0.18)' : 'transparent';
    toggle.style.borderColor = isDone ? '#4dcfcf' : (isLight ? 'rgba(109,40,217,0.22)' : 'rgba(255,255,255,0.18)');
    toggle.classList.toggle('is-done', isDone);

    row.style.background = color + '12';
    setTimeout(function () { row.style.background = ''; }, 420);
}

async function patchStatus(taskId, newStatus) {
    try {
        const res = await fetch('/tasks/' + taskId + '/status', {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ estado: newStatus }),
        });
        if (!res.ok) throw new Error();
        showToast('Marcado como ' + STATUS_LABELS[newStatus], newStatus === 'completada' ? 'success' : 'info');
    } catch (_) {
        location.reload();
    }
}

document.querySelectorAll('.status-select').forEach(function (select) {
    select.addEventListener('change', function () {
        updateTaskUI(this.dataset.taskId, this.value);
        patchStatus(this.dataset.taskId, this.value);
    });
});

document.querySelectorAll('.task-toggle').forEach(function (button) {
    button.addEventListener('click', function () {
        const newStatus = this.dataset.estado === 'completada' ? 'pendiente' : 'completada';
        updateTaskUI(this.dataset.taskId, newStatus);
        patchStatus(this.dataset.taskId, newStatus);
    });
});
