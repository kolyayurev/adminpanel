$(document).ready(function () {
    // Скрытие loader
    $('.ap-loader').animate({opacity: 0}, 500).hide('slow');

    // Select2
    $('select.select2').select2({
        theme: "bootstrap-5",
        tags: false,
    });
    // .on('select2:select', function(e){
    //     var id = e.params.data.id;
    //     var option = $(e.target).children('[value="'+id+'"]');
    //     option.detach();
    //     $(e.target).append(option).change();
    // });

    // accordion
    if(Cookies.get('activeCollapse'))
        $("#"+Cookies.get('activeCollapse')).addClass('show');

    $('.accordion')
        .on('shown.bs.collapse', function () {
            Cookies.set('activeCollapse',$(this).find(".show").attr('id'));
        })
        .on('hidden.bs.collapse', function () {
            Cookies.remove('activeCollapse')
        });
    // tabs
    if(Cookies.get('activeTab')){
        $('.nav-tabs button[data-bs-target="'+Cookies.get('activeTab')+'"]').trigger("click");
    }

    $('.nav-tabs')
        .on('shown.bs.tab', function (event) {
            Cookies.set('activeTab',$(event.target).data("bs-target"));
        });
    // Валидация формы
    // (function() {
    //     'use strict';
    //     window.addEventListener('load', function() {
    //         let forms = $('form[novalidate]');
    //         Array.prototype.filter.call(forms, function(form) {
    //             form.addEventListener('submit', function(event) {
    //                 if (form.checkValidity() === false) {
    //                     event.preventDefault();
    //                     event.stopPropagation();
    //                     form.classList.add('was-validated');
    //                 }
    //             }, false);
    //         });
    //     }, false);
    // })();

    // Скрытие alert
    setTimeout(function () {
        $('.alert-hide').animate({opacity: 0}, 500).hide('slow');
    }, 5000);

    let multilingual = $('#contentBody[data-multilingual]');
    if (multilingual.length) {
        switch (multilingual.data('mode')) {
            case 'list':
                multilingual.multilingual();
                $table.DataTable().on('draw.dt', function () {
                    // MB trouble
                    $('#contentBody').data('multilingual').init();
                })
                break;
            case 'form':
                multilingual.multilingual({"editing": true, "vueInstances": vueFieldInstances});
                break;
            case 'show':
            default:
                multilingual.multilingual();
        }
    }
    $('#contentBody input[data-alias-source]').each(function (i, el) {
        $(el).slugify();
    });

    // TODO:
    // $('.datepicker').datetimepicker();

});
