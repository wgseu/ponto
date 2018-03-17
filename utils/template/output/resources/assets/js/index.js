Gerenciar.$[table.unix] = {};
Gerenciar.$[table.unix].init = function () {
    $('#search').focus();
$[field.each]
$[field.if(enum)]
    $('#$[field]').change(function () {
        $(this).closest('form').submit();
    });
$[field.end]
$[field.end]
    ajaxLink('$[table.unix]-delete').on('$[table.unix]-delete', function (ev, url) {
        var row = $(this).closest('tr');
        $.get(makeurl(url, { saida: 'json' }), function (data) {
            if (data.status != 'ok') {
                $('.thunder-container').message('error', data.msg);
            } else {
                $('.thunder-container').message('success', data.msg, { autoClose: { enable: true } });
                row.remove();
            }
        });
    });
};
Gerenciar.$[table.unix].initForm = function (focus_ctrl) {
$[field.each]
$[field.if(image)]
    Image.upload.initialize('#$[field]_container');
$[field.else.if(blob)]
    Image.upload.initialize('#$[field]_container');
$[field.else.if(reference)]
$[field.if(searchable)]
    Gerenciar.$[reference.unix].initField('#$[field]');
$[field.end]
$[field.else.if(integer)]
    $('#$[field]').autoNumeric('init');
$[field.else.if(currency)]
    $('#$[field]').autoNumeric('init');
$[field.else.if(float)]
    $('#$[field]').autoNumeric('init');
$[field.else.if(datetime)]
    $.datetimepicker.setLocale('pt-BR');
    $('#$[field]').datetimepicker({
        format:'d/m/Y H:i'
    });
$[field.else.if(date)]
    $.datetimepicker.setLocale('pt-BR');
    $('#$[field]').datetimepicker({
        timepicker: false,
        format:'d/m/Y'
    });
$[field.else.if(time)]
    $.datetimepicker.setLocale('pt-BR');
    $('#$[field]').datetimepicker({
        datepicker: false,
        format:'H:i'
    });
$[field.else.if(masked)]
    $('#$[field]').each(function() {
        $(this).mask($(this).attr('mask'));
    });
$[field.end]
$[field.end]
    if (focus_ctrl != '') {
        $('#' + focus_ctrl).focus();
    }
};
$[table.exists(descriptor)]
Gerenciar.$[table.unix].initField = function (field) {
    Gerenciar.common.autocomplete('/gerenciar/$[table.unix]/', field + '_input', undefined,
        function (data) {
            return { value: data.$[descriptor], title: data.$[primary] };
        }$[table.exists(image)]$[field.each]$[field.if(image)],
        function (data) {
            return data.$[field] || '/static/img/$[field.image.default]';
        }$[field.end]$[field.end]$[table.else], undefined$[table.end], undefined, undefined, field
    );
};
$[table.end]