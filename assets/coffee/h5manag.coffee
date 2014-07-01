H5.Data.restURL = "http://" + document.domain + "/siema/rest"

H5.DB.ocorrencia = {}
H5.DB.ocorrencia.table = "ocorrencia"

# PRODES CONSOLIDADO {{{
H5.DB.ocorrencia.data =
  init: ->
    @ocorrencia = {}

  populate: (id_ocorrencia, des_ocorrencia) ->
    self = @ocorrencia
    self[id_ocorrencia] = {}
    self[id_ocorrencia].id_ocorrencia = id_ocorrencia
    self[id_ocorrencia].des_ocorrencia = des_ocorrencia

rest = new H5.Rest (
  url: H5.Data.restURL
  table: H5.DB.ocorrencia.table
  # parameters: "data_cadastro > '2013-01-01'"
  fields: "id_ocorrencia, des_ocorrencia"
)

H5.DB.ocorrencia.data.init()
for i, properties of rest.data
  H5.DB.ocorrencia.data.populate(
    properties.id_ocorrencia, properties.des_ocorrencia
  )


$("#btn_manage1").addClass("active")
$(".nav-sidebar a").on "click", (event) ->
  # clean all selection
  $(@).each ->
    $("a").parent().removeClass("active")
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
  html = '<table class="table table-striped">'
  html += '              <thead>'
  html += '                <tr>'
  html += '                  <th>ID da Ocorrência</th>'
  html += '                  <th>Descrição da Ocorrência</th>'
  html += '                  <th>Editar</th>'
  html += '                </tr>'
  html += '              </thead>'
  html += '              <tbody>'

  for key, reg of H5.DB.ocorrencia.data.ocorrencia
    html += '                <tr>'
    html += '                  <td>' + reg.id_ocorrencia + '</td>'
    html += '                  <td>' + reg.des_ocorrencia + '</td>'
    html += '                  <td>Editar</td>'
    html += '                </tr>'

  html += '              </tbody>'
  html += '            </table>'
# html += '          </div>'
  $("#table-ocorrencia").html(html)

drawTable()



  #makes mangage area invisible after loading
$("#manag").hide()
# }}}