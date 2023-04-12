require( 'datatables.net-bs5' );
require( 'datatables.net-datetime' );
require( 'datatables.net-responsive-bs5' );
require( 'datatables.net-select-bs5' );


$(document).ready( function () {

    window.$table = $('[data-component="dataTable"]');
    var filters = $table.data('filter');

    // Подключение дополнительных фильтров
    $(document).on('keyup', '[data-action="dataTableFilterInput"]', function () {
        $table.DataTable().ajax.reload();
    });
    $(document).on('search', '[data-action="dataTableFilterInput"]', function () {
        $table.DataTable().ajax.reload();
    });
    $(document).on('change', '[data-action="dataTableFilterSelect"]', function () {
        $table.DataTable().ajax.reload();
    });


    let data = {};
    $.each(filters, function (key, value) {
        data[value] = function () {
            return $('#' + value).val()
        }
    });

    // Инициализация компонента DataTable
    $table.DataTable(_.merge({
        ajax: {
            data: data
        },
    },dataTablesOptions));

    // Удаление модели
    $(document).on('click', '[data-action="deleteModel"]', function () {
        let $btn = $(this),
            slug = $btn.data('slug'),
            id = $btn.data('id');

        Swal.fire({
            title: 'Удалить запись?',
            showCancelButton: true,
            showCloseButton: true,
            cancelButtonText: 'Отмена',
            confirmButtonText: 'Да, удалить',
            cancelButtonColor: '#d33',
            confirmButtonColor: '#556ee6',
            width: 400,
            allowOutsideClick: false
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: window.route("adminpanel.datatype.destroy",{datatype:slug,id:id}),
                    type: 'DELETE',
                    dataType: 'JSON',
                    success: function (data) {
                        if (data.status) {
                            toastr.success(data.message);
                            $table.DataTable().ajax.reload(null, false);
                        } else {
                            toastr.error(data.message);
                        }
                    }
                });
            }
        });
    });

});
