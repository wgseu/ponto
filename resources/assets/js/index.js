$.fn.exists = function() {
  return this.length !== 0;
};
function isFunction(functionToCheck) {
  var getType = {};
  return (
    functionToCheck &&
    getType.toString.call(functionToCheck) === '[object Function]'
  );
}
function isObject(object) {
  return object !== null && typeof object === 'object';
}
Number.prototype.formatMoney = function(c, d, t) {
  var n = this,
    c = isNaN((c = Math.abs(c))) ? 2 : c,
    d = d == undefined ? '.' : d,
    t = t == undefined ? ',' : t,
    s = n < 0 ? '-' : '',
    i = parseInt((n = Math.abs(+n || 0).toFixed(c))) + '',
    j = (j = i.length) > 3 ? j % 3 : 0;
  return (
    s +
    (j ? i.substr(0, j) + t : '') +
    i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) +
    (c
      ? d +
        Math.abs(n - i)
          .toFixed(c)
          .slice(2)
      : '')
  );
};
var Util = {};
Util.toMoney = function(f) {
  return f.formatMoney(2, ',', '.');
};
Util.toFloat = function(text) {
  var f = parseFloat(text.replace(/[,]/, '.'), 10);
  return isNaN(f) ? 0.0 : f;
};
Util.checkVal = function(f) {
  if (f.is(':checked')) return f.val();
  return 'N';
};
function makeurl(url, params) {
  if (url.indexOf('?') < 0) return url + '?' + $.param(params);
  return url + '&' + $.param(params);
}

function ajaxLink(eventName) {
  var elem = $('a.ajaxlink');
  elem.click(function() {
    var ask = $(this).attr('ask');
    if (ask && !confirm(ask)) {
      return false;
    }
    if (eventName != undefined) {
      $(this).trigger(eventName, [$(this).attr('href')]);
      return false;
    }
    return true;
  });
  return elem;
}

