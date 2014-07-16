// Generated by CoffeeScript 1.7.1
(function() {
  var consultarOcorrencias, generateConsultCSV, getContentExportConsult, setFilter;

  $(document).ready(function() {
    var roundNumber;
    $("#login").load("http://" + document.domain + "/siema/index.php/login/login_window");
    $("#login").hide();
    $("#map").show();
    $('#addMeModal').modal({
      keyboard: false,
      backdrop: false,
      show: true
    });
    $("#addMeModal").draggable({
      handle: ".modal-header"
    });
    $('.selectpicker').selectpicker();
    $(".dropdown-menu input, .dropdown-menu label").click(function(e) {
      return e.stopPropagation();
    });
    $(".navbar a").on("click", function(event) {
      var where;
      $(".nav-collapse a").parent().removeClass("active");
      $(this).parent().addClass("active");
      if ($(this).prop("id") === "btn-map") {
        $("#dash").hide();
        $("#login").hide();
        $("#map").show();
        $("#consultas").hide();
        $("#manag").hide();
        if (H5.Data.changed) {
          if (H5.Data.state === "Todos") {
            where = "ano='" + H5.Data.selectedYear + "'";
          } else {
            where = "estado='" + H5.Data.state + "' AND ano='" + H5.Data.selectedYear + "'";
          }
          H5.Map.layer.alerta.setOptions({
            where: where
          });
          H5.Map.layer.clusters.setOptions({
            where: where
          });
          H5.Map.layer.alerta.redraw();
          H5.Map.layer.clusters.redraw();
          H5.Data.changed = false;
        }
      } else if ($(this).prop("id") === "btn-charts") {
        $("#login").hide();
        $("#map").hide();
        $("#dash").show();
        $("#consultas").hide();
        $("#manag").hide();
      } else if ($(this).prop("id") === "btn-login") {
        $("#dash").hide();
        $("#map").show();
        $("#login").show();
        $("#consultas").hide();
      } else if ($(this).prop("id") === "btn-consult") {
        $("#login").hide();
        $("#map").hide();
        $("#dash").hide();
        $("#consultas").show();
        $("#manag").hide();
      } else if ($(this).prop("id") === "btn-manag") {
        $("#login").hide();
        $("#map").hide();
        $("#dash").hide();
        $("#manag").show();
        $("#consultas").hide();
        $("#btn-manage1").click();
      }
      return $('.nav-collapse').collapse('hide');
    });
    String.prototype.toProperCase = function() {
      return this.replace(/\w\S*/g, function(txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
      });
    };
    roundNumber = function(number, digits) {
      var multiple, rndedNum;
      multiple = Math.pow(10, digits);
      rndedNum = Math.round(number * multiple) / multiple;
      return rndedNum;
    };
    $("#dash").fadeOut(1);
    $("#consultas").hide();
    $(".loading").fadeOut(2000);
    $("#dateStart").datepicker({
      format: "dd/mm/yyyy",
      language: "pt-BR",
      autoclose: true,
      orientation: "auto right",
      clearBtn: true,
      startView: 1,
      endDate: "today"
    });
    $("#dateFinish").datepicker({
      format: "dd/mm/yyyy",
      language: "pt-BR",
      autoclose: true,
      orientation: "auto right",
      clearBtn: true,
      startView: 1,
      endDate: "today"
    });
    $("#chkAllDates").on("click", function(event) {
      if ($(this).is(":checked")) {
        $("#dateStart").attr("disabled", "disabled");
        return $("#dateFinish").attr("disabled", "disabled");
      } else {
        $("#dateStart").removeAttr("disabled", "disabled");
        return $("#dateFinish").removeAttr("disabled", "disabled");
      }
    });
    $("#dateStart").on("change", function(event) {
      return $("#chkAllDates").attr("unchecked", "unchecked");
    });
    $("#dateFinish").on("change", function(event) {
      return $("#chkAllDates").attr("unchecked", "unchecked");
    });
    return $("#consultarDados").on("click", function(event) {
      return setFilter();
    });
  });


  /*
  Descrição: Pesquisa ocorrências cadastradas conforme os parâmetros específicados. 
  Função utilizada na guia de Consulta.
  Autor: Marcos Júnior Lopes. 
  Data: 14/07/2014
   */

  consultarOcorrencias = function(tpProd, uf, origem, dtIni, dtFim) {
    var query, registroTemp, rest;
    registroTemp = new Array();
    query = "";
    if (tpProd !== "") {
      query += " tipoProd = " + tpProd;
    }
    if (uf !== "") {
      if (query.length !== 0) {
        query += " AND ";
      }
      query += "sigla='" + uf + "'";
    }
    if (origem !== "") {
      if (query.length !== 0) {
        query += " AND ";
      }
      query += " origem = '{" + origem + "}'";
    }
    if (dtIni !== "" && dtFim !== "") {
      if (query.length !== 0) {
        query += " AND ";
      }
      query += " (dt_registro >= '" + dtIni + "' AND " + "dt_registro <= '" + dtFim + "')";
    }
    H5.Data.restURL = "http://" + document.domain + "/siema/rest";
    rest = new H5.Rest({
      url: H5.Data.restURL,
      table: "vw_ocorrencia",
      fields: "to_char(dt_registro,'DD/MM/YYYY') AS dt_registro, periodo_ocorrencia, regiao, sigla, array_to_string(origem,';') AS origem, array_to_string(tipos_danos_identificados,';') AS tipos_danos_identificados, array_to_string(institiuicoes_atuando_local,';') AS institiuicoes_atuando_local, array_to_string(tipos_fontes_informacoes,';') AS tipos_fontes_informacoes",
      parameters: query
    });
    $.each(rest.data, function(index, dt) {
      return registroTemp[registroTemp.length] = new Array(dt.dt_registro, dt.periodo_ocorrencia, dt.regiao, dt.sigla, dt.origem, dt.tipos_danos_identificados, dt.institiuicoes_atuando_local, dt.tipos_fontes_informacoes);
    });
    if (registroTemp.length > 0) {
      $("#optionsExport").show();
    } else {
      $("#optionsExport").hide();
    }
    $('#resultsConsult').html('<table cellpadding="0" cellspacing="0" border="0"  id="resultTable"></table>');
    return $('#resultTable').dataTable({
      "dom": "T<'clear'>lfrtip",
      "data": registroTemp,
      "columns": [
        {
          "title": "Data de Cadastro"
        }, {
          "title": "Período"
        }, {
          "title": "Região"
        }, {
          "title": "UF"
        }, {
          "title": "Origem"
        }, {
          "title": "Danos Identificados"
        }, {
          "title": "Inst. Atuando no Local"
        }, {
          "title": "Fontes de Informação"
        }
      ],
      "oLanguage": {
        "sLengthMenu": "Mostrar _MENU_ registros por página",
        "sZeroRecords": "Nenhum registro encontrado",
        "sInfo": "Mostrando _END_ de _TOTAL_ registro(s)",
        "sInfoEmpty": "Mostrando 0 de 0 registros",
        "sInfoFiltered": "(filtrado de _MAX_ registros)",
        "sSearch": "Pesquisar: ",
        "oPaginate": {
          "sFirst": "Início",
          "sPrevious": "Anterior",
          "sNext": "Próximo",
          "sLast": "Último"
        }
      }
    });
  };

  setFilter = function() {
    var dtFim, dtIni, filterOrigem, filterTipo, filterUF;
    filterTipo = $("#tipoProd").val() === "Todos" ? "" : $("#tipoProd").val();
    filterUF = $("#dropConsultUF").val() === "Todos" ? "" : $("#dropConsultUF").val();
    filterOrigem = $("#originsConsultSlct").val() === "Todos" ? "" : $("#originsConsultSlct").val();
    if ($("#chkAllDates").is(":checked")) {
      dtIni = "";
      dtFim = "";
    } else {
      dtIni = $("#dateStart").val();
      dtFim = $("#dateFinish").val();
    }
    return consultarOcorrencias(filterTipo, filterUF, filterOrigem, dtIni, dtFim);
  };

  $("#btnExportPdf").on("click", function(event) {
    var OrigemSelect, UfSelect, arrayResults, datasPreenchidas, definationPdf, dtCadastro, qtdeReg, tpProdutoSelect;
    tpProdutoSelect = $("#tipoProd").val();
    UfSelect = $("#dropConsultUF").val();
    OrigemSelect = $("#originsConsultSlct").val();
    qtdeReg = 0;
    datasPreenchidas = $("#dateStart").val() !== "" && $("#dateFinish").val() !== "";
    if ($("#chkAllDates").is(":checked") || !datasPreenchidas) {
      dtCadastro = "Todas";
    } else {
      dtCadastro = $("#dateStart").val() + " a " + $("#dateFinish").val();
    }
    arrayResults = getContentExportConsult();
    qtdeReg = arrayResults.length - 1;
    definationPdf = {
      pageSize: "A4",
      pageOrientation: "landscape",
      footer: function(currentPage, pageCount) {
        return {
          text: "Página " + currentPage.toString() + " de " + pageCount,
          margin: [0, 10, 40, 0],
          alignment: "right"
        };
      },
      content: [
        {
          text: "Relatório Sistema SIEMA",
          bold: true,
          fontSize: 18,
          margin: [0, 0, 0, 10]
        }, {
          text: [
            "Tipo de Produto: ", {
              text: tpProdutoSelect,
              bold: true
            }
          ]
        }, {
          text: [
            "Estado(UF): ", {
              text: UfSelect,
              bold: true
            }
          ]
        }, {
          text: [
            "Origem: ", {
              text: OrigemSelect,
              bold: true
            }
          ]
        }, {
          text: [
            "Data de Cadastro: ", {
              text: dtCadastro,
              bold: true
            }
          ],
          margin: [0, 0, 0, 10]
        }, {
          text: [
            {
              text: "Total de registro(s): " + qtdeReg,
              bold: true
            }
          ],
          margin: [0, 0, 0, 10]
        }, {
          table: {
            widths: [95, 40, 40, 20, 100, 150, 150, 100],
            headerRows: 1,
            body: ""
          }
        }
      ],
      styles: {
        header: {
          bold: true
        }
      }
    };
    definationPdf.content[6].table.body = arrayResults;
    return pdfMake.createPdf(definationPdf).open();
  });

  $("#btnExportXls").on("click", function(event) {
    var csv;
    csv = generateConsultCSV();
    return window.open("data:text/csv;charset:utf-8," + escape(csv));
  });

  generateConsultCSV = (function(_this) {
    return function() {
      var col, cont, line, row, str, table, value, _i, _j, _len, _len1;
      str = "";
      line = "";
      cont = 0;
      table = getContentExportConsult();
      for (_i = 0, _len = table.length; _i < _len; _i++) {
        row = table[_i];
        line = "";
        for (_j = 0, _len1 = row.length; _j < _len1; _j++) {
          col = row[_j];
          value = cont === 0 ? col.text : col;
          line += "\"" + value + "\",";
        }
        str += line + "\r\n";
        cont++;
      }
      return str;
    };
  })(this);

  getContentExportConsult = function() {
    var arrayResults, arrayTemp;
    arrayTemp = new Array();
    arrayResults = new Array([
      {
        text: "Data de Cadastro",
        style: "header"
      }, {
        text: "Período",
        style: "header"
      }, {
        text: "Região",
        style: "header"
      }, {
        text: "UF",
        style: "header"
      }, {
        text: "Origem",
        style: "header"
      }, {
        text: "Danos Identificados",
        style: "header"
      }, {
        text: "Inst. Atuando Local",
        style: "header"
      }, {
        text: "Fonte",
        style: "header"
      }
    ]);
    $("#resultTable").DataTable().rows().data().each(function(row) {
      var col, _i, _len;
      arrayTemp = [];
      for (_i = 0, _len = row.length; _i < _len; _i++) {
        col = row[_i];
        if (col === null) {
          arrayTemp[arrayTemp.length] = "";
        } else {
          arrayTemp[arrayTemp.length] = typeof col !== "object" ? col : col.text;
        }
      }
      return arrayResults[arrayResults.length] = arrayTemp;
    });
    return arrayResults;
  };

}).call(this);
