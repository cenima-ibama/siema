google.load("visualization", "1", { packages : ["corechart"] });
google.load('visualization', '1', { packages : ['gauge'] });

// PERIODOS E VARIAVEIS {{{
var selectedState = 'Todos';
var totalPeriodos;
// definir periodos
var d = new Date();
var curYear = d.getFullYear();

totalPeriodos = d.getFullYear() - 2005;

var periodos = new Array(totalPeriodos);
for ( i = 0; i <= totalPeriodos; i++) {
  periodos[i] = (d.getFullYear() - i - 1) + '-' + (d.getFullYear() - i);
}

var months = {
  Ago : 7,
  Set : 8,
  Out : 9,
  Nov : 10,
  Dez : 11,
  Jan : 0,
  Fev : 1,
  Mar : 2,
  Abr : 3,
  Mai : 4,
  Jun : 5,
  Jul : 6
};

var estados = ["AC", "AM", "AP", "MA", "MT", "PA", "RO", "RR", "TO"];

// objeto contendo dados da tabela do banco
var tableData = {
  init : function() {
    this.states = {};
    for ( i = 0; i < estados.length; i++) {
      this.states[estados[i]] = {};
    }
  },
  populate : function(state, date, value) {
    function convertDate(dateStr) {
      dateStr = String(dateStr);
      dArr = dateStr.split("-");
      // ex input "2010-01-18"
      return new Date(dArr[0], (dArr[1]) - 1, dArr[2]);
    }

    var self = this.states[state];
    self[date] = {};
    self[date].area = value;
    self[date].date = convertDate(date);
    self[date].year = convertDate(date).getFullYear();
    self[date].month = convertDate(date).getMonth();
    self[date].day = convertDate(date).getDate();
  }
};

$.ajax({
  type: "GET",
  url: "../siscom/rest/v1/ws_geo_attributequery.php",
  data: {
    "table": "alerta_acumulado_diario",
  },
  dataType: "jsonp",
  success: function(data) {
    tableData.init();
    $.each(data, function(i, properties) {
        tableData.populate(properties.estado, properties.data, parseFloat(properties.total));
    });
  },
  error: function(error, status, desc) {
    console.log(status, desc);
  }
});

//}}}
// FUNCOES COMUNS {{{
var fullChart = 0;
var className;
// botão de maximizar
$('.btn-maximize').click(function(e){
  e.preventDefault();

  // selecionar div principal
  var $target = $(this).parent().parent().parent();
  // selecionar div do gráfico
  var $content = $target.children().next('.chart-content');
  // selecionar botão de maximizar
  var $maximize = $target.children().children().children('button.btn-maximize').children('i');
  // selecionar botão de maximizar
  var $minimize = $target.children().children().children('button.btn-minimize');
  // selecionar botão de fechar
  var $close = $target.children().children().children('button.btn-close');

  if (fullChart === 0) {
    className = $target[0].className;

    $minimize.prop('disabled',true);
    $close.prop('disabled',true);

    $maximize.prop('class', 'icon-resize-small');
    $('#navbar').hide();
    fullChart++;
  }
  else {
    $minimize.prop('disabled',false);
    $close.prop('disabled',false);

    $maximize.prop('class', 'icon-resize-full');
    $('#navbar').show();
    fullChart--;
  }

  $content.toggleClass('chart-content-overlay');

  $($content).hide();
  setTimeout(function() {
    $($content).fadeToggle(500);
  }, 200);

  $target.toggleClass(className);
  $target.toggleClass('chart-overlay');


  setTimeout(function() {
    updateCharts();
  }, 300);
});

$('.btn-minimize').click(function(e){
  e.preventDefault();
  var $target = $(this).parent().parent().next('.chart-content');
  if($target.is(':visible'))
    $('i',$(this)).removeClass('icon-chevron-up').addClass('icon-chevron-down');
  else
    $('i',$(this)).removeClass('icon-chevron-down').addClass('icon-chevron-up');
  $target.slideToggle();
});

$('.btn-close').click(function(e){
  e.preventDefault();
  $(this).parent().parent().parent().fadeOut();
});

