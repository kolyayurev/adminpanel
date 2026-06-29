// Тонкая обёртка нотификаций/диалогов поверх Element Plus.
//
// Сохраняет публичный API legacy-библиотек (`toastr.*`, `Swal.fire`), чтобы не переписывать
// десятки мест вызова: реализация переключена на Element Plus, сигнатуры прежние.
// Это убирает зависимости toastr и sweetalert2 без правок call-site'ов.

import { ElMessage, ElNotification, ElMessageBox } from 'element-plus';

// Без заголовка — компактный ElMessage; с заголовком — ElNotification (как у toastr с title).
function notify(type, message, title) {
    if (title) {
        ElNotification({ type, title, message: message ?? '' });
    } else {
        ElMessage({ type, message: message ?? '', grouping: true });
    }
}

export const toastr = {
    success: (message, title) => notify('success', message, title),
    error: (message, title) => notify('error', message, title),
    warning: (message, title) => notify('warning', message, title),
    info: (message, title) => notify('info', message, title),
    // Совместимость с `toastr.options.* = ...` (no-op).
    options: {},
};

export const Swal = {
    // Совместимость со SweetAlert2: возвращаем thenable с { value, isConfirmed },
    // как ожидают существ; вызовы (`.then(r => r.value)`).
    fire(opts = {}) {
        return ElMessageBox.confirm(opts.text ?? '', opts.title ?? '', {
            confirmButtonText: opts.confirmButtonText ?? 'OK',
            cancelButtonText: opts.cancelButtonText ?? 'Отмена',
            showCancelButton: opts.showCancelButton !== false,
            type: 'warning',
        })
            .then(() => ({ isConfirmed: true, value: true }))
            .catch(() => ({ isConfirmed: false, value: false, dismiss: 'cancel' }));
    },
};
