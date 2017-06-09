Gerenciar.$[table.unix] = {};
Gerenciar.$[table.unix].init = function() {
    $('#query').focus();
$[field.each]
$[field.if(enum)]
    $('#$[field]').change(function() {
        $(this).closest('form').submit();
    });
$[field.end]
$[field.end]
    ajaxLink('$[table.unix]-delete').on('$[table.unix]-delete', function (ev, url) {
        var row = $(this).closest('tr');
        $.get(makeurl(url, {saida: 'json'}), function (data){
            if(data.status != 'ok') {
                $('.thunder-container').message('error', data.msg);
                return;
            }
            $('.thunder-container').message('success', data.msg, { autoClose: { enable: true } });
            row.remove();
        });
    });
};
Gerenciar.$[table.unix].initForm = function(focus_ctrl) {
$[field.each]
$[field.if(image)]
    Image.upload.initialize('#$[field]_container');
$[field.else.if(blob)]
    Image.upload.initialize('#$[field]_container');
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