// Detect whether device supports orientationchange event, otherwise fall back to
// the resize event.
var supportsOrientationChange = "onorientationchange" in window,
orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";

function updateCharts() {
  drawChartArea();
  drawChartAreaSum();
  drawChartBars();
  drawChartColumn();
  drawChartColumnSum();
  drawChartGauge();
  drawChartLine();
  drawChartPie();
  drawChartPieSum();
}

//}}}
// BARS - DESMATAMENTO GERAL {{{
var initBars = 0, setPeriodBars = 1, dataBars, chartBars;

var delBtn1 = document.getElementById('m1');
var addBtn1 = document.getElementById('p1');

addBtn1.onclick = function() {
  setPeriodBars++;
  drawChartBars();
}

delBtn1.onclick = function() {
  setPeriodBars--;
  drawChartBars();
}

function drawChartBars() {

  function sumValues(firstPeriod, secondPeriod) {
    var sum = 0;
    if (selectedState === 'Todos') {
      for (state in tableData.states) {
        for (reg in tableData.states[state]) {
          reg = tableData.states[state][reg];
          if (reg.date >= firstPeriod && reg.date <= secondPeriod)
            sum += reg.area;
        }
      }
    } else {
      for (reg in tableData.states[selectedState]) {
        reg = tableData.states[selectedState][reg];
        if (reg.date >= firstPeriod && reg.date <= secondPeriod)
          sum += reg.area;
      }
    }
    return Math.round(sum * 100) / 100;
  }

  // somar valores total
  function sumValuesTotal(year) {
    var firstPeriod = new Date(year - 1, 7, 1);
    var secondPeriod = new Date(year, 7, 0);

    year = year > 6 ? year++ : year;

    return sumValues(firstPeriod, secondPeriod);
  }

  // somar valores parcial
  function sumValuesAvg(year) {
    var sum = 0;
    var curMonth = new Date().getMonth();
    var curDay = new Date().getDate();

    year = year > 6 ? year++ : year;

    var firstPeriod = new Date(year - 1, 7, 1);
    var secondPeriod = new Date(year, curMonth, curDay);
    return sumValues(firstPeriod, secondPeriod);
  }

  dataBars = new google.visualization.DataTable();

  if (initBars == 0) {
    chartBars = new google.visualization.BarChart(document.getElementById('chart-bars'));
    initBars++;
  } else {
    //Popular tabela
    dataBars.addColumn('string', 'Ano');
    dataBars.addColumn('number', 'Parcial');
    dataBars.addColumn('number', 'Diferença');
    for ( i = 0; i <= setPeriodBars; i++) {
      var dataContent = [periodos[i]];

      var sumTotal = sumValuesTotal(curYear - i);
      var sumAvg = sumValuesAvg(curYear - i);

      dataContent[1] = sumAvg;
      dataContent[2] = Math.round((sumTotal - sumAvg) * 100) / 100;

      dataBars.addRow(dataContent);
    }

    var options = {
      title : '',
      titleTextStyle : {
        color : '#333',
        fontSize : 13
      },
      backgroundColor : 'transparent',
      focusTarget : 'category',
      chartArea : {
        width : '68%',
        height : '76%'
      },
      vAxis : {
        title : "Periodos"
      },
      HAxis : {
        title : 'Área Km2'
      },
      bar : {
        groupWidth : '80%'
      },
      isStacked : true,
      animation : {
        duration : 500,
        easing : 'inAndOut'
      }
    };

    // Disabling the buttons while the chart is drawing.
    addBtn1.disabled = true;
    delBtn1.disabled = true;
    google.visualization.events.addListener(chartBars, 'ready', function() {
      // Enabling only relevant buttons.
      addBtn1.disabled = (dataBars.getNumberOfRows()) > totalPeriodos;
      delBtn1.disabled = (dataBars.getNumberOfRows()) < 3;
    });

    chartBars.draw(dataBars, options);
  }
}

//}}}
// AREA - DESMATAMENTO GERAL {{{
var initAreaSum = 0, dataAreaSum, chartAreaSum;

