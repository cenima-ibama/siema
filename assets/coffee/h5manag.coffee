H5.Data.restURL = "http://" + document.domain + "/siema/rest"

H5.DB.ocorrencia = {}
H5.DB.ocorrencia.table = "ocorrencia"

# PRODES CONSOLIDADO {{{
H5.DB.ocorrencia.data =
  init: ->
    @ocorrencia = {}

  populate: (nro_ocorrencia, des_ocorrencia, legado) ->
    self = @ocorrencia
    self[nro_ocorrencia] = {}
    self[nro_ocorrencia].nro_ocorrencia = nro_ocorrencia
    self[nro_ocorrencia].des_ocorrencia = des_ocorrencia
    self[nro_ocorrencia].legado = legado

rest = new H5.Rest (
  url: H5.Data.restURL
  table: H5.DB.ocorrencia.table
  # parameters: "data_cadastro > '2013-01-01'"
  fields: "nro_ocorrencia, des_ocorrencia, legado"
)

H5.DB.ocorrencia.data.init()
for i, properties of rest.data
  H5.DB.ocorrencia.data.populate(
    properties.nro_ocorrencia, properties.des_ocorrencia, properties.legado
  )


$("#btn_manage1").addClass("active")
$(".nav-sidebar a").on "click", (event) ->
  # clean all selection
  $(@).each ->
    $(".nav-sidebar a").parent().removeClass("active")
  # mark selected option
  $(@).parent().addClass("active")

  if $(@).prop("id") is "btn-manage1"
    $("#manage1").show()
    $("#manage2").hide()
    $("#manage3").hide()
    $("#manage4").hide()
  else if $(@).prop("id") is "btn-manage2"
    $("#manage1").hide()
    $("#manage2").show()
    $("#manage3").hide()
    $("#manage4").hide()
  else if $(@).prop("id") is "btn-manage3"
    $("#manage1").hide()
    $("#manage2").hide()
    $("#manage3").show()
    $("#manage4").hide()
  else if $(@).prop("id") is "btn-manage4"
    $("#manage1").hide()
    $("#manage2").hide()
    $("#manage3").hide()
    $("#manage4").show()


drawTable = ->
# html = '<div class="table-responsive">'
  html = ''
  html = '<table class="table table-striped">'
  html += '  <thead>'
  html += '    <tr>'
  html += '      <th>ID da Ocorrência</th>'
  html += '      <th>Descrição da Ocorrência</th>'
  html += '      <th>Editar</th>'
  html += '    </tr>'
  html += '  </thead>'
  html += '  <tbody>'

  for key, reg of H5.DB.ocorrencia.data.ocorrencia
    if not reg.legado
      html += '    <tr>'
      html += '      <td>' + reg.nro_ocorrencia + '</td>'
      html += '      <td>' + reg.des_ocorrencia + '</td>'
      html += '      <td><a data-toggle="modal" class="editOcorrencia" data-ocorrencia="' + reg.nro_ocorrencia + '"href="#editMeModal"><i class="icon-edit icon-white"></i></a></td>'
      html += '    </tr>'

  html += '  </tbody>'
  html += '</table>'
# html += '          </div>'
  $("#table-ocorrencia").html(html)

drawTable()

$('#editMeModal').modal(
  keyboard: false
  backdrop: false
  show: false
)

$("#editMeModal").draggable(
  handle: ".modal-header"
)

$("a.editOcorrencia").on "click", (event) ->
  nro_ocorrencia = $(this).attr("data-ocorrencia")
  $("#nro_ocorrencia").val(nro_ocorrencia)
  $("#formLoadEdit").submit()

  #makes mangage area invisible after loading
$("#manag").hide()
# }}}