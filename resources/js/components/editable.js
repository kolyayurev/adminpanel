
//Редактирование полей из таблицы
$(document).on('click', '[data-action="editCell"]:not(.--edit)', function (event) {
    let _this = this;
    $.ajax({
        url: window.route("adminpanel.datatype.edit-field",{ datatype:$(this).data('datatype') }),
        type: 'POST',
        dataType: 'JSON',
        data: {
            'id': $(this).data('id'),
            'field': $(this).data('field'),
        },
        success: function (result) {
            if (result.status) {
                $(_this).addClass('--edit')
                $(_this).html(result.template)
            } else {
                toastr.error(data.message);
            }
        }
    });
});
$(document).on('submit','[data-action="updateCell"]', function (event) {
    event.preventDefault();

    let _this = this;

    let data = new FormData($(this)[0]);

    $.ajax({
        url: window.route("adminpanel.datatype.update-field",{ datatype:$(this).data('datatype') }),
        type: 'POST',
        data: data,
        contentType: false,
        processData: false,
        success: function (result) {
            if (result.status) {
                $table.DataTable().ajax.reload();
            } else {
                toastr.error(data.message);
            }
        }
    });
});

$(document).on('click', '[data-action="editStatus"]', function (event) {
    let _this = this;
    let data = {
        'id': $(this).data('id'),
    };
    data[$(this).data('field')] = $(this).val();

    $.ajax({
        url: window.route("adminpanel.datatype.update-field",{ datatype:$(this).data('datatype') }),
        type: 'POST',
        dataType: 'JSON',
        data: data,
        success: function (result) {
            if (result.status) {
                $table.DataTable().ajax.reload();
            } else {
                toastr.error(data.message);
            }
        }
    });
});



$(document).on('reset','[data-action="updateCell"]', function (event) {
    event.preventDefault();
    $table.DataTable().ajax.reload();
});