function drawChartAreaSum() {

  // somar valores
  function sumValues(date) {
    var sum = 0;
    var firstPeriod = new Date(date - 1, 7, 1);
    var secondPeriod = new Date(date, 7, 0);
    if (selectedState === 'Todos') {
      for (state in tableData.states) {
        for (register in tableData.states[state]) {
          register = tableData.states[state][register];
          if (register.date >= firstPeriod && register.date <= secondPeriod)
            sum += register.area;
        }
      }
    } else {
      for (register in tableData.states[selectedState]) {
        register = tableData.states[selectedState][register];
        if (register.date >= firstPeriod && register.date <= secondPeriod)
          sum += register.area;
      }
    }
    return Math.round(sum * 100) / 100;
  }

  dataAreaSum = new google.visualization.DataTable();

  if (initAreaSum === 0) {
    chartAreaSum = new google.visualization.AreaChart(document.getElementById('chart-area-total'));
    initAreaSum++;
  } else {
    //Popular tabela
    dataAreaSum.addColumn('string', 'Ano');
    dataAreaSum.addColumn('number', 'Total');
    for ( i = totalPeriodos; i >= 0; i--) {
      var dataContent = [periodos[i]];
      dataContent[1] = sumValues(curYear - i);
      dataAreaSum.addRow(dataContent);
    }

    var options = {
      title : '',
      titleTextStyle : {
        color : '#333',
        fontSize : 13
      },
      backgroundColor : 'transparent',
      legend : 'none',
      focusTarget : 'category',
      chartArea : {
        width : '68%',
        height : '76%'
      },
      hAxis : {
        title : "Periodos"
      },
      vAxis : {
        title : 'Área Km2'
      },
      animation : {
        duration : 500,
        easing : 'inAndOut'
      }
    };

    chartAreaSum.draw(dataAreaSum, options);
  }
}

//}}}
// AREA - DESMATAMENTO TOTAL {{{
var setPeriodArea = 1, initArea = 0, chartArea, dataArea;

var addBtn2 = document.getElementById('p2');
var delBtn2 = document.getElementById('m2');

addBtn2.onclick = function() {
  setPeriodArea++;
  drawChartArea();
}

delBtn2.onclick = function() {
  setPeriodArea--;
  drawChartArea();
}

function drawChartArea() {

  // somar valores
  function sumValues(date, month) {
    var sum = 0;
    var firstPeriod = new Date(date - 1, 7, 1);
    var secondPeriod = new Date(date, 7, 0);
    if (selectedState === 'Todos') {
      for (state in tableData.states) {
        for (register in tableData.states[state]) {
          register = tableData.states[state][register];
          if (register.date >= firstPeriod && register.date <= secondPeriod && register.month == month) {
            sum += register.area;
          }
        }
      }
    } else {
      for (register in tableData.states[selectedState]) {
        register = tableData.states[selectedState][register];
        if (register.date >= firstPeriod && register.date <= secondPeriod && register.month == month)
          sum += register.area;
      }
    }
    return Math.round(sum * 100) / 100;
  }

  dataArea = new google.visualization.DataTable();

  //carregar dados no array
  if (initArea == 0) {
    chartArea = new google.visualization.AreaChart(document.getElementById('chart-area'));
    initArea++;
  } else {
    //inicializar tabela
    dataArea.addColumn('string', 'mes');
    for (var i = 0; i <= setPeriodArea; i++) {
      dataArea.addColumn('number', periodos[i]);
    }

    //popular tabela
    //listar meses
    for (month in months) {
      var dataContent = [month];
      for ( j = 1; j < dataArea.getNumberOfColumns(); j++) {
        dataContent[j] = sumValues(curYear - j + 1, months[month]);
      }
      dataArea.addRow(dataContent);
    }

    var options = {
      title : '',
      titleTextStyle : {
        color : '#333',
        fontSize : 13
      },
      backgroundColor : 'transparent',
      focusTarget : 'category',
      chartArea : {
        width : '70%',
        height : '80%'
      },
      vAxis : {
        title : 'Área Km2'
      },
      animation : {
        duration : 500,
        easing : 'inAndOut'
      }
    };

    // Disabling the buttons while the chart is drawing.
    addBtn2.disabled = true;
    delBtn2.disabled = true;
    google.visualization.events.addListener(chartArea, 'ready', function() {
      // Enabling only relevant buttons.
      addBtn2.disabled = (dataArea.getNumberOfColumns() - 1) > totalPeriodos;
      delBtn2.disabled = (dataArea.getNumberOfColumns() - 1) < 2;
    });
    chartArea.draw(dataArea, options);
  }
}