var Upload = {};
Upload.image = {};
Upload.image.initialize = function(img_id) {
  function readURL(input, imgid) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      var img = $(input)
        .closest(imgid)
        .find('img');
      reader.onload = function(e) {
        img.attr('src', e.target.result);
        img.css('display', 'inline');
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
  $(img_id + ' > input[type=file]').each(function() {
    $(this).change(function() {
      readURL(this, img_id);
    });
  });
  $(img_id + ' > .mask').each(function() {
    var img = $(this)
      .closest(img_id)
      .find('img');
    $(this).css('border-width', img.height() / 2 + 'px 0px');
  });
  $(img_id + ' > .mask > a').each(function() {
    var img = $(this)
      .closest(img_id)
      .find('img');
    if (img.width() < 50) {
      $(this).css('background-size', '16px 16px');
      $(this).css('width', '16px');
      $(this).css('height', '16px');
      $(this).css('top', '-8px');
    }
  });
  $(img_id + ' .img-open').click(function(e) {
    e.preventDefault();
    var input = $(this)
      .parent()
      .parent()
      .find('input[type=file]');
    input.trigger('click');
  });
  $(img_id + ' .img-remove').click(function(e) {
    e.preventDefault();
    var input = $(this)
      .parent()
      .parent()
      .find('input[type=file]');
    input.replaceWith((input = input.clone(true)));
    var image = input.parent().find('img');
    var name = image.attr('tag');
    if (name == undefined || name.length == 0) name = 'image';
    image.attr('src', image.attr('no-picture'));
    image.css('display', 'inline');
    input
      .parent()
      .find('input[type=hidden]')
      .val('');
    input.val('');
  });
};
Upload.form = function(selector, event) {
  function success(data) {
    if (data.status != 'ok') {
      $('.thunder-container').message('error', data.msg);
      if (data.hasOwnProperty('errors')) {
        $('#' + Object.keys(data.errors)[0]).focus();
      }
      return;
    }
    $('.thunder-container').message('success', data.msg, {
      autoClose: { enable: true }
    });
    $('html, body').animate({ scrollTop: 0 }, 'slow');
    if (event != undefined) $(selector).trigger(event, [data]);
  }

  $(selector).submit(function() {
    var attr = $(this).attr('enctype');
    if (typeof attr == typeof undefined || attr == false) {
      $.post(
        makeurl($(this).attr('action'), { saida: 'json' }),
        $(this).serialize(),
        success
      );
      return false;
    }
    var formData = new FormData(this);
    $.ajax({
      url: makeurl($(this).attr('action'), { saida: 'json' }),
      type: 'POST',
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      success: success
    });
    return false;
  });
};

var Cliente = {};
Cliente.conta = {};
Cliente.conta.initForm = function(focus_ctrl) {
  $('#cliente-form #fone1').mask($('#cliente-form #fone1').attr('mask'));
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
var Gerenciar = {};

Gerenciar.common = {};
Gerenciar.common.load = function(
  url,
  params,
  field,
  displayfn,
  itemsfn,
  eachfn
) {
  $.get(url, params, function(data) {
    if (data.status != 'ok') {
      $('.thunder-container').message('error', data.msg);
      return;
    }
    $(field).empty();
    var items = [];
    if (itemsfn == undefined || itemsfn == null) items = data.items;
    else items = itemsfn(data);
    for (var i = 0; i < items.length; i++) {
      var item = items[i];
      var option = $('<option></option>');
      var display = displayfn(item);
      option.val(display.value);
      option.text(display.title);
      if (eachfn != undefined) eachfn(option, item);
      $(field).append(option);
    }
  });
};
Gerenciar.common.autocomplete = function(
  url,
  input,
  selectfn,
  displayfn,
  def_image,
  paramfn,
  itemsfn,
  field,
  attribute
) {
  if (attribute == undefined) attribute = 'data-content';
  if (field == undefined) field = input + '_ref';

  function clearInput() {
    $(input).val('');
    $(field).val('');
    $(field).removeAttr(attribute);
    var spanId = $(input)
      .closest('div')
      .find('label .identifier');
    spanId.text('');
    if (selectfn != undefined && selectfn != null) selectfn(null);
  }

  $(input).autocomplete({
    lookup: function(search, done) {
      var params = { search: search, saida: 'json' };
      if (paramfn != undefined) params = paramfn(search);
      $.get(url, params, function(response) {
        var result = { suggestions: [] };
        if (response.status == 'ok') {
          var items = [];
          if (itemsfn == undefined) items = response.items;
          else items = itemsfn(response);
          result = {
            suggestions: $.map(items, function(item) {
              return { value: displayfn(item).value, data: item };
            })
          };
        }
        done(result);
      });
    },
    onSelect: function(suggestion) {
      var display = displayfn(suggestion.data);
      $(field).val(suggestion.data.id);
      $(field).attr(attribute, display.value);
      var spanId = $(input)
        .closest('div')
        .find('label .identifier');
      spanId.text(display.title);
      if (selectfn != undefined && selectfn != null) selectfn(suggestion.data);
    },
    formatResult: function(suggestion, currentValue) {
      var result = $.Autocomplete.formatResult(suggestion, currentValue);
      if (def_image == undefined) return result;
      var imagemurl = null;
      var classes = '';
      var style = '';
      var src = '';
      var tag = 'img';
      if (isFunction(def_image)) {
        imagemurl = def_image(suggestion.data);
        if (isObject(imagemurl)) {
          classes = imagemurl.classes;
          style = imagemurl.style;
          tag = imagemurl.tag || tag;
          imagemurl = imagemurl.url;
          if (classes != '') {
            classes = ' class="' + classes + '"';
          }
          if (style != '') {
            style = ' style="' + style + '"';
          }
        }
      } else {
        imagemurl = suggestion.data.imagemurl;
        if (imagemurl == null) imagemurl = def_image;
      }
      if (tag == 'img') {
        src = ' src="' + imagemurl + '"';
      }
      return (
        '<div><' +
        tag +
        src +
        classes +
        style +
        '/><p>' +
        result +
        '</p><p>' +
        displayfn(suggestion.data).title +
        '</p></div>'
      );
    }
  });
  $(input).blur(function() {
    if ($(input).val() == $(field).attr(attribute)) return;
    clearInput();
  });
  $(input).bind('input', function() {
    if ($(input).val() != '') return;
    clearInput();
  });
};

Gerenciar.diversos = {};
Gerenciar.diversos.init = function(d1, doughnutData, meta_val, meta_max) {
  //define chart clolors ( you maybe add more colors if you want or flot will add it automatic )
  var chartColours = [
    '#96CA59',
    '#3F97EB',
    '#72c380',
    '#6f7a8a',
    '#f7cb38',
    '#5a8022',
    '#2c7282'
  ];
  var chartMinDate; //first day
  var chartMaxDate; //last day
  if (d1.length > 0) {
    chartMinDate = d1[0][0]; //first day
    chartMaxDate = d1[d1.length - 1][0]; //last day
  }
  var tickSize = [1, 'day'];
  var tformat = '%d/%m/%y';

  //graph options
  var options = {
    grid: {
      show: true,
      aboveData: true,
      color: '#3f3f3f',
      labelMargin: 10,
      axisMargin: 0,
      borderWidth: 0,
      borderColor: null,
      minBorderMargin: 5,
      clickable: true,
      hoverable: true,
      autoHighlight: true,
      mouseActiveRadius: 100
    },
    series: {
      lines: {
        show: true,
        fill: true,
        lineWidth: 2,
        steps: false
      },
      points: {
        show: true,
        radius: 4.5,
        symbol: 'circle',
        lineWidth: 3.0
      }
    },
    colors: chartColours,
    shadowSize: 0,
    tooltip: true, //activate tooltip
    tooltipOpts: {
      content: '%s: %y.0',
      xDateFormat: '%d/%m',
      shifts: {
        x: -30,
        y: -50
      },
      defaultTheme: false
    },
    yaxis: {
      tickDecimals: 2,
      tickFormatter: function(val) {
        return (
          $('#placeholder33x').data('symbol') +
          ' ' +
          val
            .toFixed(2)
            .replace(/\./g, ',')
            .replace(/\B(?=(?:\d{3})+(?!\d))/g, '.')
        );
      },
      min: 0
    },
    xaxis: {
      mode: 'time',
      minTickSize: tickSize,
      timeformat: tformat,
      min: chartMinDate,
      max: chartMaxDate
    }
  };
  $.plot(
    $('#placeholder33x'),
    [
      {
        data: d1,
        lines: {
          fillColor: 'rgba(150, 202, 89, 0.12)'
        }, //#96CA59 rgba(150, 202, 89, 0.42)
        points: {
          fillColor: '#fff'
        }
      }
    ],
    options
  );

  var previousPoint = null,
    previousLabel = null;
  $.fn.UseTooltip = function() {
    $(this).bind('plothover', function(event, pos, item) {
      if (item) {
        if (
          previousLabel != item.series.label ||
          previousPoint != item.dataIndex
        ) {
          previousPoint = item.dataIndex;
          previousLabel = item.series.label;
          $('#tooltip').remove();
          var dat = item.datapoint[0];
          var val = item.datapoint[1];
          var x = moment.unix(dat).format('DD/MM/YYYY');
          var y =
            $('#placeholder33x').data('symbol') +
            ' ' +
            val
              .toFixed(2)
              .replace(/\./g, ',')
              .replace(/\B(?=(?:\d{3})+(?!\d))/g, '.');
          var color = item.series.color;
          showTooltip(
            item.pageX,
            item.pageY,
            color,
            '<strong>' + y + '</strong><br/>' + x
          );
        }
      } else {
        $('#tooltip').remove();
        previousPoint = null;
      }
    });
  };
  function showTooltip(x, y, color, contents) {
    $('<div id="tooltip">' + contents + '</div>')
      .css({
        position: 'absolute',
        display: 'none',
        top: y - 40,
        left: x - 10,
        border: '2px solid ' + color,
        padding: '3px',
        'font-size': '9px',
        'border-radius': '5px',
        'background-color': '#fff',
        'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
        opacity: 0.9
      })
      .appendTo('body')
      .fadeIn(200);
  }

  $('#placeholder33x').UseTooltip();

  // begin datepicker
  //! moment.js locale configuration
  //! locale : brazilian portuguese (pt-br)
  //! author : Caio Ribeiro Pereira : https://github.com/caio-ribeiro-pereira

  moment.defineLocale('pt-br', {
    months: 'Janeiro_Fevereiro_Março_Abril_Maio_Junho_Julho_Agosto_Setembro_Outubro_Novembro_Dezembro'.split(
      '_'
    ),
    monthsShort: 'Jan_Fev_Mar_Abr_Mai_Jun_Jul_Ago_Set_Out_Nov_Dez'.split('_'),
    weekdays: 'Domingo_Segunda-feira_Terça-feira_Quarta-feira_Quinta-feira_Sexta-feira_Sábado'.split(
      '_'
    ),
    weekdaysShort: 'Dom_Seg_Ter_Qua_Qui_Sex_Sáb'.split('_'),
    weekdaysMin: 'Do_2ª_3ª_4ª_5ª_6ª_Sá'.split('_'),
    longDateFormat: {
      LT: 'HH:mm',
      LTS: 'HH:mm:ss',
      L: 'DD/MM/YYYY',
      LL: 'D [de] MMMM [de] YYYY',
      LLL: 'D [de] MMMM [de] YYYY [às] HH:mm',
      LLLL: 'dddd, D [de] MMMM [de] YYYY [às] HH:mm'
    },
    calendar: {
      sameDay: '[Hoje às] LT',
      nextDay: '[Amanhã às] LT',
      nextWeek: 'dddd [às] LT',
      lastDay: '[Ontem às] LT',
      lastWeek: function() {
        return this.day() === 0 || this.day() === 6
          ? '[Último] dddd [às] LT' // Saturday + Sunday
          : '[Última] dddd [às] LT'; // Monday - Friday
      },
      sameElse: 'L'
    },
    relativeTime: {
      future: 'em %s',
      past: '%s atrás',
      s: 'poucos segundos',
      m: 'um minuto',
      mm: '%d minutos',
      h: 'uma hora',
      hh: '%d horas',
      d: 'um dia',
      dd: '%d dias',
      M: 'um mês',
      MM: '%d meses',
      y: 'um ano',
      yy: '%d anos'
    },
    ordinalParse: /\d{1,2}º/,
    ordinal: '%dº'
  });
  var cb = function(start, end) {
    $('#reportrange span').html(
      start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY')
    );
  };

  var option = {
    startDate: moment()
      .subtract(1, 'month')
      .startOf('month'),
    endDate: moment().endOf('month'),
    dateLimit: 7776000000, // 90 days
    showDropdowns: true,
    timePicker12Hour: true,
    ranges: {
      Hoje: [moment(), moment()],
      Ontem: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
      'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
      'Esse Mês': [moment().startOf('month'), moment().endOf('month')],
      'Último Mês': [
        moment()
          .subtract(1, 'month')
          .startOf('month'),
        moment()
          .subtract(1, 'month')
          .endOf('month')
      ]
    },
    opens: 'left',
    buttonClasses: ['btn btn-default'],
    applyClass: 'btn-small btn-primary',
    cancelClass: 'btn-small',
    locale: {
      separator: ' para ',
      format: 'DD/MM/YYYY',
      applyLabel: 'Aplicar',
      cancelLabel: 'Limpar',
      fromLabel: 'De',
      toLabel: 'Para',
      customRangeLabel: 'Intervalo',
      firstDay: 1
    }
  };
  cb(option.startDate, option.endDate);
  $('#reportrange').daterangepicker(option, cb);
  $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
    $.get(
      '/gerenciar/diversos/relatorio',
      {
        action: 'faturamento',
        start: picker.startDate.format('YYYY-MM-DD'),
        end: picker.endDate.format('YYYY-MM-DD')
      },
      function(data) {
        if (data.status != 'ok') {
          $('.thunder-container').message('error', data.msg);
          return;
        }
        d1 = [];
        for (var i in data.faturamento) {
          d1.push([data.faturamento[i].data, data.faturamento[i].total]);
        }
        if (d1.length > 0) {
          chartMinDate = d1[0][0]; //first day
          chartMaxDate = d1[d1.length - 1][0]; //last day
        }
        options.xaxis.min = chartMinDate;
        options.xaxis.max = chartMaxDate;
        $.plot(
          $('#placeholder33x'),
          [
            {
              data: d1,
              lines: {
                fillColor: 'rgba(150, 202, 89, 0.12)'
              }, //#96CA59 rgba(150, 202, 89, 0.42)
              points: {
                fillColor: '#fff'
              }
            }
          ],
          options
        );
      }
    );
  });
  var doptions = {
    responsive: false,
    animation: {
      animateScale: true,
      animateRotate: true
    },
    title: {
      display: false
    },
    tooltips: {
      callbacks: {
        label: function(tooltipItem, data) {
          var dataset = data.datasets[tooltipItem.datasetIndex].data;
          var value = dataset[tooltipItem.index];
          return value + '%';
        }
      }
    },
    percentageInnerCutout: 50
  };
  // end datepicker
  new Chart($('#canvas1')[0].getContext('2d'), {
    type: 'doughnut',
    data: doughnutData,
    options: doptions
  });
  var opts = {
    lines: 12, // The number of lines to draw
    angle: 0, // The length of each line
    lineWidth: 0.4, // The line thickness
    pointer: {
      length: 0.75, // The radius of the inner circle
      strokeWidth: 0.042, // The rotation offset
      color: '#1D212A' // Fill color
    },
    limitMax: 'true', // If true, the pointer will not go past the end of the gauge
    colorStart: '#1ABC9C', // Colors
    colorStop: '#1ABC9C', // just experiment with them
    strokeColor: '#F0F3F3', // to see which ones work best for you
    generateGradient: true
  };
  var target = document.getElementById('foo'); // your canvas element
  var gauge = new Gauge(target).setOptions(opts); // create sexy gauge!
  gauge.maxValue = meta_max; // set max gauge value
  gauge.animationSpeed = 32; // set animation speed (32 is default value)
  gauge.set(meta_val); // set actual value
  gauge.setTextField(document.getElementById('gauge-text'));
  $('.quick-list a').click(function() {
    var link = $(this);
    $.get(
      '/gerenciar/diversos/relatorio',
      {
        action: 'meta',
        intervalo: link.attr('range')
      },
      function(data) {
        if (data.status != 'ok') {
          $('.thunder-container').message('error', data.msg);
          return;
        }
        $('#meta-name').text(link.text());
        $('#gauge-text').text(Util.toMoney(data.atual));
        $('#goal-text').text(
          $('#placeholder33x').data('symbol') + Util.toMoney(data.base)
        );
        meta_val = Math.round(data.atual);
        meta_max = Math.max(Math.round(data.base), meta_val);
        gauge.maxValue = meta_max; // set max gauge value
        gauge.set(meta_val); // set actual value
      }
    );
  });
};
Gerenciar.mesa = {};
Gerenciar.mesa.init = function() {
  $('#search').focus();
  $('#ativa').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  ajaxLink('mesa-delete').on('mesa-delete', function(ev, url) {
    var row = $(this).closest('tr');
    $.get(makeurl(url, { saida: 'json' }), function(data) {
      if (data.status != 'ok') {
        $('.thunder-container').message('error', data.msg);
        return;
      }
      $('.thunder-container').message('success', data.msg, {
        autoClose: { enable: true }
      });
      row.remove();
    });
  });
};
Gerenciar.mesa.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.sessao = {};
Gerenciar.sessao.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.sessao.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.caixa = {};
Gerenciar.caixa.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#estado').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.caixa.initForm = function(focus_ctrl) {
  $('#serie').autoNumeric('init');
  $('#numeroinicial').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.banco = {};
