$(document).ready ->
  $("#login").load("http://" + document.domain + "/siema/index.php/login/login_window")
  $("#login").hide()

  $("#map").show()

  #-------------------------------------------------------------------------
  # BOOTSTRAP
  #-------------------------------------------------------------------------

  $('#addMeModal').modal(
    keyboard: false
    backdrop: false
    show: true
  )

  $("#addMeModal").draggable(
    handle: ".modal-header"
  )

  $('.selectpicker').selectpicker()

  #-------------------------------------------------------------------------
  # NAVBAR
  #-------------------------------------------------------------------------

  $(".dropdown-menu input, .dropdown-menu label").click (e) ->
    e.stopPropagation()

  $(".navbar a").on "click", (event) ->
    # clean all selection
    # $(@).each ->
    $(".nav-collapse a").parent().removeClass("active")
    # mark selected option
    $(@).parent().addClass("active")

    if $(@).prop("id") is "btn-map"
      $("#dash").hide()
      $("#login").hide()
      $("#map").show()
      $("#consultas").hide()
      $("#manag").hide()

      if H5.Data.changed
        if H5.Data.state is "Todos"
          where = "ano='" + H5.Data.selectedYear + "'"
        else
          where = "estado='" + H5.Data.state + "' AND ano='" + H5.Data.selectedYear + "'"

        H5.Map.layer.alerta.setOptions({where: where})
        H5.Map.layer.clusters.setOptions({where: where})
        H5.Map.layer.alerta.redraw()
        H5.Map.layer.clusters.redraw()

        H5.Data.changed = false

    else if $(@).prop("id") is "btn-charts"
      $("#login").hide()
      $("#map").hide()
      $("#dash").show()
      $("#consultas").hide()
      $("#manag").hide()
    else if $(@).prop("id") is "btn-login"
      $("#dash").hide()
      $("#map").show()
      $("#login").show()
      $("#consultas").hide()
    else if $(@).prop("id") is "btn-consult"
      $("#login").hide()
      $("#map").hide()
      $("#dash").hide()
      $("#consultas").show()
      $("#manag").hide()
    else if $(@).prop("id") is "btn-manag"
      $("#login").hide()
      $("#map").hide()
      $("#dash").hide()
      $("#manag").show()
      $("#consultas").hide()
      $("#btn-manage1").click()

    $('.nav-collapse').collapse('hide')


  # $("#chkAllDates").on "click", (event) ->
  #   if $(@).is ":checked"
  #     $("#dateStart").attr "disabled", "disabled"
  #     $("#dateFinish").attr "disabled", "disabled"
  #   else
  #     $("#dateStart").removeAttr "disabled", "disabled"
  #     $("#dateFinish").removeAttr "disabled", "disabled"

  # $("#tipoProd").on "change", (event) ->
  #   setFilter()

  # $("#dropConsultUF").on "change", (event) ->
  #   setFilter()

  # $("#originsConsultSlct").on "change", (event) ->
  #   setFilter()

  # $("#dateFinish").on "change", (event) ->
  #   setFilter()

  # $("#chkAllDates").on "change", (event) ->
  #   if $(@).is ":checked"
  #     #Consultar.
  #     setFilter()
  #   else
  #     if $("#dateStart").value() isnt "" and $("#dateFinish").value() isnt ""
  #        #Consultar caso as datas estiverem preenchidas.
  #        setFilter()



  # ----------------- Consulta BTN -----------------------------------------

  $("#dateStart").on "change", (event) ->
    $("#chkAllDates").attr "unchecked", "unchecked"

  $("#dateFinish").on "change", (event) ->
    $("#chkAllDates").attr "unchecked", "unchecked"

  $("#consultarDados").on "click", (event) ->
    setFilter()

  # ----------------- End Consulta BTN -------------------------------------


  #-------------------------------------------------------------------------
  # MISC
  #-------------------------------------------------------------------------

  # Change the case to Letter case, ex: helmuth saatkamp to Helmuth Saatkamp
  String::toProperCase = ->
    @replace /\w\S*/g, (txt) ->
      txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()

  # Round numbers function
  roundNumber = (number, digits) ->
    multiple = Math.pow(10, digits)
    rndedNum = Math.round(number * multiple) / multiple
    rndedNum

  #Configurar DatePicker para consulta de datas.
  $("#dateStart").datepicker(
    format: "dd/mm/yyyy"
    language: "pt-BR"
    autoclose: true
    orientation: "auto right"
    clearBtn: true
    startView: 1
    endDate: "today"
  );

  $("#dateFinish").datepicker(
    format: "dd/mm/yyyy"
    language: "pt-BR"
    autoclose: true
    orientation: "auto right"
    clearBtn: true
    startView: 1
    endDate: "today"
  );

  # Animate load screen
  $("#dash").fadeOut(1)
  $("#consultas").hide()
  $(".loading").fadeOut(2000)


