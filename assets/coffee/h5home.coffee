
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

  # Animate load screen
  $("#dash").fadeOut(1)
  $("#consultas").hide()
  $(".loading").fadeOut(2000)

#---------------------------------------------------------------------------------#
#GUIA DE CONSULTAS:
#---------------------------------------------------------------------------------#
   
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

  # ----------------- Consulta BTN -----------------------------------------

  $("#chkAllDates").on "click", (event) ->
    if $(@).is ":checked"
      $("#dateStart").attr "disabled", "disabled"
      $("#dateFinish").attr "disabled", "disabled"
    else
      $("#dateStart").removeAttr "disabled", "disabled"
      $("#dateFinish").removeAttr "disabled", "disabled"
  
  $("#dateStart").on "change", (event) ->
    $("#chkAllDates").attr "unchecked", "unchecked"

  $("#dateFinish").on "change", (event) ->
    $("#chkAllDates").attr "unchecked", "unchecked"

  $("#consultarDados").on "click", (event) ->
     setFilter()
    
        

  $("#chkOCeano").on "click", (event) ->
    if $(@).is ":checked"
      $("#divBaciaSedimentar").show()
    else
      $("#divBaciaSedimentar").hide()

  #Esta div será mostrada somente quando o checkbox 'Oceano' estiver selecionado.
  $("#divBaciaSedimentar").hide()

  # ----------------- End Consulta BTN -------------------------------------