//}}}
// COLUNA - DESMATAMENTO GERAL POR ESTADO {{{
var initFullColumn = 0, dataFullColumn, chartFullColumn;

function drawChartColumnSum() {

  dataFullColumn = new google.visualization.DataTable();

  if (initFullColumn == 0) {
    chartFullColumn = new google.visualization.ColumnChart(document.getElementById('desmatamento_estado_total'));
    initFullColumn++;
  } else {
    //inicializar tabela
    dataFullColumn.addColumn('string', 'Estado');
    dataFullColumn.addColumn('number', 'Área Total');

    // somar valores
    function sumValues(state, date) {
      var sum = 0;
      for (register in tableData.states[state]) {
        register = tableData.states[state][register];
        sum += register.area;
      }
      return Math.round(sum * 100) / 100;
    }

    //popular tabela
    for (var i = 0; i < estados.length; i++) {
      var estado = estados[i];
      var dataContent = [estado];
      dataContent[1] = sumValues(estados[i], curYear);
      dataFullColumn.addRow(dataContent);
    }

    var options = {
      title : '',
      titleTextStyle : {
        color : '#333',
        fontSize : 13
      },
      backgroundColor : 'transparent',
      legend : 'none',
      focusTarget : 'category',
      chartArea : {
        width : '68%',
        height : '76%'
      },
      bar : {
        groupWidth : '100%'
      },
      vAxis : {
        title : 'Área Km2'
      }
    };

    chartFullColumn.draw(dataFullColumn, options);
  }
}

//}}}
// COLUNA - DESMATAMENTO TOTAL POR ESTADO {{{
var setPeriodColumn = 1, initColumn = 0, chartColumn, dataColumn;

var addBtn4 = document.getElementById('p4');
var delBtn4 = document.getElementById('m4');

addBtn4.onclick = function() {
  setPeriodColumn++;
  drawChartColumn();
}

delBtn4.onclick = function() {
  setPeriodColumn--;
  drawChartColumn();
}

function drawChartColumn() {

  dataColumn = new google.visualization.DataTable();

  if (initColumn == 0) {
    chartColumn = new google.visualization.ColumnChart(document.getElementById('desmatamento_estado'));
    //altera contador inicial
    initColumn++;
  } else {
    //inicializar tabela
    dataColumn.addColumn('string', 'mes');
    for (var i = 0; i <= setPeriodColumn; i++)
      dataColumn.addColumn('number', periodos[i]);

    // somar valores
    function sumValues(state, date) {
      var sum = 0;
      var firstPeriod = new Date(date - 1, 7, 1);
      var secondPeriod = new Date(date, 7, 0);
      for (register in tableData.states[state]) {
        register = tableData.states[state][register];
        if (register.date >= firstPeriod && register.date <= secondPeriod)
          sum += register.area;
      }
      return Math.round(sum * 100) / 100;
    }

    //popular tabela
    for (var i = 0; i < estados.length; i++) {
      var estado = estados[i];
      var dataContent = [estado];
      for (var j = 1; j < dataColumn.getNumberOfColumns(); j++)
        dataContent[j] = sumValues(estados[i], curYear - j + 1);
      dataColumn.addRow(dataContent);
    }

    var options = {
      title : '',
      titleTextStyle : {
        color : '#333',
        fontSize : 13
      },
      backgroundColor : 'transparent',
      focusTarget : 'category',
      chartArea : {
        width : '70%',
        height : '76%'
      },
      bar : {
        groupWidth : '100%'
      },
      vAxis : {
        title : 'Área Km2'
      },
      animation : {
        duration : 500,
        easing : 'inAndOut'
      }
    };

    // Disabling the buttons while the chart is drawing.
    addBtn4.disabled = true;
    delBtn4.disabled = true;
    google.visualization.events.addListener(chartColumn, 'ready', function() {
      // Enabling only relevant buttons.
      addBtn4.disabled = (dataColumn.getNumberOfColumns() - 1) > totalPeriodos;
      delBtn4.disabled = (dataColumn.getNumberOfColumns() - 1) < 2;
    });
    chartColumn.draw(dataColumn, options);
  }
}