###
tpProd -> Tipo de produto(lista ONU)
uf -> Estado
origem -> Origem Acidente
dtIni -> Data Inicial
dfFim -> Data final
###
consultarOcorrencias = (tpProd,uf, origem, dtIni, dtFim) ->

  registroTemp = new Array();
  query = ""

  query += " tipoProd = "+tpProd if tpProd isnt ""

  if uf isnt ""
    query += " AND " if query.length isnt 0
    query += "sigla='"+uf+"'"

  if origem isnt ""
    query += " AND " if query.length isnt 0
    query += " origem = '{"+origem+"}'"

  if (dtIni isnt "" and dtFim isnt "")
    query += " AND " if query.length isnt 0
    query += " (dt_registro >= '"+dtIni+"' AND "+"dt_registro <= '"+dtFim+"')"


  H5.Data.restURL = "http://" + document.domain + "/siema/rest"

  rest = new H5.Rest (
    url: H5.Data.restURL
    table: "vw_ocorrencia"
    fields: "id_ocorrencia,to_char(dt_registro,'DD/MM/YYYY') AS dt_registro,periodo_ocorrencia, regiao, sigla, array_to_string(origem,';') AS origem, array_to_string(tipos_danos_identificados,';') AS tipos_danos_identificados, array_to_string(institiuicoes_atuando_local,';') AS institiuicoes_atuando_local, array_to_string(tipos_fontes_informacoes,';') AS tipos_fontes_informacoes"
    parameters: query

  )

  $.each rest.data, (index,dt) ->
     registroTemp[registroTemp.length] = new Array(dt.id_ocorrencia,dt.dt_registro,dt.periodo_ocorrencia, dt.regiao, dt.sigla, dt.origem, dt.tipos_danos_identificados, dt.institiuicoes_atuando_local, dt.tipos_fontes_informacoes);

  $('#resultsConsult').html '<table cellpadding="0" cellspacing="0" border="0"  id="resultTable"></table>';

  $('#resultTable').dataTable(
    dom: "T<'clear'>lfrtip"
    "data": registroTemp
    "columns": [
      { "title": "Código" }
      { "title": "Data de Cadastro" }
      { "title": "Período" }
      { "title": "Região" }
      { "title": "UF" }
      { "title": "Origem" }
      { "title": "Danos Identificados" }
      { "title": "Inst. Atuando no Local" }
      { "title": "Fontes de Informação" }
    ]
    ###
    "oTableTools":
        "sSwfPath": "http://" + document.domain + "/siema/assets/img/copy_csv_xls_pdf.swf"
        "aButtons": [
          {
            "sExtends": "xls"
            "sButtonText": "Exportar para XLS"
            "sFileName": "*.xls"
            "sFieldSeperator": ","
            "sTitle": "Consulta de ocorrências SIEMA(Sistema Nacional de Emergências Ambientais)"
          },
          {
            "sExtends": "pdf"
            "sButtonText": "Exportar para PDF"
            "sTitle": "Consulta de ocorrências SIEMA(Sistema Nacional de Emergências Ambientais)"
            "sPdfOrientation": "landscape"
          }
        ]
    ###
    "oLanguage":
      {
        "sLengthMenu": "Mostrar _MENU_ registros por página"
        "sZeroRecords": "Nenhum registro encontrado"
        "sInfo": "Mostrando _END_ de _TOTAL_ registro(s)"
        "sInfoEmpty": "Mostrando 0 de 0 registros"
        "sInfoFiltered": "(filtrado de _MAX_ registros)"
        "sSearch": "Pesquisar: "
        "oPaginate":
          {
            "sFirst": "Início"
            "sPrevious": "Anterior"
            "sNext": "Próximo"
            "sLast": "Último"
          }
      }
  )

setFilter = ->
  filterTipo = if $("#tipoProd").val() is "Todos" then "" else $("#tipoProd").val()
  filterUF =  if $("#dropConsultUF").val() is "Todos" then "" else $("#dropConsultUF").val()
  filterOrigem = if $("#originsConsultSlct").val() is "Todos" then "" else $("#originsConsultSlct").val()

  if $("#chkAllDates").is(":checked")
    dtIni = ""
    dtFim = ""
  else
    dtIni = $("#dateStart").val()
    dtFim = $("#dateFinish").val()

  consultarOcorrencias(filterTipo,filterUF,filterOrigem,dtIni,dtFim)