###
Descrição: Pesquisa ocorrências cadastradas conforme os parâmetros específicados. 
Função utilizada na guia de Consulta.
Autor: Marcos Júnior Lopes. 
Data: 14/07/2014
###
consultarOcorrencias = (tpProd,uf, origem, dtIni, dtFim,eOceano,baciaSedimentar) ->

  registroTemp = new Array();
  query = "" 

  switch tpProd
    when "0" 
      #Produtos da lista ONU.
      query += "produtos_onu <> '{}'"
    when "1" 
      #Produtos da lista outro.
      query += "produtos_outro <> '{}'"    
    else
      query = ""

  if uf isnt ""
    query += " AND " if query.length isnt 0
    query += "uf='"+uf+"'"

  if origem isnt ""
    query += " AND " if query.length isnt 0
    query += " origem = '{"+origem+"}'"

  if (dtIni isnt "" and dtFim isnt "")
    query += " AND " if query.length isnt 0
    query += " (dt_registro >= '"+dtIni+"' AND "+"dt_registro <= '"+dtFim+"')"

  if eOceano
    query += " AND " if query.length isnt 0
    query += "bacia_sedimentar is not null"

  if baciaSedimentar isnt "" and eOceano
    query += " AND " if query.length isnt 0
    query += "bacia_sedimentar='"+baciaSedimentar+"'"

  H5.Data.restURL = "http://" + document.domain + "/siema/rest"

  rest = new H5.Rest (
    url: H5.Data.restURL
    table: "vw_ocorrencia_consulta"
    fields: 
      "cast( id_ocorrencia AS text) AS id_ocorrencia,
      to_char(dt_registro,'DD/MM/YYYY') AS dt_registro,
      to_char(dt_ocorrencia,'DD/MM/YYYY') AS dt_ocorrencia,
      to_char(dt_primeira_obs,'DD/MM/YYYY') AS dt_primeira_obs, 
      municipio,
      uf,
      array_to_string(origem,'; ') AS origem,          
      array_to_string(tipo_evento,'; ') AS tipo_evento, 
      array_to_string(tipos_danos_identificados,'; ') AS tipos_danos_identificados,
      array_to_string(institiuicoes_atuando_local,'; ') AS institiuicoes_atuando_local,
      array_to_string(tipos_fontes_informacoes,'; ') AS tipos_fontes_informacoes,      
      dia_semana,
      dia_semana_primeira_obs,
      dia_semana_registro,
      periodo_ocorrencia,       
      dt_ocorrencia_feriado,
      array_to_string(produtos_onu,'; ') AS produtos_onu,
      array_to_string(produtos_outro,'; ') AS produtos_outro"      
    parameters: query    
  )

  dataIncidente = ""
  diaSemana = ""
  dataCadastrada = false
  municipioUf = "";

  #Montar array com os registros retornados pra passar para o datatable.
  $.each rest.data, (index,dt) ->

     dataIncidente = ""
     diaSemana = ""
     dataCadastrada = false
     municipioUf = "";

     #Data do Incidente.
     dataIncCadastrada = !(dt.dt_ocorrencia is null or dt.dt_ocorrencia is "")

     #Data da primeira observação.
     dataObsCadastrada = !(dt.dt_primeira_obs is null or dt.dt_primeira_obs is "")

     if dataIncCadastrada
      dataIncidente = dt.dt_ocorrencia
      diaSemana = dt.dia_semana
     else if dataObsCadastrada
      dataIncidente = dt.dt_primeira_obs
      diaSemana = dt.dia_semana_primeira_obs
     else
      dataIncidente = dt.dt_registro
      diaSemana = dt.dia_semana_registro

     municipioUf = municipioUf.concat dt.municipio, " - ", dt.uf

     registroTemp[registroTemp.length] = new Array(
        dt.id_ocorrencia        
        dataIncidente
        municipioUf               
        dt.origem
        dt.tipo_evento        
        dt.tipos_danos_identificados
        dt.institiuicoes_atuando_local
        dt.tipos_fontes_informacoes        
        diaSemana        
        dt.periodo_ocorrencia
        dt.dt_ocorrencia_feriado               
      );

  #Mostrar opções de exportação quando houver registro sendo mostrados.
  if registroTemp.length > 0
    $("#optionsExport").show();
  else
    $("#optionsExport").hide();  

  headersTable = getHeadersColumnsResults()

  $('#resultsConsult').html '<table cellpadding="0" cellspacing="0" border="0"  id="resultTable"></table>';

  $('#resultTable').dataTable(
    "dom": "T<'clear'>lfrtip"
    "data": registroTemp
    "columns": headersTable
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
  filterTipo = $("#tipoProd").val()
  filterUF =  if $("#dropConsultUF").val() is "Todos" then "" else $("#dropConsultUF").val()
  filterOrigem = if $("#originsConsultSlct").val() is "Todos" then "" else $("#originsConsultSlct").val()

  if $("#chkAllDates").is(":checked")
    dtIni = ""
    dtFim = ""
  else
    dtIni = $("#dateStart").val()
    dtFim = $("#dateFinish").val()

  eOceano = $("#chkOCeano").is(":checked");
  baciaSedimentar = if $("#baciaConsultSlct").val() is "Todos" then "" else $("#baciaConsultSlct").val()

  consultarOcorrencias(filterTipo,filterUF,filterOrigem,dtIni,dtFim,eOceano,baciaSedimentar)

$("#btnExportPdf").on "click", (event) ->
  #Get selected options for header pdf document.   
  tpProdutoSelect = $("#tipoProd :selected").text()
  UfSelect = $("#dropConsultUF").val()
  OrigemSelect = $("#originsConsultSlct").val()
  qtdeReg = 0

  datasPreenchidas = ($("#dateStart").val() isnt "" and $("#dateFinish").val() isnt "");

  if $("#chkAllDates").is(":checked") or !datasPreenchidas
    dtCadastro = "Todas"
  else
    dtCadastro = $("#dateStart").val()+" a "+$("#dateFinish").val()

  #Get content of table results.
  arrayResults = getContentExportConsult() 

  #Total records is arrayResults out headers columns of table in position 0.
  qtdeReg = arrayResults.length - 1 

  #Define content PDF.
  definationPdf = 
    pageSize: "A4"      
    pageOrientation: "landscape"      
    footer: (currentPage, pageCount) -> 
        { 
          text: "Página "+currentPage.toString()+" de "+pageCount,
          margin: [0, 10, 40, 0],
          alignment: "right"
        }
    content: 
        [
          {text: "Relatório Sistema SIEMA",bold: true, fontSize: 18, margin: [0, 0, 0, 10]}
          {text: ["Tipo de Produto: ", {text: tpProdutoSelect,bold: true}]}
          {text: ["Estado(UF): ", {text: UfSelect,bold: true}]}
          {text: ["Origem: ", {text: OrigemSelect,bold: true}]}
          {text: ["Data de Cadastro: ", {text: dtCadastro,bold: true}],  margin: [0, 0, 0, 10]}
          {text: [{text: "Total de registro(s): "+qtdeReg,bold: true}],  margin: [0, 0, 0, 10]}
          table:
            #Tamanho das colunas para o relatório. 
            #Deve ser proporcional a qtde de colunas do grid de resultados.  
            widths: [50,65,50,80,70,70,70,70,45,45,38]                      
            headerRows: 1              
            body: ""          
        ]
    styles:
      header:
        bold: true               

  #Set content for PDF.
  definationPdf.content[6].table.body = arrayResults;

  #Start view of PDF.
  pdfMake.createPdf(definationPdf).open();

$("#btnExportXls").on "click", (event) ->
  csv = generateConsultCSV()
  window.open "data:text/csv;charset:utf-8,"+escape(csv)
 
generateConsultCSV = =>

  str = ""
  line = "" 
  cont = 0

  table = getContentExportConsult()

  # get data for the rows
  for row in table
    line = ""
    for col in row
      #a primeira linha do array são os cabeçalhos da tabela estes são objetos, então buscar a propriedade text.
      value = if cont is 0 then col.text else col              
      line += "\"" + value + "\","
    
    str += line + "\r\n"

    cont++

  return str

getContentExportConsult = ->  
  
  arrayTemp = new Array()

  #Include header coluns for table results search.  
  arrayResults = new Array [  
      {text:"Número Registro", style: "header"},      
      {text:"Data", style: "header"},
      {text:"Município/UF", style: "header"},      
      {text:"Origem", style: "header"},
      {text:"Tipo Evento", style: "header"},
      {text:"Ocorrências/Ambientes Atingidos", style: "header"},
      {text:"Inst. Atuando no Local", style: "header"},
      {text:"Fonte", style: "header"},
      {text:"Dia", style: "header"},
      {text:"Período", style: "header"},
      {text:"Feriado", style: "header"}
  ]
  
  #Add records of result search, add rows for table of results. 
  $("#resultTable").DataTable().rows().data().each (row) ->     
    arrayTemp = []
    for col in row          
      if col is null       
        arrayTemp[arrayTemp.length] = ""
      else
        arrayTemp[arrayTemp.length] = if typeof(col) isnt "object" then col else col.text
      
    arrayResults[arrayResults.length] = arrayTemp

  return arrayResults  

getHeadersColumnsResults = ->
  columnsResultsConsulta = new Array(
      { "title": "Número de Registro" }      
      { "title": "Data do Incidente" }
      { "title": "Município/UF" }      
      { "title": "Origem" }
      { "title": "Tipo de Evento" }      
      { "title": "Ocorrências/Ambientes Atingidos" }
      { "title": "Inst. Atuando no Local" }
      { "title": "Fontes de Informação" }
      { "title": "Dia da Semana" } 
      { "title": "Período" }
      { "title": "Feriado" }      
    )

  return columnsResultsConsulta

#---------------------------------------------------------------------------------#
#FIM - GUIA DE CONSULTAS:
#---------------------------------------------------------------------------------#