//}}}
// PIE - DESMATAMENTO GERAL POR ESTADO {{{
var initFullPie = 0, dataFullPie, chartFullPie;

function drawChartPieSum() {

  if (initFullPie == 0) {
    chartFullPie = new google.visualization.PieChart(document.getElementById('chart-pie-all'));
    initFullPie++;
  } else {

    dataFullPie = new google.visualization.DataTable();

    //inicializar tabela
    dataFullPie.addColumn('string', 'Estado');
    dataFullPie.addColumn('number', 'Área Total');

    // somar valores
    function sumValues(state, date) {
      var sum = 0;
      for (register in tableData.states[state]) {
        register = tableData.states[state][register];
        sum += register.area;
      }
      return Math.round(sum * 100) / 100;
    }

    //popular tabela
    for (var i = 0; i < estados.length; i++) {
      var estado = estados[i];
      var dataContent = [estado];
      dataContent[1] = sumValues(estados[i], curYear);
      dataFullPie.addRow(dataContent);
    }

    var options = {
      title : '',
      titleTextStyle : {
        color : '#333',
        fontSize : 13
      },
      chartArea : {
        width : '90%',
        height : '80%'
      },
      backgroundColor : 'transparent'
    };

    chartFullPie.draw(dataFullPie, options);
  }
}

//}}}
// PIE - DESMATAMENTO TOTAL POR ESTADO {{{
var setPeriodPie = 0, initPie = 0, chartPie, dataPie;

var addBtn6 = document.getElementById('p6');
var delBtn6 = document.getElementById('m6');

addBtn6.onclick = function() {
  setPeriodPie++;
  drawChartPie();
}

delBtn6.onclick = function() {
  setPeriodPie--;
  drawChartPie();
}

function drawChartPie() {

  if (initPie === 0) {
    chartPie = new google.visualization.PieChart(document.getElementById('chart-pie'));
    initPie++;
  } else {

    dataPie = new google.visualization.DataTable();

    // somar valores
    function sumValues(state, date) {
      var sum = 0;
      var firstPeriod = new Date(date - 1, 7, 1);
      var secondPeriod = new Date(date, 7, 0);
      for (register in tableData.states[state]) {
        register = tableData.states[state][register];
        if (register.date >= firstPeriod && register.date <= secondPeriod) {
          sum += register.area;
        }
      }
      return Math.round(sum * 100) / 100;
    }

    //inicializar tabela
    dataPie.addColumn('string', 'Estado');
    dataPie.addColumn('number', periodos[totalPeriodos]);

    //popular tabela
    for (var i = 0; i < estados.length; i++) {
      var estado = estados[i];
      var dataContent = [estado];
      dataContent[1] = sumValues(estados[i], curYear - setPeriodPie);
      dataPie.addRow(dataContent);
    }

    var options = {
      title : periodos[setPeriodPie],
      titleTextStyle : {
        color : '#333',
        fontSize : 13
      },
      chartArea : {
        width : '90%',
        height : '80%'
      },
      backgroundColor : 'transparent'
    };

    // Disabling the buttons while the chart is drawing.
    addBtn6.disabled = true;
    delBtn6.disabled = true;
    google.visualization.events.addListener(chartPie, 'ready', function() {
      // Enabling only relevant buttons.
      addBtn6.disabled = setPeriodPie >= totalPeriodos;
      delBtn6.disabled = setPeriodPie < 1;
    });
    chartPie.draw(dataPie, options);
  }
}

//}}}
// PIE - QUALIFICACAO DOS POLIGONOS {{{
function drawChartPieQualify() {

  var chart = new google.visualization.PieChart(document.getElementById('chart-pie-qualify'));

  var data = google.visualization.arrayToDataTable([['Qualificação', 'Valor'], ['Corte raso', 73], ['Degradação Florestal por exploração florestal', 3], ['Degradação Florestal por uso de fogo', 9], ['Falso positivo (floresta não desmatada)', 13], ['Falso positivo (vegetação não florestal)', 2]]);

  var options = {
    title : '',
    titleTextStyle : {
      color : '#333',
      fontSize : 15
    },
    backgroundColor : 'transparent',
    chartArea : {
      width : '90%',
      height : '80%'
    }
  };

  chart.draw(data, options);
}

