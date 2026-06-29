// Таблицу рендерит el-table в blade (components/datatable.blade.php). Здесь — делегированный
// обработчик удаления строки (кнопка из серверного HTML ячейки actions).
// Перезагрузка таблицы — через window.adminTableReload (его выставляет приложение таблицы).

$(document).ready(function () {
    // Удаление модели
    $(document).on('click', '[data-action="deleteModel"]', function () {
        let slug = $(this).data('slug'),
            id = $(this).data('id');

        Swal.fire({
            title: 'Удалить запись?',
            showCancelButton: true,
            cancelButtonText: 'Отмена',
            confirmButtonText: 'Да, удалить',
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: window.route('adminpanel.datatype.destroy', { datatype: slug, id: id }),
                    type: 'DELETE',
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status) {
                            toastr.success(data.message);
                            window.adminTableReload && window.adminTableReload();
                        } else {
                            toastr.error(data.message);
                        }
                    },
                });
            }
        });
    });
});
