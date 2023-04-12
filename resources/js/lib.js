

/**
 * Модальные окна
 */
export function getModalWindow(modalName, callback, existId, extraData)
{
    let ajaxData = {
        'modalName': modalName
    }

    if (typeof existId !== 'undefined') ajaxData.existId = existId;
    if (typeof extraData !== 'undefined') ajaxData.extraData = extraData;

    //TODO:
    $.ajax({
        url: window.route("adminpanel.getModal"),
        type: 'POST',
        data: ajaxData,
        dataType: 'JSON'
    }).done((result) => {
        let modalElement = $('#modalContainer').html(result.template).find('.modal');
        let modal = new bootstrap.Modal(modalElement);
        modal.show();
        modalElement
            .on('hidden.bs.modal', (e) => $(e.currentTarget).remove())
            .on('shown.bs.modal', (e) => {
                $(e.target).find('[data-component="fileinput"]').each(function (i, el) {
                    $(el).fileinput({
                        showUpload: false,
                        dropZoneEnabled: false,
                        allowedFileExtensions: $(el).data('allowed'),
                        maxFileCount: 10,
                        inputGroupClass: "input-group",
                        fileActionSettings: {
                            showZoom: false
                        },
                        language: "ru"
                    });
                });
                if (typeof callback === 'function') callback($(e.target));
            });
    });
}

/**
 * Валидация и ajax отправка формы
 */
// Валидация и ajax отправка формы
export function validateAndAjaxSubmitForm($form,
                                   callbackSuccess,
                                   callbackError = (r) => { defaultCallbackError(r);},
                                   callbackBeforeSend) {
    validateForm($form, ($f) => {
        ajaxSubmitForm($f, callbackSuccess, callbackError, callbackBeforeSend);
    })
}

// Валидация формы
export function validateForm($form, callbackSuccess) {
    $form.on('submit', function(e) {
        e.preventDefault();
        callbackSuccess($(e.target));
    })
}

// Аяксовая отправка формы
export function ajaxSubmitForm($form,
                        callbackSuccess,
                        callbackError ,
                        callbackBeforeSend) {

    let isFileForm = $form.attr('enctype') === 'multipart/form-data';

    let ajaxConfig = {
        url : $form.attr('action'),
        type : $form.attr('method'),
        data : isFileForm ? new FormData($form[0]) : $form.serialize(),
        dataType : 'JSON',
        beforeSend : callbackBeforeSend,
    }

    if (isFileForm) {
        ajaxConfig.cache = false;
        ajaxConfig.cache = false;
        ajaxConfig.contentType = false;
        ajaxConfig.processData = false;
    }

    $.ajax(ajaxConfig).done((result) => result.status ? callbackSuccess(result) : callbackError(result));
}
export function defaultCallbackError(result){
    if(result.validation)
    {
        Object.entries(result.errors).forEach(([field, messages]) => {
            messages.forEach(m => toastr.error(m))
        })
    }

}

export function isValidUrl(urlString) {
    var urlPattern = new RegExp('^(https?:\\/\\/)?'+ // validate protocol
        '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // validate domain name
        '((\\d{1,3}\\.){3}\\d{1,3}))'+ // validate OR ip (v4) address
        '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // validate port and path
        '(\\?[;&a-z\\d%_.~+=-]*)?'+ // validate query string
        '(\\#[-a-z\\d_]*)?$','i'); // validate fragment locator

    return !!urlPattern.test(urlString);
}

export function printObject(obj){
    return JSON.stringify(obj);
}

global.printObject = printObject;