//}}}
// LINHA - DESMATAMENTO TOTAL POR ESTADO/ANO {{{
var setYearLine = new Date().getFullYear(), setMonLine = new Date().getMonth(), initLine = 0, chartLine, dataLine;

var form7year = document.form7.year;
var form7month = document.form7.month;

function updateLineValues() {
  setYearLine = new Date(form7year.value, form7month.value).getFullYear();
  setMonLine = new Date(form7year.value, form7month.value).getMonth();
  drawChartLine();
  drawChartGauge()
}

function drawChartLine() {

  dataLine = new google.visualization.DataTable();

  if (initLine === 0) {
    chartLine = new google.visualization.LineChart(document.getElementById('chart-line'));
    initLine++;
  } else {
    //inicializar tabela
    dataLine.addColumn('number', 'Dia');
    dataLine.addColumn('number', 'Área');

    var monthDays = new Date(setYearLine, setMonLine + 1, 0).getDate();
    var dateStart = new Date(setYearLine, setMonLine, 1);
    var dateEnd = new Date(setYearLine, setMonLine, monthDays);

    //criar e popular tabela
    var dataContent = new Array();
    var dayValue = 0;
    for (var i = 1; i <= monthDays; i++) {
      dataContent[0] = i;
      dataContent[1] = 0;
      dataLine.addRow(dataContent);
    }

    //popular tabela com dados da soma dos estado definido o periodo
      function createTable(state) {
        var dayValue = 0;
        for (var k = 1; k <= monthDays; k++) {
          for (reg in tableData.states[state]) {
            reg = tableData.states[state][reg];
            if (reg.date >= dateStart && reg.date <= dateEnd && reg.day === k) {
              dayValue += reg.area;
              break;
            }
          }
          dataLine.setValue((k - 1), 1, Math.round((dataLine.getValue((k - 1), 1) + dayValue) * 100) / 100);
        }
      }

      if (selectedState === 'Todos')
        for (state in tableData.states)createTable(state);
      else
        createTable(selectedState);

      var options = {
        title : '',
        titleTextStyle : {
          color : '#333',
          fontSize : 13
        },
        backgroundColor : 'transparent',
        legend : 'none',
        chartArea : {
          width : '70%',
          height : '70%'
        },
        vAxis : {
          title : 'Área Km2'
        },
        hAxis : {
          title : "Dias",
          gridlines : {
            color : '#CCC',
            count : monthDays / 5
          }
        },
        animation : {
          duration : 500,
          easing : 'inAndOut'
        }
      };

      chartLine.draw(dataLine, options);
  }
}

//}}}
// GAUGE - TAXAS INDICATIVAS {{{

var initGauge = 0, dataGaugeYear, chartGaugeYear, dataGaugeMonth, chartGaugeMonth, dataGaugePeriod, chartGaugePeriod;