Gerenciar.banco.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.banco.initField = function(input, selectfn) {
  Gerenciar.common.autocomplete(
    '/gerenciar/banco/',
    input,
    selectfn,
    function(data) {
      return { value: data.razaosocial, title: data.numero };
    },
    undefined,
    function(search) {
      return { search: search, saida: 'json', limite: 5 };
    }
  );
};
Gerenciar.banco.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.carteira = {};
Gerenciar.carteira.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#tipo').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.banco.initField('#bancoid');
};
Gerenciar.carteira.initForm = function(focus_ctrl) {
  function applyMask() {
    $('#conta').unmask();
    $('#agencia').unmask();
    $('#conta').mask(
      $('#bancoid')
        .data()
        .agenciamascara.replace(/>a/, '*')
    );
    $('#agencia').mask(
      $('#bancoid')
        .data()
        .contamascara.replace(/>a/, '*')
    );
  }

  function tipoAlterado(tipo) {
    if (tipo == 'Financeira') {
      $('#banco')
        .closest('.form-group')
        .addClass('hidden');
      var label = $('#conta')
        .closest('.form-group')
        .find('label');
      label.text(label.attr('data-servico'));
      label = $('#agencia')
        .closest('.form-group')
        .find('label');
      label.text(label.attr('data-conta'));
      $('#conta').unmask();
      $('#agencia').unmask();
    } else {
      $('#banco')
        .closest('.form-group')
        .removeClass('hidden');
      var label = $('#conta')
        .closest('.form-group')
        .find('label');
      label.text(label.attr('data-conta'));
      label = $('#agencia')
        .closest('.form-group')
        .find('label');
      label.text(label.attr('data-agencia'));
      applyMask();
    }
  }

  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  Gerenciar.banco.initField('#bancoid', function(banco) {
    $('#bancoid_ref').data('agenciamascara', banco.agenciamascara);
    $('#bancoid_ref').data('contamascara', banco.contamascara);
    applyMask();
  });
  $('#tipo').change(function() {
    tipoAlterado(
      $(this)
        .find(':selected')
        .val()
    );
  });
  tipoAlterado(
    $('#tipo')
      .find(':selected')
      .val()
  );
};
Gerenciar.forma_pagto = {};
Gerenciar.forma_pagto.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#tipo, #estado').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.forma_pagto.initForm = function(focus_ctrl) {
  function tipoAlterado(tipo) {
    if (tipo == 'Cartao' || tipo == 'Cheque') {
      $('#minparcelas')
        .closest('.form-group')
        .removeClass('hidden');
      $('#maxparcelas')
        .closest('.form-group')
        .removeClass('hidden');
      $('#parcelassemjuros')
        .closest('.form-group')
        .removeClass('hidden');
      $('#juros')
        .closest('.form-group')
        .removeClass('hidden');
    } else {
      $('#minparcelas')
        .closest('.form-group')
        .addClass('hidden');
      $('#maxparcelas')
        .closest('.form-group')
        .addClass('hidden');
      $('#parcelassemjuros')
        .closest('.form-group')
        .addClass('hidden');
      $('#juros')
        .closest('.form-group')
        .addClass('hidden');
    }
  }

  $('#minparcelas').autoNumeric('init');
  $('#maxparcelas').autoNumeric('init');
  $('#parcelassemjuros').autoNumeric('init');
  $('#juros').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  $('#tipo').change(function() {
    tipoAlterado(
      $(this)
        .find(':selected')
        .val()
    );
  });
  tipoAlterado(
    $('#tipo')
      .find(':selected')
      .val()
  );
};
Gerenciar.cartao = {};
Gerenciar.cartao.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#estado').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.cartao.initForm = function(focus_ctrl) {
  $('#mensalidade').autoNumeric('init');
  $('#transacao').autoNumeric('init');
  $('#taxa').autoNumeric('init');
  $('#diasrepasse').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  $('#imageindex').ddslick({
    imageSrc: $('#imageindex').attr('data-imagesrc'),
    width: '100%',
    height: 260,
    imgWidth: 50,
    imgHeight: 31,
    onSelected: function() {
      $('#imageindex').css(
        'margin-top',
        parseInt(
          $('#descricao')
            .closest('.form-group')
            .css('height')
        ) - parseInt($('#imageindex').css('height'))
      );
    }
  });
};
Gerenciar.cartao.initField = function(input, field) {
  Gerenciar.cartao.initFieldSelect(input, field, undefined);
};
Gerenciar.cartao.initFieldSelect = function(input, field, selectFn) {
  Gerenciar.common.autocomplete(
    '/gerenciar/cartao/',
    input,
    selectFn,
    function(data) {
      return { value: data.descricao, title: '' };
    },
    function(data) {
      return {
        tag: 'div',
        style: 'background-position: -' + data.imageindex * 50 + 'px 0px;',
        classes: 'cell-icon cartao-icons'
      };
    },
    function(search) {
      return { search: search, saida: 'json', limite: 5 };
    },
    function(response) {
      return response.cartoes;
    },
    field,
    'data-descricao'
  );
};
Gerenciar.funcao = {};
Gerenciar.funcao.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.funcao.initForm = function(focus_ctrl) {
  $('#salariobase').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.cliente = {};
Gerenciar.cliente.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#tipo').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.cliente.initField = function(input, field, tipo) {
  Gerenciar.common.autocomplete(
    '/app/cliente/procurar',
    input,
    undefined,
    function(data) {
      return {
        value: $.trim(
          data.nome +
            (data.tipo != 'Fisica' || data.sobrenome == null
              ? ''
              : ' ' + data.sobrenome)
        ),
        title: data.fone1
      };
    },
    function(data) {
      var imagemurl = data.imagemurl;
      if (imagemurl != null) {
        if (data.tipo == 'Fisica') return { url: imagemurl, classes: 'fisica' };
        return imagemurl;
      }
      if (data.tipo == 'Fisica')
        return { url: '/static/img/cliente.png', classes: 'fisica' };
      return '/static/img/empresa.png';
    },
    function(search) {
      return { busca: search, tipo: tipo, limite: 5, formatar: 1 };
    },
    function(response) {
      return response.clientes;
    },
    field,
    'data-nome'
  );
};
Gerenciar.cliente.initForm = function(focus_ctrl) {
  function tipoAlterado(tipo) {
    if (tipo == 'Fisica') {
      $('#imagem_container')
        .prev('label')
        .text($('#imagem_container').attr('data-foto'));
      $('#nome')
        .prev('label')
        .text($('#nome').attr('data-nome'));
      $('#sobrenome')
        .prev('label')
        .text($('#sobrenome').attr('data-sobrenome'));
      $('#cpf')
        .prev('label')
        .text($('#cpf').attr('data-cpf'));
      $('#cpf').mask($('#cpf').attr('mask'));
      $('#rg')
        .prev('label')
        .text($('#rg').attr('data-rg'));
      $('#dataaniversario')
        .prev('label')
        .text($('#dataaniversario').attr('data-niver'));
      $('#slogan')
        .prev('label')
        .text($('#slogan').attr('data-obs'));
      $('#im')
        .closest('.form-group')
        .addClass('hidden');
      $('#genero')
        .closest('.form-group')
        .removeClass('hidden');
    } else {
      $('#imagem_container')
        .prev('label')
        .text($('#imagem_container').attr('data-logomarca'));
      $('#nome')
        .prev('label')
        .text($('#nome').attr('data-fantasia'));
      $('#sobrenome')
        .prev('label')
        .text($('#sobrenome').attr('data-razaosocial'));
      $('#cpf')
        .prev('label')
        .text($('#cpf').attr('data-cnpj'));
      $('#cpf').mask($('#cpf').attr('mask-cnpj'));
      $('#rg')
        .prev('label')
        .text($('#rg').attr('data-ie'));
      $('#dataaniversario')
        .prev('label')
        .text($('#dataaniversario').attr('data-fund'));
      $('#slogan')
        .prev('label')
        .text($('#slogan').attr('data-slogan'));
      $('#im')
        .closest('.form-group')
        .removeClass('hidden');
      $('#genero')
        .closest('.form-group')
        .addClass('hidden');
    }
  }
  $('#tipo').change(function() {
    tipoAlterado(
      $(this)
        .find(':selected')
        .val()
    );
  });
  $.datetimepicker.setLocale('pt-BR');
  $('#dataaniversario').mask('99/99/9999');
  $('#dataaniversario').datetimepicker({
    timepicker: false,
    format: 'd/m/Y',
    enterLikeTab: false
  });
  $('#fone1').each(function() {
    $(this).mask($(this).attr('mask'));
  });
  $('#fone2').each(function() {
    $(this).mask($(this).attr('mask'));
  });
  $('#limitecompra').autoNumeric('init');
  Upload.image.initialize('#imagem_container');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  tipoAlterado(
    $('#tipo')
      .find(':selected')
      .val()
  );
  Upload.form('#cliente-form', 'clientesave');
};
Gerenciar.funcionario = {};
Gerenciar.funcionario.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#funcao, #genero, #estado').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.funcionario.initField = function(input, ativo) {
  Gerenciar.common.autocomplete(
    '/app/funcionario/',
    input,
    undefined,
    function(data) {
      return { value: data.nome, title: data.funcao };
    },
    function(data) {
      var imagemurl = data.imagemurl;
      if (imagemurl == null) imagemurl = '/static/img/cliente.png';
      return { url: imagemurl, classes: 'fisica' };
    },
    function(search) {
      return { search: search, ativo: ativo, limite: 5 };
    }
  );
};
Gerenciar.funcionario.initForm = function(focus_ctrl) {
  $('#codigobarras').autoNumeric('init');
  $('#pontuacao').autoNumeric('init');
  $('#porcentagem').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  Gerenciar.cliente.initField('#clienteid', '#clienteid_ref', 'fisica');
};
Gerenciar.moeda = {};
Gerenciar.moeda.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.moeda.initForm = function(focus_ctrl) {
  $('#divisao').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.pais = {};
Gerenciar.pais.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#moedaid').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.pais.initForm = function(focus_ctrl) {
  $('#bandeiraindex').change(function() {
    Gerenciar.pais.setBandeira($(this));
  });
  Gerenciar.pais.setBandeira($('#bandeiraindex'));
  $('#linguagemid').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.pais.setBandeira = function(field) {
  var option = field.find(':selected');
  var value = option.attr('data-bandeira');
  var div = field.prev('label').find('.cell-icon');
  div.css('background-position', -(value * 16) + 'px 0px');
};
Gerenciar.estado = {};
Gerenciar.estado.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#paisid').change(function() {
    Gerenciar.pais.setBandeira($(this));
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.pais.setBandeira($('#paisid'));
};
Gerenciar.estado.initForm = function(focus_ctrl) {
  $('#paisid').change(function() {
    Gerenciar.pais.setBandeira($(this));
  });
  Gerenciar.pais.setBandeira($('#paisid'));
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.estado.load = function(pais_id) {
  Gerenciar.common.load(
    '/app/estado/',
    { paisid: pais_id, saida: 'json' },
    '#estadoid',
    function(item) {
      return { value: item.id, title: item.nome };
    },
    null,
    function(option, item) {
      var estado_sel = $('#estadoid').attr('data-select-after');
      option.attr('data-sigla', item.uf);
      if (
        estado_sel != null &&
        removeDiacritics(item.nome) == removeDiacritics(estado_sel)
      ) {
        option.prop('selected', true);
        $('#estadoid').removeAttr('data-select-after');
      }
    }
  );
};
Gerenciar.cidade = {};
Gerenciar.cidade.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#paisid').change(function() {
    Gerenciar.pais.setBandeira($(this));
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.pais.setBandeira($('#paisid'));
  $('#estadoid').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.cidade.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) {
    $('#' + focus_ctrl).focus();
  }
  $('#cep').each(function() {
    $(this).mask($(this).attr('mask'));
  });
  $('#paisid').change(function() {
    Gerenciar.pais.setBandeira($(this));
    Gerenciar.estado.load(
      $(this)
        .find(':selected')
        .val()
    );
  });
  Gerenciar.pais.setBandeira($('#paisid'));
};
Gerenciar.cidade.autocomplete = function(
  cidade_selector,
  estado_selector,
  use_data_value
) {
  $(cidade_selector).autocomplete({
    lookup: function(search, done) {
      var estado_value = $(estado_selector).val();
      if (use_data_value)
        estado_value = $(estado_selector)
          .find(':selected')
          .attr('data-id');
      $.get(
        '/app/cidade/',
        { search: search, estadoid: estado_value },
        function(response) {
          var result = { suggestions: [] };
          if (response.status == 'ok') {
            result = {
              suggestions: $.map(response.items, function(item) {
                return { value: item.nome, data: item };
              })
            };
          }
          done(result);
        }
      );
    },
    onSelect: function(suggestion) {
      $(cidade_selector).trigger('cidadechoose', [suggestion.data]);
    }
  });
  return $(cidade_selector);
};
Gerenciar.cidade.fill = function(id, nome) {
  $('#cidade').val(id);
  $('#cidadeid').val(nome);
};
Gerenciar.bairro = {};
Gerenciar.bairro.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#paisid').change(function() {
    Gerenciar.pais.setBandeira($(this));
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.pais.setBandeira($('#paisid'));
  $('#estadoid').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.cidade
    .autocomplete('#cidadeid', '#estadoid', true)
    .on('cidadechoose', function(ev, data) {
      Gerenciar.cidade.fill(data.id, data.nome, '', '');
    });
};
Gerenciar.bairro.initForm = function(focus_ctrl) {
  $('#valorentrega').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  $('#paisid').change(function() {
    Gerenciar.pais.setBandeira($(this));
    Gerenciar.cidade.fill('', '', '', '');
    Gerenciar.estado.load($(this).val());
  });
  Gerenciar.pais.setBandeira($('#paisid'));
  $('#estadoid').change(function() {
    Gerenciar.cidade.fill('', '', '', '');
  });
  Gerenciar.cidade
    .autocomplete('#cidadeid', '#estadoid', false)
    .on('cidadechoose', function(ev, data) {
      Gerenciar.cidade.fill(data.id, data.nome, '', '');
    });
};
Gerenciar.localizacao = {};
Gerenciar.localizacao.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.localizacao.initMap = function() {
  var markers = [];

  function placeMarker(map, location, center) {
    for (var i = 0; i < markers.length; ++i) {
      markers[i].setMap(null);
    }
    markers.length = 0;
    var marker = new google.maps.Marker({
      position: location,
      map: map,
      icon: '/static/img/house.png',
      draggable: true
    });
    google.maps.event.addListener(marker, 'dragend', function(event) {
      $('#latitude').val(event.latLng.lat());
      $('#latitude').val(event.latLng.lng());
      searchLocation(map, event.latLng);
    });
    markers.push(marker);
    $('#latitude').val(location.lat);
    $('#longitude').val(location.lng);
    if (center) {
      map.setCenter(location);
      map.setZoom(16);
    }
  }
  function fillLocation(pais, cep, estado, cidade, bairro, logradouro, numero) {
    if (pais != undefined) {
      $('#paisid option').each(function() {
        if (
          (pais.length == 2 && $(this).attr('data-codigo') == pais) ||
          (pais.length == 3 && $(this).attr('data-sigla') == pais) ||
          removeDiacritics($(this).text()) == removeDiacritics(pais)
        ) {
          if (estado != undefined)
            $('#estadoid').attr('data-select-after', estado);
          $('#paisid')
            .val($(this).val())
            .trigger('change');
        }
      });
    }
    if (cep != undefined) $('#cep').val(cep);

    if (estado != undefined) {
      $('#estadoid option').each(function() {
        if (removeDiacritics($(this).text()) == removeDiacritics(estado))
          $('#estadoid').val($(this).val());
      });
    }
    if (cidade != undefined) $('#cidade').val(cidade);
    if (bairro != undefined) $('#bairro').val(bairro);
    if (logradouro != undefined) $('#logradouro').val(logradouro);
    var old_numero = $('#numero').val();
    if (numero != undefined && old_numero == '') $('#numero').val(numero);
  }

  function setLocation(map, lat, lng, pais, estado, cidade) {
    var pos = {
      lat: lat,
      lng: lng
    };
    placeMarker(map, pos, true);
    fillLocation(pais, undefined, estado, cidade);
  }
  function getLocation(map) {
    $.get('http://ipinfo.io/json', function(data) {
      var loc = data.loc.split(',');
      setLocation(
        map,
        Util.toFloat(loc[0]),
        Util.toFloat(loc[1]),
        data.country,
        data.region,
        data.city
      );
    });
  }
  function searchLocation(map, location) {
    $.get(
      'http://maps.googleapis.com/maps/api/geocode/json',
      { latlng: location.lat() + ',' + location.lng(), sensor: 'true' },
      function(data) {
        if (data.results.length == 0) return;
        var result = data.results[0];
        var pais, cep, estado, cidade, bairro, logradouro, numero;
        for (var i = 0; i < result.address_components.length; i++) {
          var component = result.address_components[i];
          switch (component.types[0]) {
          case 'street_number':
            numero = component.long_name;
            break;
          case 'route':
            logradouro = component.long_name;
            break;
          case 'political':
            bairro = component.long_name;
            break;
          case 'locality':
            cidade = component.long_name;
            break;
          case 'administrative_area_level_1':
            estado = component.long_name;
            break;
          case 'postal_code':
            if (component.types.length == 1) cep = component.long_name;
            break;
          case 'country':
            pais = component.short_name;
            break;
          }
        }
        fillLocation(pais, cep, estado, cidade, bairro, logradouro, numero);
      }
    );
  }
  var location;
  var zoom = 16;
  if ($('#latitude').val() == '' || $('#longitude').val() == '') {
    location = {
      lat: -15.628327,
      lng: -47.598267
    };
    zoom = 4;
  } else {
    location = {
      lat: Util.toFloat($('#latitude').val()),
      lng: Util.toFloat($('#longitude').val())
    };
  }
  var map = new google.maps.Map($('#google-map')[0], {
    center: location,
    zoom: zoom
  });
  google.maps.event.addListener(map, 'click', function(event) {
    var pos = {
      lat: event.latLng.lat(),
      lng: event.latLng.lng()
    };
    placeMarker(map, pos, false);
    searchLocation(map, event.latLng);
  });
  if (zoom == 16) {
    placeMarker(map, location, true);
    return;
  }
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
      function(position) {
        setLocation(map, position.coords.latitude, position.coords.longitude);
      },
      function() {
        getLocation(map);
      }
    );
  } else {
    getLocation(map);
  }
};
Gerenciar.localizacao.initForm = function(focus_ctrl) {
  function tipoAlterado(tipo) {
    if (tipo == 'Casa') {
      $('#condominio')
        .closest('.form-group')
        .addClass('hidden');
      $('#bloco')
        .closest('.form-group')
        .addClass('hidden');
      $('#apartamento')
        .closest('.form-group')
        .addClass('hidden');
    } else {
      $('#condominio')
        .closest('.form-group')
        .removeClass('hidden');
      $('#bloco')
        .closest('.form-group')
        .removeClass('hidden');
      $('#apartamento')
        .closest('.form-group')
        .removeClass('hidden');
    }
  }
  $('#cep').each(function() {
    $(this).mask($(this).attr('mask'));
  });
  Gerenciar.pais.setBandeira($('#paisid'));
  $('#tipo-loc').change(function() {
    tipoAlterado(
      $(this)
        .find(':selected')
        .val()
    );
  });
  tipoAlterado(
    $('#tipo-loc')
      .find(':selected')
      .val()
  );
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  $('#paisid').change(function() {
    Gerenciar.pais.setBandeira($(this));
    Gerenciar.estado.load(
      $(this)
        .find(':selected')
        .val()
    );
  });
  Gerenciar.cidade.autocomplete('#cidade', '#estadoid', false);
  $('#bairro').autocomplete({
    lookup: function(search, done) {
      $.get(
        '/app/bairro/',
        {
          search: search,
          estadoid: $('#estadoid').val(),
          cidade: $('#cidade').val()
        },
        function(response) {
          var result = { suggestions: [] };
          if (response.status == 'ok') {
            result = {
              suggestions: $.map(response.items, function(item) {
                return { value: item.nome, data: item };
              })
            };
          }
          done(result);
        }
      );
    }
  });
  $('#logradouro').autocomplete({
    lookup: function(search, done) {
      $.get(
        '/app/localizacao/',
        {
          typesearch: search,
          estadoid: $('#estadoid').val(),
          cidade: $('#cidade').val()
        },
        function(response) {
          var result = { suggestions: [] };
          if (response.status == 'ok') {
            result = {
              suggestions: $.map(response.items, function(item) {
                return { value: item.logradouro, data: item };
              })
            };
          }
          done(result);
        }
      );
    },
    onSelect: function(suggestion) {
      if (suggestion.data.cep != null && suggestion.data.cep.length > 0)
        $('#cep').val(suggestion.data.cep);
      $('#bairro').val(suggestion.data.bairro);
      $('#logradouro').val(suggestion.data.logradouro);
    }
  });
  $('#condominio').autocomplete({
    lookup: function(search, done) {
      $.get(
        '/app/localizacao/',
        {
          tipo: 'Apartamento',
          typesearch: search,
          estadoid: $('#estadoid').val(),
          cidade: $('#cidade').val()
        },
        function(response) {
          var result = { suggestions: [] };
          if (response.status == 'ok') {
            result = {
              suggestions: $.map(response.items, function(item) {
                return { value: item.condominio, data: item };
              })
            };
          }
          done(result);
        }
      );
    },
    onSelect: function(suggestion) {
      if (suggestion.data.cep != null && suggestion.data.cep.length > 0)
        $('#cep').val(suggestion.data.cep);
      $('#bairro').val(suggestion.data.bairro);
      $('#logradouro').val(suggestion.data.logradouro);
      $('#numero').val(suggestion.data.numero);
    }
  });
  Upload.form('#localizacao-form', 'localizacaosave');
};
Gerenciar.comanda = {};
Gerenciar.comanda.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#ativa').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.comanda.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.movimentacao = {};
Gerenciar.movimentacao.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#aberto, #caixaid').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.funcionario.initField('#funcionarioaberturaid', '');
  $('#aberto').focus();
};
Gerenciar.movimentacao.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.pedido = {};
Gerenciar.pedido.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#estado, #tipo').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.cliente.initField('#clienteid', '#clienteid_ref');
  Gerenciar.funcionario.initField('#funcionarioid', '');
  $.datetimepicker.setLocale('pt-BR');
  $('#inicio, #fim').mask('99/99/9999');
  $('#inicio, #fim').datetimepicker({
    timepicker: false,
    format: 'd/m/Y',
    enterLikeTab: false
  });
};
Gerenciar.pedido.initForm = function(focus_ctrl) {
  $('#pessoas').autoNumeric('init');
  $.datetimepicker.setLocale('pt-BR');
  $('#dataagendamento').mask('99/99/9999 99:99');
  $('#dataagendamento').datetimepicker({
    format: 'd/m/Y H:i',
    enterLikeTab: false
  });
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.categoria = {};
Gerenciar.categoria.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#categoria').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.categoria.initForm = function(focus_ctrl) {
  Upload.image.initialize('#imagem_container');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.unidade = {};
Gerenciar.unidade.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.unidade.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.setor = {};
Gerenciar.setor.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.setor.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.produto = {};
Gerenciar.produto.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#tipo, #unidade, #categoria').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.produto.initField = function(input, field, stock) {
  Gerenciar.produto.initFieldSelect(input, field, stock, undefined);
};
Gerenciar.produto.initFieldSelect = function(input, field, stock, selectFn) {
  Gerenciar.common.autocomplete(
    '/app/produto/procurar',
    input,
    selectFn,
    function(data) {
      return { value: data.descricao, title: data.categoria };
    },
    '/static/img/produto.png',
    function(search) {
      return { busca: search, estoque: stock };
    },
    function(response) {
      return response.produtos;
    },
    field,
    'data-descricao'
  );
};
Gerenciar.produto.initView = function(categorias) {
  var template = $('#product-template').html();

  var catPos = 0;
  var cache = {};
  var allLoaded = false;

  function fillProducts(data) {
    for (var i in data.produtos) {
      var item = $(template);
      $('.simbolo', item).text($('#product-list').data('symbol') + ' ');
      if (data.produtos[i].imagemurl == undefined)
        $('img', item).attr('src', '/static/img/produto.png');
      else $('img', item).attr('src', data.produtos[i].imagemurl);
      $('img', item).attr('title', data.produtos[i].descricao);
      $('.alinhamento', item).text(data.produtos[i].descricao);
      if (data.produtos[i].tipo == 'Pacote') $('.por', item).html('');
      else
        $('.por strong', item).text(
          Util.toMoney(Util.toFloat(data.produtos[i].precovenda))
        );
      $('.item', item).attr('data-produto', data.produtos[i].id);
      $('.item', item).attr('data-detalhes', data.produtos[i].detalhes);
      $('#product-list').append(item);
    }
    initSelectItem($('#product-list .item'));
  }

  function carregaProdutos(cat_id, load_only) {
    if (!load_only) $('#product-list').empty();
    if (cat_id in cache) {
      if (!load_only) fillProducts(cache[cat_id]);
      return;
    }
    if (!load_only)
      $('#product-list').append('<div class="loader">Carregando...</div>');
    $.get('/app/produto/listar', { categoria: cat_id }, function(data) {
      if (data.status != 'ok') {
        if (!load_only) {
          $('.thunder-container').message('error', data.msg);
        }
        return;
      }
      cache[cat_id] = data;
      if (!allLoaded && Object.keys(cache).length == categorias.length) {
        allLoaded = true;
      }
      if (load_only) return;
      fillProducts(data);
      $('#product-list .loader').remove();
    }).fail(function() {
      if (load_only) return;
      $('.thunder-container').message(
        'error',
        'Falha na conexão com o servidor'
      );
      $('#product-list .loader').remove();
    });
  }

  function procuraProdutos(search) {
    $('#product-list').empty();
    $('#product-list').append('<div class="loader">Carregando...</div>');
    $.get('/app/produto/procurar', { busca: search, limite: 8 }, function(
      data
    ) {
      if (data.status != 'ok') {
        $('.thunder-container').message('error', data.msg);
        return;
      }
      fillProducts(data);
      $('#product-list .loader').remove();
      $('#product-list div:first .item').addClass('selected');
    }).fail(function() {
      $('.thunder-container').message(
        'error',
        'Falha na conexão com o servidor'
      );
      $('#product-list .loader').remove();
    });
  }

  function showProductInfo(item) {
    $('#product-info .modal-title').text($('.alinhamento', item).text());
    $('#product-info .description').text($('.alinhamento', item).text());
    $('#product-info .details').text('');
    $('#product-info .details').text(item.attr('data-detalhes'));
    $('#product-info .price span').text($('.por span', item).text());
    $('#product-info .price strong').text($('.por strong', item).text());
    $('#product-info').attr('data-produto', item.attr('data-produto'));
    $('#product-info .image img').attr('src', $('img', item).attr('src'));
    $('#product-info').modal('show');
    $('#product-info').on('hidden.bs.modal', function() {
      $('#search').focus();
    });
  }

  function initSelectItem(elem) {
    elem.click(function() {
      $('#product-list .selected').removeClass('selected');
      $(this).addClass('selected');
    });
    $('button', elem).click(function() {
      showProductInfo($(this).closest('.item'));
    });
  }

  function initReloadLink(elem) {
    elem.click(function() {
      var li = $(this).closest('li');
      var cat_id = $(this).attr('data-categoria');
      $('.categoria-list .active').removeClass('active');
      li.addClass('active');
      carregaProdutos(cat_id, false);
      return false;
    });
  }
  initReloadLink($('.categoria-list a'));
  $('.categoria-nav').click(function() {
    var dir = $(this).hasClass('next') ? 1 : -1;
    var categoria;
    var li;
    if (dir == 1) {
      li = $('.categoria-list').find('li:first');
      categoria = categorias[catPos + 4];
    } else {
      li = $('.categoria-list').find('li:last');
      categoria = categorias[catPos - 1];
    }
    var li_new = li.clone();
    var isActive = li.hasClass('active');
    li.remove();
    li_new.attr('title', categoria.descricao);
    li_new.removeClass('active');
    $('a', li_new).attr('data-categoria', categoria.id);
    $('a', li_new).text(categoria.descricao);
    if (dir == 1) $('.categoria-list').append(li_new);
    else $('.categoria-list').prepend(li_new);
    initReloadLink($('a', li_new));
    catPos += dir;
    if (catPos == 0) $('.categoria-nav.prev').addClass('hide');
    else $('.categoria-nav.prev').removeClass('hide');
    if (categorias.length - catPos <= 4)
      $('.categoria-nav.next').addClass('hide');
    else $('.categoria-nav.next').removeClass('hide');
    if (isActive) {
      if (dir == 1) li = $('.categoria-list').find('li:first');
      else li = $('.categoria-list').find('li:last');
      li.addClass('active');
      carregaProdutos($('a', li).attr('data-categoria'), false);
    }
    return false;
  });
  $('#search').focus();
  var timer,
    delay = 500;
  $('#search').bind('input', function() {
    var _this = $(this);
    clearTimeout(timer);
    timer = setTimeout(function() {
      if (_this.val() == '') {
        var li = $('.categoria-list').find('li:first');
        if (!li.exists()) return;
        $('.categoria-list .active').removeClass('active');
        li.addClass('active');
        carregaProdutos(categorias[catPos].id, false);
      } else {
        $('.categoria-list .active').removeClass('active');
        procuraProdutos(_this.val());
      }
    }, delay);
  });
  $('#search').keydown(function(e) {
    switch (e.which) {
    case 38: // up
      var prod = $('#product-list .selected');
      var next;
      if (prod.exists()) next = prod.closest('.ofertas').prev();
      else next = $('#product-list div:last');
      break;
    case 40: // down
      var prod = $('#product-list .selected');
      var next;
      if (prod.exists()) next = prod.closest('.ofertas').next();
      else next = $('#product-list div:first');
      break;
    case 13: // up
      var prod = $('#product-list .selected');
      if (prod.exists()) showProductInfo(prod);
      e.preventDefault();
      return;
    default:
      return; // exit this handler for other keys
    }
    e.preventDefault(); // prevent the default action (scroll / move caret)
    if (prod.exists() && next.exists()) prod.removeClass('selected');
    if (next.exists()) {
      next = $('.item', next);
      next.addClass('selected');
      $('html, body').scrollTop(next.offset().top - next.height());
    }
  });
  initSelectItem($('#product-list .item'));
  if (categorias.length == 0) return;
  (function loopCat(i) {
    setTimeout(function() {
      carregaProdutos(categorias[i].id, true);
      if (i < categorias.length - 1) loopCat(i + 1);
    }, 3000);
  })(0);
};
Gerenciar.produto.initForm = function(focus_ctrl) {
  function tipoAlterado(tipo) {
    if (tipo == 'Produto') {
      $('#setorestoqueid')
        .closest('.form-group')
        .removeClass('hidden');
      $('#quantidadelimite')
        .closest('.form-group')
        .removeClass('hidden');
      $('#quantidademaxima')
        .closest('.form-group')
        .removeClass('hidden');
      $('#tempopreparo')
        .closest('.form-group')
        .addClass('hidden');
      $('#perecivel')
        .closest('.form-group')
        .removeClass('hidden');
      $('#setorpreparoid')
        .closest('.form-group')
        .removeClass('col-sm-6');
      $('#setorpreparoid')
        .closest('.form-group')
        .addClass('col-sm-3');
    } else if (tipo == 'Composicao') {
      $('#setorestoqueid')
        .closest('.form-group')
        .addClass('hidden');
      $('#quantidadelimite')
        .closest('.form-group')
        .addClass('hidden');
      $('#quantidademaxima')
        .closest('.form-group')
        .addClass('hidden');
      $('#tempopreparo')
        .closest('.form-group')
        .removeClass('hidden');
      $('#perecivel')
        .closest('.form-group')
        .addClass('hidden');
      $('#setorpreparoid')
        .closest('.form-group')
        .removeClass('col-sm-3');
      $('#setorpreparoid')
        .closest('.form-group')
        .addClass('col-sm-6');
    } else {
      $('#setorestoqueid')
        .closest('.form-group')
        .addClass('hidden');
      $('#quantidadelimite')
        .closest('.form-group')
        .addClass('hidden');
      $('#quantidademaxima')
        .closest('.form-group')
        .addClass('hidden');
      $('#tempopreparo')
        .closest('.form-group')
        .addClass('hidden');
      $('#perecivel')
        .closest('.form-group')
        .addClass('hidden');
      $('#setorpreparoid')
        .closest('.form-group')
        .removeClass('col-sm-3');
      $('#setorpreparoid')
        .closest('.form-group')
        .addClass('col-sm-6');
    }
  }

  $('#codigobarras').autoNumeric('init');
  $('#quantidadelimite').autoNumeric('init');
  $('#quantidademaxima').autoNumeric('init');
  $('#conteudo').autoNumeric('init');
  $('#precovenda').autoNumeric('init');
  $('#custoproducao').autoNumeric('init');
  $('#tempopreparo').autoNumeric('init');
  Upload.image.initialize('#imagem_container');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  $('#tipo').change(function() {
    tipoAlterado(
      $(this)
        .find(':selected')
        .val()
    );
  });
  tipoAlterado(
    $('#tipo')
      .find(':selected')
      .val()
  );
};
Gerenciar.produto.initDiagram = function(data) {
  var config = {
    container: '#diagrama-produto',
    connectors: {
      type: 'step'
    },
    node: {
      HTMLclass: 'diagrama-card'
    }
  };
  data.unshift(config);
  new Treant(data);
};
Gerenciar.servico = {};
Gerenciar.servico.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#tipo').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.servico.initForm = function(focus_ctrl) {
  function tipoAlterado(tipo) {
    if (tipo == 'Taxa') {
      $('#datainicio')
        .closest('.form-group')
        .addClass('hidden');
      $('#datafim')
        .closest('.form-group')
        .addClass('hidden');
    } else {
      // Evento
      $('#datainicio')
        .closest('.form-group')
        .removeClass('hidden');
      $('#datafim')
        .closest('.form-group')
        .removeClass('hidden');
    }
  }

  $.datetimepicker.setLocale('pt-BR');
  $('#datainicio').mask('99/99/9999 99:99');
  $('#datainicio').datetimepicker({
    format: 'd/m/Y H:i',
    enterLikeTab: false
  });
  $('#datafim').mask('99/99/9999 99:99');
  $('#datafim').datetimepicker({
    format: 'd/m/Y H:i',
    enterLikeTab: false
  });
  $('#valor').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  $('#tipo').change(function() {
    tipoAlterado(
      $(this)
        .find(':selected')
        .val()
    );
  });
  tipoAlterado(
    $('#tipo')
      .find(':selected')
      .val()
  );
};
Gerenciar.produto_pedido = {};
Gerenciar.produto_pedido.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#estado, #tipo, #modulo').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.funcionario.initField('#funcionarioid', '');
  $.datetimepicker.setLocale('pt-BR');
  $('#inicio, #fim').mask('99/99/9999');
  $('#inicio, #fim').datetimepicker({
    timepicker: false,
    format: 'd/m/Y',
    enterLikeTab: false
  });
  Gerenciar.produto.initField('#produtoid', '#produtoid_ref', -1);
};
Gerenciar.produto_pedido.initForm = function(focus_ctrl) {
  $('#preco').autoNumeric('init');
  $('#quantidade').autoNumeric('init');
  $('#porcentagem').autoNumeric('init');
  $('#precovenda').autoNumeric('init');
  $('#precocompra').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.cheque = {};
Gerenciar.cheque.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.cheque.initForm = function(focus_ctrl) {
  $('#parcelas').autoNumeric('init');
  $('#total').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.classificacao = {};
Gerenciar.classificacao.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#classificacaoid').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.classificacao.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.classificacao.initField = function(field, paramfn) {
  Gerenciar.common.autocomplete(
    '/gerenciar/classificacao/',
    field,
    undefined,
    function(data) {
      return { value: data.descricao, title: data.id };
    },
    undefined,
    paramfn
  );
};
Gerenciar.conta = {};
Gerenciar.conta.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#classificacaoid').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.cliente.initField('#clienteid', '#clienteid_ref');
};
Gerenciar.conta.initForm = function(focus_ctrl) {
  function tipoAlterado(tipo) {
    if (tipo == '1') {
      $('#cliente-label').text('Cliente: ');
    } else {
      $('#cliente-label').text('Fornecedor: ');
    }
  }
  $('#tipo').change(function() {
    tipoAlterado(
      $(this)
        .find(':selected')
        .val()
    );
  });
  tipoAlterado(
    $('#tipo')
      .find(':selected')
      .val()
  );
  $('#valor').autoNumeric('init');
  $('#acrescimo').autoNumeric('init');
  $('#multa').autoNumeric('init');
  $('#juros').autoNumeric('init');
  $.datetimepicker.setLocale('pt-BR');
  $('#vencimento').mask('99/99/9999');
  $('#vencimento').datetimepicker({
    format: 'd/m/Y',
    enterLikeTab: false
  });
  $('#dataemissao').mask('99/99/9999');
  $('#dataemissao').datetimepicker({
    format: 'd/m/Y',
    enterLikeTab: false
  });
  $('#datapagamento').mask('99/99/9999');
  $('#datapagamento').datetimepicker({
    format: 'd/m/Y',
    enterLikeTab: false
  });
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  Gerenciar.cliente.initField('#clienteid', '#clienteid_ref');
  Gerenciar.classificacao.initField('#classificacaoid', function(search) {
    return { search: search, superior: 'Y', saida: 'json' };
  });
  Gerenciar.classificacao.initField('#subclassificacaoid', function(search) {
    return {
      search: search,
      classificacaoid: $('#classificacaoid_ref').val(),
      saida: 'json'
    };
  });
  $('#raw_anexocaminho').change(function() {
    var input = $(this),
      label = input
        .val()
        .replace(/\\/g, '/')
        .replace(/.*\//, '');
    var text = $(this)
      .parents('.input-group')
      .find(':text');
    if (input.length) text.val(label);
  });
  $('#clear_anexocaminho').click(function() {
    var text = $(this)
      .parents('.input-group')
      .find(':text');
    $('#anexocaminho').val('');
    text.val('');
    var input = $('#raw_anexocaminho');
    input.replaceWith((input = input.clone(true)));
  });
};
Gerenciar.credito = {};
Gerenciar.credito.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#estado').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.cliente.initField('#clienteid', '#clienteid_ref');
};
Gerenciar.credito.initForm = function(focus_ctrl) {
  $('#valor').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  Gerenciar.cliente.initField('#clienteid', '#clienteid_ref');
};
Gerenciar.pagamento = {};
Gerenciar.pagamento.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#formapagtoid, #cartaoid, #carteiraid, #estado').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.funcionario.initField('#funcionarioid', '');
  $.datetimepicker.setLocale('pt-BR');
  $('#inicio, #fim').mask('99/99/9999');
  $('#inicio, #fim').datetimepicker({
    timepicker: false,
    format: 'd/m/Y',
    enterLikeTab: false
  });
};
Gerenciar.pagamento.initForm = function(focus_ctrl) {
  $('#total').autoNumeric('init');
  $('#parcelas').autoNumeric('init');
  $('#valorparcela').autoNumeric('init');
  $('#taxas').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.auditoria = {};
Gerenciar.auditoria.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#tipo, #prioridade').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.funcionario.initField('#funcionarioid', '');
};
Gerenciar.auditoria.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.folha_cheque = {};
Gerenciar.folha_cheque.init = function() {
  ajaxLink();
  $('#cliente').focus();
  $('#estado').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
  Gerenciar.banco.initField('#bancoid');
  Gerenciar.cliente.initField('#clienteid', '#clienteid_ref');
};
Gerenciar.folha_cheque.initForm = function(focus_ctrl) {
  $('#valor').autoNumeric('init');
  $('#c1').autoNumeric('init');
  $('#c2').autoNumeric('init');
  $('#c3').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.composicao = {};
Gerenciar.composicao.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.composicao.initForm = function(focus_ctrl) {
  $('#quantidade').autoNumeric('init');
  $('#valor').autoNumeric('init');
  if (focus_ctrl != undefined) {
    $('#' + focus_ctrl).focus();
  }
};
Gerenciar.composicao.initFieldSelect = function(
  input,
  field,
  produtoid,
  selectFn
) {
  Gerenciar.common.autocomplete(
    '/app/composicao/listar',
    input,
    selectFn,
    function(data) {
      return { value: data.produtodescricao, title: data.tipo };
    },
    '/static/img/produto.png',
    function(search) {
      return {
        busca: search,
        produto: produtoid,
        selecionaveis: 1,
        adicionais: 1,
        sem_opcionais: 1,
        limite: 5
      };
    },
    function(response) {
      return response.composicoes;
    },
    field,
    'data-descricao'
  );
};
Gerenciar.fornecedor = {};
Gerenciar.fornecedor.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.fornecedor.initField = function(input, field) {
  Gerenciar.common.autocomplete(
    '/app/fornecedor/',
    input,
    undefined,
    function(data) {
      return { value: data.nome, title: data.fone1 };
    },
    '/static/img/empresa.png',
    function(search) {
      return { busca: search, limite: 5, format: 1 };
    },
    undefined,
    field,
    'data-nome'
  );
};
Gerenciar.fornecedor.initForm = function(focus_ctrl) {
  $('#prazopagamento').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  Gerenciar.cliente.initField('#empresaid', '#empresaid_ref', 'juridica');
};
Gerenciar.estoque = {};
Gerenciar.estoque.init = function() {
  ajaxLink();
  $('#produto').focus();
  Gerenciar.fornecedor.initField('#fornecedorid', '#fornecedorid_ref');
  Gerenciar.produto.initField('#produtoid', '#produtoid_ref', 1);
  $('#tipo').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.estoque.initForm = function(focus_ctrl) {
  $('#quantidade').autoNumeric('init');
  $('#precocompra').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.grupo = {};
Gerenciar.grupo.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.grupo.initForm = function(focus_ctrl) {
  $('#quantidademinima').autoNumeric('init');
  $('#quantidademaxima').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.propriedade = {};
Gerenciar.propriedade.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.propriedade.initForm = function(focus_ctrl) {
  Upload.image.initialize('#imagem_container');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.pacote = {};
Gerenciar.pacote.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.pacote.initForm = function(focus_ctrl) {
  $('#quantidade').autoNumeric('init');
  $('#valor').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.pacote.initFieldSelect = function(input, field, grupoid, selectFn) {
  Gerenciar.common.autocomplete(
    '/app/pacote/listar',
    input,
    selectFn,
    function(data) {
      var title = data.produtotipo;
      if (data.propriedadeid) {
        title = 'Propriedade';
      }
      return { value: data.descricao, title: title };
    },
    '/static/img/produto.png',
    function(search) {
      var grupo_id = grupoid;
      if (isFunction(grupoid)) {
        grupo_id = grupoid(input);
      }
      return { busca: search, grupo: grupo_id, limite: 5 };
    },
    function(response) {
      return response.pacotes;
    },
    field,
    'data-descricao'
  );
};
Gerenciar.dispositivo = {};
Gerenciar.dispositivo.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.dispositivo.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.impressora = {};
Gerenciar.impressora.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.impressora.initForm = function(focus_ctrl) {
  $('#colunas').autoNumeric('init');
  $('#avanco').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.promocao = {};
Gerenciar.promocao.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.promocao.initForm = function(focus_ctrl) {
  $('#valor').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.funcionalidade = {};
Gerenciar.funcionalidade.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.funcionalidade.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.permissao = {};
Gerenciar.permissao.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.permissao.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.acesso = {};
Gerenciar.acesso.init = function() {
  ajaxLink();
  $('#search').focus();
  $('input.js-switch').click(function() {
    var input = $(this);
    $.post(
      '/gerenciar/acesso/?saida=json',
      {
        marcado: Util.checkVal(input),
        funcao: input.attr('data-funcao'),
        permissao: input.attr('data-permissao')
      },
      function(data) {
        if (data.status != 'ok') {
          $('.thunder-container').message('error', data.msg);
          return;
        }
        var row = input.closest('tr');
        if (input.is(':checked')) row.removeClass('active');
        else row.addClass('active');
      }
    ).fail(function() {
      $('.thunder-container').message(
        'error',
        'Falha na conexão com o servidor'
      );
    });
  });
};
Gerenciar.acesso.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.produto_fornecedor = {};
Gerenciar.produto_fornecedor.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.produto_fornecedor.initForm = function(focus_ctrl) {
  $('#precocompra').autoNumeric('init');
  $('#precovenda').autoNumeric('init');
  $('#quantidademinima').autoNumeric('init');
  $('#estoque').autoNumeric('init');
  $.datetimepicker.setLocale('pt-BR');
  $('#dataconsulta').mask('99/99/9999 99:99');
  $('#dataconsulta').datetimepicker({
    format: 'd/m/Y H:i',
    enterLikeTab: false
  });
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.sistema = {};
Gerenciar.sistema.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.sistema.initForm = function(focus_ctrl) {
  $('#computadores').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.sistema.initEmpresa = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  $('form').each(function() {
    $(this)
      .find('input')
      .keypress(function(e) {
        if (e.which == 10 || e.which == 13) {
          $('#cliente-form').submit();
        }
      });
  });
  $('#cliente-form').on('clientesave', function(ev, data) {
    $(this).attr('data-cliente-id', data.item.id);
    $(this).attr(
      'action',
      makeurl('/gerenciar/cliente/editar', { id: data.item.id })
    );
    $('#clienteid').val(data.item.id);
    $('#localizacao-form').submit();
  });
  $('#localizacao-form').on('localizacaosave', function(ev, data) {
    $(this).attr(
      'action',
      makeurl('/gerenciar/localizacao/editar', { id: data.item.id })
    );
  });
  $('#save-button').click(function() {
    $('#cliente-form').submit();
  });
};
Gerenciar.sistema.initFiscal = function(focus_ctrl) {
  $('#fiscal_timeout').autoNumeric('init');
  if (focus_ctrl != undefined) {
    $('#' + focus_ctrl).focus();
  }
};
Gerenciar.sistema.initAvancado = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.sistema.initLayout = function(focus_ctrl) {
  Upload.image.initialize('.image-view');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.sistema.optionChecker = function(url) {
  $('input.js-switch').click(function() {
    $.post(
      url,
      {
        marcado: Util.checkVal($(this)),
        secao: $(this).attr('data-section'),
        chave: $(this).attr('data-key')
      },
      function(data) {
        if (data.status != 'ok') {
          $('.thunder-container').message('error', data.msg);
        }
      }
    ).fail(function() {
      $('.thunder-container').message(
        'error',
        'Falha na conexão com o servidor'
      );
    });
  });
};
Gerenciar.sistema.printInit = function() {
  Gerenciar.sistema.optionChecker('/gerenciar/sistema/impressao');
};
Gerenciar.sistema.optionInit = function() {
  Gerenciar.sistema.optionChecker('/gerenciar/sistema/opcoes');
};
Gerenciar.sistema.initEmail = function(focus_ctrl) {
  $('#porta').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  $('#destinatario, #usuario').blur(function() {
    if ($('#servidor').val() != '' && $(this).is('#destinatario')) return;
    var values = $(this)
      .val()
      .split('@');
    if (values.length != 2) return;
    var host = values[1];
    var server = 'smtp.' + host;
    switch (host) {
    case 'hotmail.com':
    case 'hotmail.com.br':
    case 'outook.com':
    case 'outook.com.br':
      server = 'smtp.live.com';
      break;
    case 'gmail.com':
      server = 'smtp.gmail.com';
      break;
    case 'yahoo.com':
    case 'yahoo.com.br':
      server = 'smtp.mail.yahoo.com';
      break;
    case 'bol.com':
    case 'bol.com.br':
      server = 'smtps.bol.com.br';
      break;
    }
    $('#servidor').val(server);
  });
  $('#encriptacao').change(function() {
    var port = 25;
    switch ($(this).val()) {
    case '1':
      port = 465;
      break;
    case '2':
      port = 587;
      break;
    // default: 25
    }
    $('#porta').val(port);
  });
};
Gerenciar.informacao = {};
Gerenciar.informacao.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.informacao.initForm = function(focus_ctrl) {
  $('#porcao').autoNumeric('init');
  $('#dieta').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.resumo = {};
Gerenciar.resumo.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.resumo.initForm = function(focus_ctrl) {
  $('#valor').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.formacao = {};
Gerenciar.formacao.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.formacao.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.lista_compra = {};
Gerenciar.lista_compra.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.lista_compra.initForm = function(focus_ctrl) {
  $.datetimepicker.setLocale('pt-BR');
  $('#datacompra').mask('99/99/9999 99:99');
  $('#datacompra').datetimepicker({
    format: 'd/m/Y H:i',
    enterLikeTab: false
  });
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.modulo = {};
Gerenciar.modulo.init = function() {
  ajaxLink();
  $('#search').focus();
  $('input.js-switch').click(function() {
    var input = $(this);
    $.post(
      makeurl('/gerenciar/modulo/editar', { id: input.attr('data-id') }),
      { habilitado: Util.checkVal(input) },
      function(data) {
        if (data.status != 'ok') {
          $('.thunder-container').message('error', data.msg);
          return;
        }
        var row = input.closest('tr');
        if (input.is(':checked')) row.removeClass('active');
        else row.addClass('active');
      }
    ).fail(function() {
      $('.thunder-container').message(
        'error',
        'Falha na conexão com o servidor'
      );
    });
  });
};
Gerenciar.modulo.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.lista_produto = {};
Gerenciar.lista_produto.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.lista_produto.initForm = function(focus_ctrl) {
  $('#quantidade').autoNumeric('init');
  $('#precomaximo').autoNumeric('init');
  $('#preco').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.endereco = {};
Gerenciar.endereco.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.endereco.initForm = function(focus_ctrl) {
  $('#cep').each(function() {
    $(this).mask($(this).attr('mask'));
  });
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.horario = {};
Gerenciar.horario.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.horario.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.valor_nutricional = {};
Gerenciar.valor_nutricional.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.valor_nutricional.initForm = function(focus_ctrl) {
  $('#quantidade').autoNumeric('init');
  $('#valordiario').autoNumeric('init');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.transferencia = {};
Gerenciar.transferencia.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.transferencia.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.patrimonio = {};
Gerenciar.patrimonio.init = function() {
  ajaxLink();
  $('#search').focus();
  Gerenciar.cliente.initField('#empresaid', '#empresaid_ref', 'juridica');
  Gerenciar.fornecedor.initField('#fornecedorid', '#fornecedorid_ref');
  $('#estado').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.patrimonio.initForm = function(focus_ctrl) {
  $('#quantidade').autoNumeric('init');
  $('#altura').autoNumeric('init');
  $('#largura').autoNumeric('init');
  $('#comprimento').autoNumeric('init');
  $('#custo').autoNumeric('init');
  $('#valor').autoNumeric('init');
  Upload.image.initialize('#imagemanexada_container');
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
  Gerenciar.cliente.initField('#empresaid', '#empresaid_ref', 'juridica');
  Gerenciar.fornecedor.initField('#fornecedorid', '#fornecedorid_ref');
};
Gerenciar.pagina = {};
Gerenciar.pagina.init = function() {
  ajaxLink();
  $('#search').focus();
  $('#nome, #linguagemid').change(function() {
    $(this)
      .closest('form')
      .submit();
  });
};
Gerenciar.pagina.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.juncao = {};
Gerenciar.juncao.init = function() {
  ajaxLink();
  $('#search').focus();
};
Gerenciar.juncao.initForm = function(focus_ctrl) {
  if (focus_ctrl != undefined) $('#' + focus_ctrl).focus();
};
Gerenciar.integracao = {};
Gerenciar.integracao.init = function() {
  $('#search').focus();
  $('input.js-switch').click(function() {
    var input = $(this);
    $.post(
      '/gerenciar/integracao/opcoes',
      {
        id: input.attr('data-id'),
        ativo: Util.checkVal(input)
      },
      function(data) {
        if (data.status != 'ok') {
          $('.thunder-container').message('error', data.msg);
          return;
        }
        var row = input.closest('tr');
        if (input.is(':checked')) row.removeClass('active');
        else row.addClass('active');
      }
    ).fail(function() {
      $('.thunder-container').message(
        'error',
        'Falha na conexão com o servidor'
      );
    });
  });
};
Gerenciar.integracao.productInit = function(service) {
  var group_template = $('#group-template').html();
  var item_template = $('#item-template').html();

  $('#search').focus();
  function initRemoveItem(elem, titleFn, paramFn) {
    elem.click(function() {
      var top_item = $(this).closest('.top-row');
      if (!confirm(titleFn(top_item))) {
        return false;
      }
      $.post(
        '/gerenciar/produto/' + service + '?action=delete',
        paramFn(top_item),
        function(data) {
          if (data.status != 'ok') {
            $('.thunder-container').message('error', data.msg);
            return;
          }
          top_item.remove();
        }
      );
    });
  }
  initRemoveItem(
    $('.assoc-item .remove-item'),
    function(top_item) {
      return (
        'Deseja excluir o produto "' +
        top_item.find('.identifier').text() +
        '" da associação?'
      );
    },
    function(top_item) {
      return { codigo: top_item.find('input[type=hidden]').data('codigo') };
    }
  );
  function changeButtonState(field, btn) {
    if (
      field.val() == field.data('id') &&
      (field.data('item-count') == 0 || field.val() == '')
    ) {
      btn.addClass('disabled');
    } else {
      btn.removeClass('disabled');
    }
    if (field.val() == field.data('id') && field.data('item-count') > 0) {
      btn.find('i').addClass('fa-edit');
      btn.find('i').removeClass('fa-save');
    } else {
      btn.find('i').addClass('fa-save');
      btn.find('i').removeClass('fa-edit');
    }
  }
  function salvarCodigo(field, btn, item) {
    $.post(
      '/gerenciar/produto/' + service + '?action=update',
      { codigo: field.data('codigo'), id: field.val() },
      function(data) {
        if (data.status != 'ok') {
          $('.thunder-container').message('error', data.msg);
          return;
        }
        field.attr('data-id', field.val());
        field.data('id', field.val());
        item.removeClass('empty error incomplete');
        changeButtonState(field, btn);
        if (field.val() == '') {
          item.addClass('empty');
        } else if (field.data('item-count') > 0) {
          item.addClass('incomplete');
        }
      }
    );
  }
  function salvarPacote(group_list, field, btn, item, pacote_item) {
    $.post(
      '/gerenciar/produto/' + service + '?action=mount',
      {
        codigo: group_list.data('codigo'),
        subcodigo: field.data('codigo'),
        id: field.val()
      },
      function(data) {
        if (data.status != 'ok') {
          $('.thunder-container').message('error', data.msg);
          return;
        }
        var old_id = field.data('id');
        field.attr('data-id', field.val());
        field.data('id', field.val());
        item.removeClass('empty');
        btn.addClass('disabled');
        if (field.val() == '') {
          item.addClass('empty');
        }
        if ($.isNumeric(old_id) != $.isNumeric(field.data('id'))) {
          var saved_count = group_list.data('saved-count');
          if (field.val() == '') {
            saved_count--;
          } else {
            saved_count++;
          }
          group_list.attr('data-saved-count', saved_count);
          group_list.data('saved-count', saved_count);
          if (saved_count != group_list.data('item-count')) {
            pacote_item.addClass('incomplete');
          } else {
            pacote_item.removeClass('incomplete');
          }
        }
      }
    );
  }
  function editaPacote(field, btn, pacote_item) {
    var group_list = $('#group-list');
    group_list.empty();
    $('#edit-pkg-label').text(field.data('descricao'));
    $.get(
      '/gerenciar/produto/' + service + '?action=package',
      { codigo: field.data('codigo') },
      function(data) {
        if (data.status != 'ok') {
          $('.thunder-container').message('error', data.msg);
          return;
        }
        group_list.attr(
          'data-item-count',
          Object.keys(data.produto.itens).length
        );
        group_list.data('item-count', Object.keys(data.produto.itens).length);
        group_list.attr('data-descricao', data.produto.descricao);
        group_list.data('descricao', data.produto.descricao);
        group_list.attr('data-codigo', data.produto.codigo);
        group_list.data('codigo', data.produto.codigo);
        group_list.attr('data-tipo', data.produto.tipo);
        group_list.data('tipo', data.produto.tipo);
        group_list.attr('data-id', data.produto.id || data.produto.codigo_pdv);
        group_list.data('id', data.produto.id || data.produto.codigo_pdv);
        var grupos = {};
        $.each(data.grupos, function() {
          var group = $(group_template);
          group.text(this.descricao);
          group_list.append(group);
          var div = $('<div class="connectedSortable"/>');
          group_list.append(div);
          grupos[this.id] = div;
          div.attr('data-grupoid', this.id);
          div.data('grupoid', this.id);
          div.sortable({
            connectWith: '.connectedSortable',
            placeholder: 'connectedSortable-placeholder assoc-item',
            receive: function(event, ui) {
              ui.item.find('input[type=text]').focus();
            }
          });
        });
        var saved_count = 0;
        $.each(data.produto.itens, function() {
          var item = $(item_template);
          var group = grupos[this.grupoid];
          var input = $('input[type=text]', item);
          var field = $('input[type=hidden]', item);
          var imgdiv = item.find('img');
          var btn = $('button', item);
          $('.identifier', item).text(this.descricao);
          field.attr('data-id', this.id);
          field.data('id', this.id);
          field.attr('id', 'produto_' + this.codigo + '_ref');
          field.attr('name', 'produto[' + this.codigo + ']');
          field.attr(
            'data-descricao',
            this.associado.descricao || this.associado.nome
          );
          field.data(
            'descricao',
            this.associado.descricao || this.associado.nome
          );
          field.attr('data-codigo', this.codigo);
          field.data('codigo', this.codigo);
          field.val(this.id);
          input.attr('id', 'produto_' + this.codigo);
          input.val(this.associado.descricao || this.associado.nome);
          group.append(item);
          if (this.associado.imagem != null) {
            imgdiv.attr('src', '/static/img/produto/' + this.associado.imagem);
          } else {
            imgdiv.attr('src', '/static/img/produto.png');
          }
          item = $('.assoc-item', item);
          if (!this.id) {
            item.addClass('empty');
          } else {
            saved_count++;
          }
          btn.click(function(event) {
            event.preventDefault();
            if (btn.hasClass('disabled')) {
              return;
            }
            salvarPacote(group_list, field, btn, item, pacote_item);
          });
          input.keyup(function(e) {
            if (e.keyCode == 13) {
              btn.click();
            }
          });
          if (data.produto.tipo == 'Pacote') {
            Gerenciar.pacote.initFieldSelect(
              input[0],
              field[0],
              function(text_input) {
                return $(text_input)
                  .closest('.connectedSortable')
                  .data('grupoid');
              },
              function(data) {
                if (field.val() == field.data('id')) {
                  btn.addClass('disabled');
                } else {
                  btn.removeClass('disabled');
                }
                if (data != null && data.imagemurl != null) {
                  imgdiv.attr('src', data.imagemurl);
                } else {
                  imgdiv.attr('src', '/static/img/produto.png');
                }
              }
            );
          } else {
            Gerenciar.composicao.initFieldSelect(
              input[0],
              field[0],
              group_list.data('id'),
              function(data) {
                if (field.val() == field.data('id')) {
                  btn.addClass('disabled');
                } else {
                  btn.removeClass('disabled');
                }
                if (data != null && data.imagemurl != null) {
                  imgdiv.attr('src', data.imagemurl);
                } else {
                  imgdiv.attr('src', '/static/img/produto.png');
                }
              }
            );
          }
        });
        initRemoveItem(
          $('.assoc-item .remove-item', group_list),
          function(top_item) {
            return (
              'Deseja excluir o item "' +
              top_item.find('.identifier').text() +
              '" do pacote "' +
              group_list.data('descricao') +
              '" da associação?'
            );
          },
          function(top_item) {
            return {
              codigo: group_list.data('codigo'),
              subcodigo: top_item.find('input[type=hidden]').data('codigo')
            };
          }
        );
        group_list.attr('data-saved-count', saved_count);
        group_list.data('saved-count', saved_count);
      }
    );
    $('#edit-pkg').modal('show');
  }
  $('.assoc-input').each(function() {
    var input = $(this);
    var field = $(this)
      .closest('.assoc-info')
      .find('input[type=hidden]');
    var btn = $(this)
      .closest('div')
      .find('button');
    var item = $(this).closest('.assoc-item');
    var imgdiv = item.find('img');
    btn.click(function() {
      if (btn.hasClass('disabled')) {
        return;
      }
      if (btn.find('i').hasClass('fa-save')) {
        salvarCodigo(field, btn, item);
      } else {
        editaPacote(field, btn, item);
      }
    });
    input.keyup(function(e) {
      if (e.keyCode == 13) {
        btn.click();
      }
    });
    Gerenciar.produto.initFieldSelect(this, field[0], -1, function(data) {
      changeButtonState(field, btn);
      if (data != null && data.imagemurl != null) {
        imgdiv.attr('src', data.imagemurl);
      } else {
        imgdiv.attr('src', '/static/img/produto.png');
      }
    });
  });
};
Gerenciar.integracao.cardInit = function(service) {
  $('#search').focus();
  function salvarCodigo(field, btn, item) {
    $.post(
      '/gerenciar/cartao/' + service + '?action=update',
      { codigo: field.data('codigo'), id: field.val() },
      function(data) {
        if (data.status != 'ok') {
          $('.thunder-container').message('error', data.msg);
          return;
        }
        field.attr('data-id', field.val());
        field.data('id', field.val());
        item.removeClass('empty error incomplete');
        btn.addClass('disabled');
        if (field.val() == '') {
          item.addClass('empty');
        }
      }
    );
  }
  $('.assoc-input').each(function() {
    var input = $(this);
    var field = $(this)
      .closest('.assoc-info')
      .find('input[type=hidden]');
    var btn = $(this)
      .closest('div')
      .find('button');
    var item = $(this).closest('.assoc-item');
    var imgdiv = item.find('.cell-icon');
    btn.click(function() {
      if (btn.hasClass('disabled')) {
        return;
      }
      salvarCodigo(field, btn, item);
    });
    input.keyup(function(e) {
      if (e.keyCode == 13) {
        btn.click();
      }
    });
    Gerenciar.cartao.initFieldSelect(this, field[0], function(data) {
      if (field.val() == field.data('id')) {
        btn.addClass('disabled');
      } else {
        btn.removeClass('disabled');
      }
      if (data != null) {
        imgdiv.css(
          'background-position',
          '-' + data.imageindex * 50 + 'px 0px'
        );
      } else {
        imgdiv.css('background-position', '0px 0px');
      }
    });
  });
};
