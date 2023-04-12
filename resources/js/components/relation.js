$(document).ready( function (){
    $('select.select2-ajax[data-component="relation"]').each(function() {
        let url = $(this).data('pagetype') ? route("adminpanel.settings.relation",{ name:$(this).data('pagetype') }) : route("adminpanel.datatype.relation",{ datatype:$(this).data('datatype') });
        console.log(url)
        $(this).select2({
            theme: "bootstrap-5",
            width: '100%',
            ajax: {
                url: url,
                data: function (params) {
                    let query = {
                        search: params.term,
                        field: $(this).data('field'),
                        method: $(this).data('method'),
                        id: $(this).data('id'),
                        page: params.page || 1
                    }
                    return query;
                }
            }
        });

        $(this).on('select2:select',function(e){
            var data = e.params.data;
            if (data.id === '') {
                // "None" was selected. Clear all selected options
                $(this).val([]).trigger('change');
            } else {
                $(e.currentTarget).find("option[value='" + data.id + "']").attr('selected','selected');
            }
        });

        $(this).on('select2:unselect',function(e){
            var data = e.params.data;
            $(e.currentTarget).find("option[value='" + data.id + "']").attr('selected',false);
        });
    });
    $('select.select2-taggable[data-component="relation"]').select2({
        theme: "bootstrap-5",
        width: '100%',
        tags: true,
        createTag: function (params) {
            var term = $.trim(params.term);

            if (term === '') {
                return null;
            }

            return {
                id: term,
                text: term,
                newTag: true
            }
        }
    }).on('select2:selecting', function (e) {
        let $el = $(this);
        let route = $el.data('route');
        let label = $el.data('label');
        let errorMessage = $el.data('error-message');
        let newTag = e.params.args.data.newTag;

        if (!newTag) return;

        $el.select2('close');

        $.post(route, {
            [label]: e.params.args.data.text,
            _tagging: true,
        }).done(function (data) {
            var newOption = new Option(e.params.args.data.text, data.data.id, false, true);
            $el.append(newOption).trigger('change');
        }).fail(function (error) {
            toastr.error(errorMessage);
        });

        return false;
    }).on('select2:select', function (e) {
        if (e.params.data.id === '') {
            // "None" was selected. Clear all selected options
            $(this).val([]).trigger('change');
        }
    });
});