function drawChartGauge() {

  // retornar taxa de desmatamento do periodo determinado
    function periodDeforestationRate(month, year, type) {
      var curDate, preDate, curValue = 0, preValue = 0;
      year = year > 6 ? year++ : year;

      function sumValues(date) {
        var sum = 0;
        if (selectedState === 'Todos') {
          for (state in tableData.states) {
            for (reg in tableData.states[state]) {
              reg = tableData.states[state][reg];
              if (reg.year >= date.getFullYear() && reg.year <= date.getFullYear() && reg.month === date.getMonth())
                sum += reg.area;
            }
          }
        } else {
          for (reg in tableData.states[selectedState]) {
            reg = tableData.states[selectedState][reg];
            if (reg.year >= date.getFullYear() && reg.year <= date.getFullYear() && reg.month === date.getMonth())
              sum += reg.area;
          }
        }
        return sum;
      }

      // definir periodo atual
      curDate = new Date(year, month);

      // definir periodo anterior
      if (type === 'year')
        preDate = new Date(year - 1, month);
      else if (type === 'month')
        preDate = new Date(year, month - 1);

      // definir valores referentes ao periodo atual
      curValue = sumValues(curDate);
      preValue = sumValues(preDate);

      // caso o valor do periodo anterior seja 0, retorna 0
      // para evitar uma divisão por 0
      if (preValue == 0)
        return 0;
      else
        return Math.round((curValue - preValue) / preValue * 100);
    }

    function periodDeforestationAvgRate(month, year) {
      var curValue = 0, preValue = 0;
      year = year > 6 ? year++ : year;

      // somar valores
      function sumValues(fp, sp) {
        var sum = 0;
        if (selectedState === 'Todos') {
          for (state in tableData.states) {
            for (reg in tableData.states[state]) {
              reg = tableData.states[state][reg];
              if (reg.date >= fp && reg.date <= sp)
                sum += reg.area;
            }
          }
        } else {
          for (reg in tableData.states[selectedState]) {
            reg = tableData.states[selectedState][reg];
            if (reg.date >= fp && reg.date <= sp)
              sum += reg.area;
          }
        }
        return sum;
      }

      var prePeriod = new Date(year - 1, 7, 1);
      var curPeriod = new Date(year, month + 1, 0);
      curValue = sumValues(prePeriod, curPeriod);

      var prePeriod = new Date(year - 2, 7, 1);
      var curPeriod = new Date(year - 1, month + 1, 0);
      preValue = sumValues(prePeriod, curPeriod);

      // caso o valor do periodo anterior seja 0, retorna 0
      // para evitar uma divisão por 0
      if (preValue == 0)
        return 0;
      else
        return Math.round((curValue - preValue) / preValue * 100);
    }

    var rateMonth = periodDeforestationRate(setMonLine, setYearLine, 'month');
    var rateYear = periodDeforestationRate(setMonLine, setYearLine, 'year');
    var rateAvg = periodDeforestationAvgRate(setMonLine, setYearLine);

    if (initGauge === 0) {

      chartGaugeYear = new google.visualization.Gauge(document.getElementById('chart-gauge-year'));
      chartGaugeMonth = new google.visualization.Gauge(document.getElementById('chart-gauge-month'));
      chartGaugePeriod = new google.visualization.Gauge(document.getElementById('chart-gauge-period'));

      dataGaugeYear = google.visualization.arrayToDataTable([['Label', 'Value'], ['TVAA', rateYear]]);
      dataGaugeMonth = google.visualization.arrayToDataTable([['Label', 'Value'], ['TVMA', rateMonth]]);
      dataGaugePeriod = google.visualization.arrayToDataTable([['Label', 'Value'], ['TVPA', rateAvg]]);

      initGauge++;

    } else {

      dataGaugeYear.setValue(0, 1, rateYear);
      dataGaugeMonth.setValue(0, 1, rateMonth);
      dataGaugePeriod.setValue(0, 1, rateAvg);

      var options = {
        min : -100,
        max : 100,
        height : ($('.chart-content').height()),
        width : ($('.chart-content').width() / 3),
        greenFrom : -100,
        greenTo : 0,
        redFrom : 50,
        redTo : 100,
        yellowFrom : 0,
        yellowTo : 50,
        minorTicks : 5,
        animation : {
          duration : 700,
          easing : 'inAndOut'
        }
      };

      chartGaugeYear.draw(dataGaugeYear, options);
      chartGaugeMonth.draw(dataGaugeMonth, options);
      chartGaugePeriod.draw(dataGaugePeriod, options);
    }
}

//}}}
// CALLBACK {{{
google.setOnLoadCallback(drawChartArea);
google.setOnLoadCallback(drawChartAreaSum);
google.setOnLoadCallback(drawChartBars);
google.setOnLoadCallback(drawChartColumn);
google.setOnLoadCallback(drawChartColumnSum);
google.setOnLoadCallback(drawChartGauge);
google.setOnLoadCallback(drawChartLine);
google.setOnLoadCallback(drawChartPie);
google.setOnLoadCallback(drawChartPieQualify);
google.setOnLoadCallback(drawChartPieSum);
//}}}
