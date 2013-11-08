H5.Data.restURL = "http://" + document.domain + "/siema/rest"
$(document).ready ->

  _tipoLocalizacao = null
  _tipoEvento = null
  _tipoDanoIdentificado  = null
  _tipoInstituicaoAtuando = null
  _tipoFonteInformacao = null
  _tipoProduto = null

  idOcorrencia = null


  # Get the product name from the database, by ajax
  rest = new H5.Rest (
    url: H5.Data.restURL
    fields: "nextval('tmp_pol_id_tmp_pol_seq') as lastval"
    table: "tipo_fonte_informacao"
    limit: "1"
  )
  idPol = rest.data[0].lastval


  rest = new H5.Rest (
    url: H5.Data.restURL
    fields: "nextval('tmp_lin_id_tmp_lin_seq') as lastval"
    table: "tipo_fonte_informacao"
    limit: "1"
  )
  idLin = rest.data[0].lastval

  if !$("#comunicado").val()
    date = new Date()

    seconds = parseInt(date.getSeconds() + (date.getHours() * 60 * 60), 10)
    nroComunicado = "" + parseInt(date.getFullYear(),10) + parseInt(date.getMonth() + 1,10) + parseInt(date.getDate(),10) + seconds

    $("#comunicado").val(nroComunicado)
    $("#nroComunicado").html(nroComunicado)

  # Get the data from the database
  rest = new H5.Rest (
    url: H5.Data.restURL
    table: "ocorrencia"
    fields: "id_ocorrencia"
    parameters: "nro_ocorrencia%3D'" + $("#comunicado").prop('value') + "'"
  )

  $.each rest.data, (e,prop)->
    $.each prop, (nameField, nameValue)->
      idOcorrencia = nameValue

  # Get the product name from the database, by ajax
  rest = new H5.Rest (
    url: H5.Data.restURL
    table: "produto"
    fields: "id_produto,nome,num_onu,classe_risco"
    order: "nome"
  )

  nroOcorrencia = $("#comunicado").val()

  _tipoProduto = rest.data

  #-------------------------------------------------------------------------
  # COLAPSE BOOTSTRAP
  #-------------------------------------------------------------------------

  $(".accordion-body").on "shown", ->
    stop = $(this).offset().top - 55
    delay = 300
    $("body, html").animate
      scrollTop: stop
    , delay
    return false

  #-------------------------------------------------------------------------
  # MINIMAP
  #-------------------------------------------------------------------------

  # add position to the map
  bingKey = "AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h"

  binghybrid = new L.BingLayer(bingKey,
    type: "AerialWithLabels"
    attribution: ""
    )
  # update size of the map container
  $( '#minimap' ).css("height", "371px")
  $( '#minimap' ).css("width", "100%")
  $( '#minimap' ).css("box-shadow", "0 0 0 1px rgba(0, 0, 0, 0.15)")
  $( '#minimap' ).css("border-radius", "4px")

  #-------------------------------------------------------------------------
  # MARKER CREATION
  #-------------------------------------------------------------------------

  Marker = new L.Marker([0 ,0], {draggable:true})

  minimapView = new L.Map("minimap",
    center: new L.LatLng(-10.0, -50.0)
    zoom: 3
    layers: [binghybrid]
    zoomControl: true
    )

  isLoadForm = $(window.top.document.getElementById("optionsAtualizarAcidente")).is(":checked")
  shapeLoadedFromDB = $("#shapeLoaded").prop "checked"

  drawAPI = new H5.Draw(
    map: minimapView
    url: H5.Data.restURL
    uniquePoint: true
    reloadShape: shapeLoadedFromDB
    # srid: $("#inputEPSG").val()
    srid: '4674'
    buttons:
      marker: true
      polyline: true
      polygon: true
      rectangle: true
      circle: true
      edit: false
      remove: true
    tables:
      marker:
        table: "tmp_pon"
        fields: ["id_tmp_pon","descricao","shape","nro_ocorrencia"]
        uniqueField: "id_tmp_pon"
        defaultValues:
          nro_ocorrencia: nroOcorrencia
      polyline:
        table: "tmp_lin"
        fields: ["id_tmp_lin","descricao","shape","nro_ocorrencia"]
        uniqueField: "id_tmp_lin"
        defaultValues:
          nro_ocorrencia: nroOcorrencia
      polygon:
        table: "tmp_pol"
        fields: ["id_tmp_pol","descricao","shape","nro_ocorrencia"]
        uniqueField: "id_tmp_pol"
        defaultValues:
          nro_ocorrencia: nroOcorrencia
  )

  if isLoadForm and !shapeLoadedFromDB

    pointTable = {
      fields: ['id_ocorrencia_pon as id_tmp_pon','descricao','shape',nroOcorrencia + ' as nro_ocorrencia']
      name: 'ocorrencia_pon'
      parameters:
        field: 'id_ocorrencia'
        value: idOcorrencia
      }
    polygonTable = {
      fields: ['id_ocorrencia_pol as id_tmp_pol','descricao','shape',nroOcorrencia + ' as nro_ocorrencia']
      name: 'ocorrencia_pol'
      parameters:
        field: 'id_ocorrencia'
        value: idOcorrencia
      }
    lineTable = {
      fields: ['id_ocorrencia_lin as id_tmp_lin','descricao','shape',nroOcorrencia + ' as nro_ocorrencia']
      name: 'ocorrencia_lin'
      parameters:
        field: 'id_ocorrencia'
        value: idOcorrencia
      }

    drawAPI.editShapes(pointTable, polygonTable,lineTable)


  # Add draw functionality to a map
  # drawnItems = new L.FeatureGroup()
  # minimapView.addLayer(drawnItems)

  # drawControl = new L.Control.Draw({
  #     draw: {
  #       marker: false
  #     },
  #     edit: {
  #       featureGroup: drawnItems,
  #       edit: false
  #     }
  # })
  # minimapView.addControl(drawControl)

  # minimapView.on 'draw:created', (e)->
  #   type = e.layerType

  #   layer = e.layer

  #   if (type is 'polygon')
  #     # Saves a polygon
  #     firstPoint = ""

  #     layer._leaflet_id = ++idPol

  #     sql = "(nro_ocorrencia, shape) values ( " + nroOcorrencia + ",ST_MakePolygon(ST_GeomFromText('LINESTRING("

  #     $.each layer._latlngs, ->
  #       if firstPoint is ""
  #         firstPoint = @

  #       sql = sql + @.lat + " " + @.lng

  #       sql = sql +  ","

  #     sql = sql + firstPoint.lat + " " + firstPoint.lng + ")', " + $("#inputEPSG").val() + ")))"

  #     console.log(sql)

  #     # Insert the figure in a temporary table.
  #     rest = new H5.Rest (
  #      url: H5.Data.restURL
  #      fields: sql
  #      table: "tmp_pol"
  #      restService: "ws_insertquery.php"
  #     )
  #   else if (type is 'polyline')
  #     # Saves a polyline
  #     firstPoint = ""

  #     layer._leaflet_id = ++idLin

  #     sql = "(nro_ocorrencia, shape) values ( " + nroOcorrencia + ",ST_GeomFromText('LINESTRING("

  #     $.each layer._latlngs, ->
  #       if firstPoint is ""
  #         firstPoint = true
  #         sql = sql + @.lat + " " + @.lng
  #       else
  #         sql = sql + "," + @.lat + " " + @.lng

  #     sql = sql + ")', " + $("#inputEPSG").val() + "))"

  #     console.log(sql)

  #     # Insert the figure in a temporary table.
  #     rest = new H5.Rest (
  #      url: H5.Data.restURL
  #      fields: sql
  #      table: "tmp_lin"
  #      restService: "ws_insertquery.php"
  #     )
  #   else if (type is 'rectangle')

  #     layer._leaflet_id = ++idPol

  #     sql = "(nro_ocorrencia, shape) values ( " + nroOcorrencia + ",ST_MakeEnvelope("

  #     sql = sql +
  #           layer._latlngs[0].lat + "," + layer._latlngs[0].lng + ", " +
  #           layer._latlngs[2].lat + "," + layer._latlngs[2].lng

  #     sql = sql + ", " + $("#inputEPSG").val() + "))"

  #     console.log sql

  #     # Insert the figure in a temporary table.
  #     rest = new H5.Rest (
  #       url: H5.Data.restURL
  #       fields: sql
  #       table: "tmp_pol"
  #       restService: "ws_insertquery.php"
  #     )
  #   else if (type is 'circle')

  #     console.log layer

  #     layer._leaflet_id = ++idPol

  #     sql = "(nro_ocorrencia, shape) values ( " + nroOcorrencia + ", ST_Buffer(ST_GeomFromText('POINT(" +
  #           layer._latlng.lat + " " + layer._latlng.lng + ")'," + $("#inputEPSG").val() + "),"

  #     sql = sql + layer._mRadius/100010 + "))"

  #     console.log sql

  #     rest = new H5.Rest (
  #       url: H5.Data.restURL
  #       fields: sql
  #       table: "tmp_pol"
  #       restService: "ws_insertquery.php"
  #     )

  #   drawnItems.addLayer(layer)

  # minimapView.on 'draw:deleted', (e)->

  #   type = ""
  #   sqlPon = "id_tmp_pol=0 "
  #   sqlLin = "id_tmp_lin=0 "

  #   $.each e.layers._layers, ->

  #     type = @.toGeoJSON().geometry.type

  #     if type is 'Polygon'
  #       sqlPon = sqlPon + "or id_tmp_pol=" + @._leaflet_id + " "
  #     else if type is 'LineString'
  #       sqlLin = sqlLin + "or id_tmp_lin=" + @._leaflet_id + " "
  #     else if type is 'Point'
  #       sqlPon = sqlPon + "or id_tmp_pol=" + @._leaflet_id + " "

  #   if type is 'Polygon'
  #     sqlPon = sqlPon + "and nro_ocorrencia='" + nroOcorrencia + "'"

  #     # Remove lines
  #     rest = new H5.Rest (
  #       url: H5.Data.restURL
  #       table: "tmp_pol"
  #       parameters: sqlPon
  #       restService: "ws_deletequery.php"
  #     )

  #   else if type is 'LineString'
  #     sqlLin = sqlLin + "and nro_ocorrencia='" + nroOcorrencia + "'"

  #     # Remove lines
  #     rest = new H5.Rest (
  #       url: H5.Data.restURL
  #       table: "tmp_lin"
  #       parameters: sqlLin
  #       restService: "ws_deletequery.php"
  #     )
  #   else if type is 'Point'
  #     sqlPon = sqlPon + "and nro_ocorrencia='" + nroOcorrencia + "'"

  #     # Remove lines
  #     rest = new H5.Rest (
  #       url: H5.Data.restURL
  #       table: "tmp_pol"
  #       parameters: sqlPon
  #       restService: "ws_deletequery.php"
  #     )


  # minimapView.on 'draw:edited', (e)->

  #   type = ""
  #   sqlPon = ""
  #   sqlLin = ""
  #   this._map=minimapView

  #   $.each e.layers._layers, ->

  #     firstPoint = ""

  #     type = @.toGeoJSON().geometry.type

  #     if type is 'Polygon'
  #       sql = "shape%3DST_MakePolygon(ST_GeomFromText('LINESTRING("

  #       $.each @._latlngs, ->
  #         if firstPoint is ''
  #           firstPoint = @

  #         sql = sql + "" + @.lat + " " + @.lng

  #         sql = sql + ","

  #       sql = sql + firstPoint.lat + " " + firstPoint.lng + ")', " + $("#inputEPSG").val() + "))"

  #       # # Remove lines
  #       rest = new H5.Rest (
  #         url: H5.Data.restURL
  #         table: "tmp_pol"
  #         fields: sql
  #         parameters: "id_tmp_pol%3D" + @._leaflet_id
  #         restService: "ws_updatequery.php"
  #       )
  #     else if type is 'LineString'
  #       sqlLin = sqlLin + "or id_tmp_lin=" + @._leaflet_id + " "
  #       sql = "shape%3DST_Envelope(ST_GeomFromText('LINESTRING("

  #       sql = sql +
  #             layer._latlngs[0].lat + " " + layer._latlngs[0].lng + ", " +
  #             layer._latlngs[2].lat + " " + layer._latlngs[2].lng

  #       sql = sql + ")', " + $("#inputEPSG").val() + ")))"

  # Add possibles vectors already created (be when reloading the page, be when loading a saved report)
  # Search on database vectors already on the tmp_pol table
  # rest = new H5.Rest (
  #   url: H5.Data.restURL
  #   fields: 'id_tmp_lin, ST_AsGeoJson(shape) as shape'
  #   table: "tmp_lin"
  #   parameters: "nro_ocorrencia='" + nroOcorrencia + "'"
  # )
  # polylineList = rest.data

  # $.each polylineList, ()->

  #   element = JSON.parse(@.shape)

  #   polyline = new L.Polyline(element.coordinates)
  #   polyline._leaflet_id = @.id_tmp_lin
  #   drawnItems.addLayer(polyline)

  # # Add possibles vectors already created (be when reloading the page, be when loading a saved report)
  # # Search on database vectors already on the tmp_pol table
  # rest = new H5.Rest (
  #   url: H5.Data.restURL
  #   fields: 'id_tmp_pol, ST_AsGeoJson(shape) as shape'
  #   table: "tmp_pol"
  #   parameters: "nro_ocorrencia='" + nroOcorrencia + "'"
  # )
  # polygonList = rest.data

  # $.each polygonList, ()->

  #   element = JSON.parse(@.shape)

  #   polygon = new L.Polygon(element.coordinates)
  #   polygon._leaflet_id = @.id_tmp_pol
  #   drawnItems.addLayer(polygon)

  # drawAPI.reloadShape()


  #add search for the address inputText
  GeoSearch =
    _provider: new L.GeoSearch.Provider.Google
    _geosearch: (qry, showAddress) ->
      try
        # console.log @_provider
        console.log qry
        if typeof @_provider.GetLocations is "function"
          # console.log "Is function"
          results = @_provider.GetLocations(qry, ((results) ->
            console.log results
            @_processResults results, showAddress
          ).bind(this))
        else
          # console.log "Not a Function"
          url = @_provider.GetServiceUrl(qry)
          $.getJSON url, (data) (->
            try
              results = @_provider.ParseJSON(data)
              @_processResults results
            catch error
              @_printError error
          ).bind(this)
      catch error
        @_printError error

    _processResults: (results, showAddress) ->
      if results
        if showAddress
          @_showAddres results[0].Label
        else
          @_showLocation results[0]


    _showLocation: (location) ->
      latlng = new L.LatLng(location.Y,location.X)
      drawAPI.setPoint(latlng)

      minimapView.setView(latlng, 15, false)
      if not window.parent.H5.isMobile.any()
        window.parent.H5.Map.base.setView(latlng, 10, false)
      $("#inputLat").val location.Y
      $("#inputLng").val location.X

    _showAddres: (label) ->
      console.log label
      $("#inputMunicipio").val ""
      $("#inputUF").val ""
      $("#inputEndereco").val ""
      address = @_parseLabel label
      console.log address


    _parseLabel: (label) ->
      labelParts = label.split(", ")
      address = {}

      if labelParts.length <= 1
        return null

      #case that it has more than the continent or country
      cepRegExp = new RegExp("[0-9]{1,}-[0-9]{1,}")
      # cidadeEstado = new RegExp("[a-zA-Z]*[\s][-][\s][a-zA-Z]*")
      result = cepRegExp.test(labelParts[labelParts.length - 2])
      if result
        #case that it has the CEP
        console.log labelParts[labelParts.length - 2]
        indexCity = labelParts.length - 3
      else
        indexCity = labelParts.length - 2

      @_parseCidade labelParts[indexCity]
      strAdd = ""
      for i in [0...indexCity] by 1
        strAdd += (labelParts[i] + " ")

      $("#inputEndereco").val strAdd


    _parseCidade: (string) ->
      subCidade = string.split(" - ")
      if subCidade.length > 1
      #case with the city name
        # $("#inputMunicipio").val subCidade[0]
        $('#dropdownMunicipio option').filter ->
          return $(this).text() == subCidade[0]
        .prop 'selected', true
        @_parseEstado subCidade[1]
      else
      #case with only the state name of abbreviation
        # $("#inputMunicipio").val ""
        $("#dropdownMunicipio").val ""
        @_parseEstado string

    _parseEstado: (string) ->
        estados = ["Acre","Alagoas","Amapá","Amazonas","Bahia","Ceará","Distrito Federal","Espírito Santo","Goiás","Maranhão","Mato Grosso","Mato Grosso do Sul","Minas Gerais","Pará","Paraíba","Paraná","Pernambuco","Piauí","Rio de Janeiro","Rio Grande do Norte","Rio Grande do Sul","Rondônia","Roraima","Santa Catarina","São Paulo","Sergipe","Tocantins"]
        uf = ["AC","AL","AP","AM","BA","CE","DF","E","GO","MA","MT","MS","MG","PA","PB","PR","PE","PI","RJ","RN","RS","RO","RR","SC","SP","SE","TO"]
        if string in estados or string in uf
          if string.length > 2
            # $("#inputUF").val uf[estados.indexOf(string)]
            $('#dropdownUF option').filter ->
              return $(this).text() == uf[estados.indexOf(string)]
            .prop 'selected', true
          else
            # $("#inputUF").val string
            $('#dropdownUF option').filter ->
              return $(this).text() == string
            .prop 'selected', true

    _printError: (error) ->
      alert "Erro na Busca: " + error


  # Update marker from changed inputs
  minimapView.on 'draw:created', (event) ->
    type = event.layerType
    layer = event.layer;

    if (type is 'marker') and (($("#inputLat").prop "value" ) isnt "") and (($("#inputLng").prop "value" ) isnt "")
      qry = ($("#inputLat").prop "value" ) + "," + ($("#inputLng").prop "value")
      GeoSearch._geosearch qry,true
  #   else
  #     $("#inputMunicipio").val ""
  #     $("#inputUF").val ""
  #     $("#inputEndereco").val ""
  #     latlng = new L.LatLng(($("#inputLat").prop "value" ) ,($("#inputLng").prop "value" ))
  #     if (!minimapView.hasLayer(Marker))
  #       minimapView.addLayer(Marker)

  #     Marker.setLatLng(latlng).update()
  #     minimapView.setView(latlng, 8, false)

  #   #link the big map with the form map
  #   if not window.parent.H5.isMobile.any()
  #     window.parent.H5.Map.base.setView(latlng, 8, false)

  #   drawAPI.setPoint($("#inputLat").val(), $("#inputLng").val())

  #   # $("#inputEPSG").val ""
  #   # $("#inputEPSG").removeAttr("disabled")

  #connect the GeoSearch to the inputAddress
  $("#inputEndereco").on 'keyup', (event) ->
    # console.log "Entrei no key pressed"
    enterKey = 13
    if event.keyCode is enterKey
      # console.log this.value
      # municipio = $("#inputMunicipio").val()
      municipioVal = document.getElementById('dropdownMunicipio').value
      municipio = $("#dropdownMunicipio option[value='" + municipioVal + "']")
      # uf = $("#inputUF").val()
      ufVal = document.getElementById('dropdownUF').value
      uf = $("#dropdownUF option[value='" + ufVal + "']")
      if municipio.html().length is 0 and uf.html().length is 0
        GeoSearch._geosearch(this.value)
      else
        #GeoSearch._geosearch(this.value + ", " + municipio + " - " + uf)
        GeoSearch._geosearch(this.value + ", " + municipio.html() + " - " + uf.html())

  # Add a move property to the marker
  # Marker.on "move", (event) ->
  #   $("#inputLat").val event.latlng.lat
  #   $("#inputLng").val event.latlng.lng

  #   # $("#inputEPSG").val "4674"
  #   # drawAPI.setSRID('4674')
  #   # $("#inputEPSG").prop "disabled", "disabled"

  #   if not window.parent.H5.isMobile.any()
  #     latlng = new L.LatLng(($("#inputLat").prop "value" ) ,($("#inputLng").prop "value" ))
  #     window.parent.H5.Map.base.setView(latlng, minimapView.getZoom(), false)

  # Create marker from a click event
  # minimapView.on "click", (event) ->
  #   if not minimapView.hasLayer(Marker)
  #     minimapView.addLayer(Marker)

  #   Marker.setLatLng(event.latlng).update()

  #   $("#inputLat").prop("value", event.latlng.lat)
  #   $("#inputLng").prop("value", event.latlng.lng)

  #   # $("#inputEPSG").val "4674"
  #   # drawAPI.setSRID('4674')
  #   # $("#inputEPSG").prop "disabled", "disabled"

  # Sets the zoom on the big map accordingly to the minimap
  # minimapView.on "move zoom", (event) ->
  #   window.parent.H5.Map.base.setView(minimapView.getCenter(), minimapView.getZoom(), false)

  # Create a marker from input values on the page's reload
  # if (($("#inputLat").prop "value" ) isnt "" ) and (($("#inputLng").prop "value" ) isnt "")
  #   latlng = new L.LatLng(($("#inputLat").prop "value" ),($("#inputLng").prop "value" ))
  #   # disabled = $("#inputEPSG").prop("disabled")
  #   # value = $("#inputEPSG").prop("value")
  #   Marker.setLatLng(latlng).update()
  #   # $("#inputEPSG").prop("disabled", disabled)
  #   # $("#inputEPSG").prop("value", value)
  #   # drawAPI.setSRID(value)
  #   minimapView.addLayer(Marker)

  #-------------------------------------------------------------------------
  # FORM VALIDATION
  #-------------------------------------------------------------------------

  addSelection = (idField, value) ->
    field = document.getElementById(idField)
    field.innerHTML =  value

  # Handeling the database access on the form report
  $ ->
    # Put the data from table tipo_localizacao on the form
    tipoLocalizacao = document.getElementById("tipoLocalizacao")

    # Get the data from the database
    rest = new H5.Rest (
      url: H5.Data.restURL
      table: "tipo_localizacao"
      fields: "id_tipo_localizacao, des_tipo_localizacao"
      order: "id_tipo_localizacao"
    )

    total = rest.data.length

    labelOutros = ""

    $.each rest.data, (key, value) ->

      input = document.createElement("input")
      input.id = "TL" + value.id_tipo_localizacao
      input.name = "tipoLocalizacao[]"
      input.type = "checkbox"
      input.value = value.id_tipo_localizacao

      if ($("#semOrigem").attr "checked")?
        input.disabled = "disabled"

      $("span[data-id='postTL']").each ()->
        if (@.innerHTML is input.value)
          input.checked = "checked"
          $(@).remove()
          # addSelection('labelInputCompOrigem',value.des_tipo_localizacao)

      # $(input).click ()->
      #   if $(this).is(":checked")
      #     addSelection('labelInputCompOrigem',value.des_tipo_localizacao)

      span = document.createElement("span")
      span.innerHTML = value.des_tipo_localizacao

      label = document.createElement("label")
      $(label).addClass "checkbox"

      $(label).append input, span

      if value.des_tipo_localizacao isnt "Outro(s)"
        $(tipoLocalizacao).append label
      else
        labelOutros = label

    # Add the last element to the screen
    $(tipoLocalizacao).append labelOutros

    _tipoLocalizacao = tipoLocalizacao

    # Put the data from table tipo_evento on the form
    tipoEvento = document.getElementById("tipoEvento")

    # Get the data from the database
    rest = new H5.Rest (
      url: H5.Data.restURL
      table: "tipo_evento"
      fields: "id_tipo_evento, nome"
      order: "id_tipo_evento"
    )

    total = rest.data.length

    labelOutros = ""

    $.each rest.data, (key, value) ->

      input = document.createElement("input")
      input.id = "TE" + value.id_tipo_evento
      input.name = "tipoEvento[]"
      input.type = "checkbox"
      input.value = value.id_tipo_evento

      if ($("#semEvento").attr "checked")?
        input.disabled = "disabled"

      $("span[data-id='postTE']").each ()->
        if (@.innerHTML is input.value)
          input.checked = "checked"
          $(@).remove()

      span = document.createElement("span")
      span.innerHTML = value.nome

      label = document.createElement("label")
      $(label).addClass "checkbox"

      $(label).append input, span

      if value.nome isnt "Outro(s)"
        $(tipoEvento).append label
      else
        labelOutros = label

    # Add the last element to the screen
    $(tipoEvento).append labelOutros

    _tipoEvento = tipoEvento

    # Put the data from table tipo_dano_identificado on the form
    tipoDanoIdentificado = document.getElementById("tipoDanoIdentificado")

    # Get the data from the database
    rest = new H5.Rest (
      url: H5.Data.restURL
      table: "tipo_dano_identificado"
      fields: "id_tipo_dano_identificado, nome"
      order: "id_tipo_dano_identificado"
    )

    total = rest.data.length

    labelOutros = ""

    $.each rest.data, (key, value) ->

      input = document.createElement("input")
      input.id = "TDI" + value.id_tipo_dano_identificado
      input.name = "tipoDanoIdentificado[]"
      input.type = "checkbox"
      input.value = value.id_tipo_dano_identificado

      if ($("#semDanos").attr "checked")?
        input.disabled = "disabled"

      $("span[data-id='postTDI']").each ()->
        if (@.innerHTML is input.value)
          input.checked = "checked"
          $(@).remove()

      span = document.createElement("span")
      span.innerHTML = value.nome

      label = document.createElement("label")
      $(label).addClass "checkbox"

      $(label).append input, span

      if value.nome isnt "Outro(s)"
        $(tipoDanoIdentificado).append label
      else
        labelOutros = label

    # Add the last element to the screen
    $(tipoDanoIdentificado).append labelOutros

    _tipoDanoIdentificado = tipoDanoIdentificado

    # Put the data from table instituicao_atuando_local on the form
    tipoInstituicaoAtuando = document.getElementById("tipoInstituicaoAtuando")

    # Get the data from the database
    rest = new H5.Rest (
      url: H5.Data.restURL
      table: "instituicao_atuando_local"
      fields: "id_instituicao_atuando_local, nome"
      order: "id_instituicao_atuando_local"
    )

    total = rest.data.length

    labelOutros = ""

    $.each rest.data, (key, value) ->

      input = document.createElement("input")
      input.id = "IAL" + value.id_instituicao_atuando_local
      input.name = "instituicaoAtuandoLocal[]"
      input.type = "checkbox"
      input.value = value.id_instituicao_atuando_local

      if ($("#semInstituicao").attr "checked")?
        input.disabled = "disabled"

      $("span[data-id='postIAL']").each ()->
        if (@.innerHTML is input.value)
          input.checked = "checked"
          $(@).remove()

      span = document.createElement("span")
      span.innerHTML = value.nome

      label = document.createElement("label")
      $(label).addClass "checkbox"

      $(label).append input, span

      if value.nome isnt "Outra(s)"
        $(tipoInstituicaoAtuando).append label
      else
        labelOutros = label

    # Add the last element to the screen
    $(tipoInstituicaoAtuando).append labelOutros

    _tipoInstituicaoAtuando = tipoInstituicaoAtuando

    # Put the data from table instituicao_atuando_local on the form
    tipoFonteInformacao = document.getElementById("tipoFonteInformacao")

    # Get the data from the database
    rest = new H5.Rest (
      url: H5.Data.restURL
      table: "tipo_fonte_informacao"
      fields: "id_tipo_fonte_informacao, nome"
      order: "id_tipo_fonte_informacao"
    )

    total = rest.data.length

    labelOutros = ""

    $.each rest.data, (key, value) ->

      input = document.createElement("input")
      input.id = "TFI" + value.id_tipo_fonte_informacao
      input.name = "tipoFonteInformacao[]"
      input.type = "checkbox"
      input.value = value.id_tipo_fonte_informacao

      $("span[data-id='postTFI']").each ()->
        if (@.innerHTML is input.value)
          input.checked = "checked"
          $(@).remove()


      span = document.createElement("span")
      span.innerHTML = value.nome

      label = document.createElement("label")
      $(label).addClass "checkbox"

      $(label).append input, span

      if value.nome isnt "Outra(s)"
        $(tipoFonteInformacao).append label
      else
        labelOutros = label

    # Add the last element to the screen
    $(tipoFonteInformacao).append labelOutros

    _tipoFonteInformacao = tipoFonteInformacao

    # PRODUTO

    subjects = []

    $.each _tipoProduto, ()->
      subjects.push(@nome)

    $("#nomeProduto").typeahead({source: subjects})


    # $("#inputEPSG").on 'change', ()->
      # drawAPI.setSRID($("#inputEPSG").val())


  #-------------------------------------------------------------------------
  # DISABLE SELECTED INPUTS
  #-------------------------------------------------------------------------
    $("#oceano").on 'click', () ->
      if $(@).is ":checked"
        $("#spanBaciaSed").removeAttr("style")
      else
        $("#spanBaciaSed").attr("style","display:none;")

    $("#semLocalizacao").on 'click', ()->
      if $(this).is(":checked")
        $("#inputLat").attr("disabled","disabled")
        $("#inputLng").attr("disabled","disabled")
        # $("#inputEPSG").attr("disabled","disabled")
        $("#inputMunicipio").attr("disabled","disabled")
        $("#inputUF").attr("disabled","disabled")
        $("#inputEndereco").attr("disabled","disabled")
        $("#btnAddToMap").attr("disabled","disabled")
        $("#dropdownMunicipio").attr("disabled","disabled")
        $("#dropdownUF").attr("disabled","disabled")
      else
        $("#inputLat").removeAttr("disabled")
        $("#inputLng").removeAttr("disabled")
        # $("#inputEPSG").removeAttr("disabled")
        $("#inputMunicipio").removeAttr("disabled")
        $("#inputUF").removeAttr("disabled")
        $("#inputEndereco").removeAttr("disabled")
        $("#btnAddToMap").removeAttr("disabled")
        $("#dropdownMunicipio").removeAttr("disabled")
        $("#dropdownUF").removeAttr("disabled")

    $("#semNavioInstalacao").on 'click', () ->
      if $(@).is ":checked"
        $("#inputNomeNavio").attr("disabled","disabled")
        $("#inputNomeInstalacao").attr("disabled","disabled")
      else
        $("#inputNomeNavio").removeAttr("disabled")
        $("#inputNomeInstalacao").removeAttr("disabled")

    $("#semDataObs").on 'click', ()->
      if $(this).is(":checked")
        $("#inputDataObs").attr("disabled","disabled")
        $("#inputHoraObs").attr("disabled","disabled")
        $("#PerObsMatu").attr("disabled","disabled")
        $("#PerObsVesper").attr("disabled","disabled")
        $("#PerObsNotu").attr("disabled","disabled")
        $("#PerObsMadru").attr("disabled","disabled")
      else
        $("#inputDataObs").removeAttr("disabled")
        $("#inputHoraObs").removeAttr("disabled")
        $("#PerObsMatu").removeAttr("disabled")
        $("#PerObsVesper").removeAttr("disabled")
        $("#PerObsNotu").removeAttr("disabled")
        $("#PerObsMadru").removeAttr("disabled")

    $("#semDataInci").on 'click', ()->
      if $(this).is(":checked")
        $("#inputDataInci").attr("disabled","disabled")
        $("#inputHoraInci").attr("disabled","disabled")
        $("#PerInciMatu").attr("disabled","disabled")
        $("#PerInciVesper").attr("disabled","disabled")
        $("#PerInciNotu").attr("disabled","disabled")
        $("#PerInciMadru").attr("disabled","disabled")
        $("#dtFeriado").attr("disabled","disabled")
      else
        $("#inputDataInci").removeAttr("disabled")
        $("#inputHoraInci").removeAttr("disabled")
        $("#PerInciMatu").removeAttr("disabled")
        $("#PerInciVesper").removeAttr("disabled")
        $("#PerInciNotu").removeAttr("disabled")
        $("#PerInciMadru").removeAttr("disabled")
        $("#dtFeriado").removeAttr("disabled")


    $("#semOrigem").on 'click', ()->
      if $(this).is(":checked")
        $("input[name='tipoLocalizacao[]']").each ()->
          $(this).attr("disabled","disabled")

        $("#inputOrigemOutro").attr("disabled","disabled")
        $("#inputCompOrigem").attr("disabled","disabled")
      else
        $("input[name='tipoLocalizacao[]']").each ()->
          $(this).removeAttr("disabled")

        $("#inputOrigemOutro").removeAttr("disabled")
        $("#inputCompOrigem").removeAttr("disabled")


    $("#semEvento").on 'click', ()->
      if $(this).is(":checked")
        $("input[name='tipoEvento[]']").each ()->
          $(this).attr("disabled","disabled")

        $("#inputEventoOutro").attr("disabled","disabled")
        $("#inputCompEvento").attr("disabled","disabled")
      else
        $("input[name='tipoEvento[]']").each ()->
          $(this).removeAttr("disabled")

        $("#inputEventoOutro").removeAttr("disabled")
        $("#inputCompEvento").removeAttr("disabled")

    $("#semProduto").on 'click', ()->
      if $(this).is(":checked")
        $("#myTable").attr("style", "display:none;")
        $("#productsInfo").attr("style", "display:none;")
      else
        $("#myTable").removeAttr("style")
        $("#productsInfo").removeAttr("style")

    $("#semSubstancia").on 'click', ()->
      if $(this).is(":checked")
        $("#inputTipoSubstancia").attr("disabled","disabled")
        $("#inputValorEstimado").attr("disabled","disabled")
      else
        $("#inputTipoSubstancia").removeAttr("disabled")
        $("#inputValorEstimado").removeAttr("disabled")

    $("#semCausa").on 'click', ()->
      if $(this).is(":checked")
        $("#inputCausaProvavel").attr("disabled","disabled")
      else
        $("#inputCausaProvavel").removeAttr("disabled")

    $("#semDanos").on 'click', ()->
      if $(this).is(":checked")
        $("input[name='tipoDanoIdentificado[]']").each ()->
          $(this).attr("disabled","disabled")

        $("#inputDanoOutro").attr("disabled","disabled")
        $("#inputCompDano").attr("disabled","disabled")
        $("#inputDesDanos").attr("disabled","disabled")
      else
        $("input[name='tipoDanoIdentificado[]']").each ()->
          $(this).removeAttr("disabled")

        $("#inputDanoOutro").removeAttr("disabled")
        $("#inputCompDano").removeAttr("disabled")
        $("#inputDesDanos").removeAttr("disabled")

    $("#semInstituicao").on 'click', ()->
      if $(this).is(":checked")
        $("input[name='instituicaoAtuandoLocal[]']").each ()->
          $(this).attr("disabled","disabled")

        $("#inputInstituicaoOutro").attr("disabled","disabled")
        $("#inputCompInstituicao").attr("disabled","disabled")
        $("#inputInfoInstituicaoNome").attr("disabled","disabled")
        $("#inputInfoInstituicaoTelefone").attr("disabled","disabled")
      else
        $("input[name='instituicaoAtuandoLocal[]']").each ()->
          $(this).removeAttr("disabled")

        $("#inputInstituicaoOutro").removeAttr("disabled")
        $("#inputCompInstituicao").removeAttr("disabled")
        $("#inputInfoInstituicaoNome").removeAttr("disabled")
        $("#inputInfoInstituicaoTelefone").removeAttr("disabled")

    $("#semResponsavel").on 'click', ()->
      if $(this).is(":checked")
        $("#inputResponsavel").attr("disabled","disabled")
        $("#inputCPFCNPJ").attr("disabled","disabled")
        $("#slctLicenca").attr("disabled","disabled")
        # $("button[data-id='slctLicenca']").addClass("disabled")
      else
        $("#inputResponsavel").removeAttr("disabled")
        $("#inputCPFCNPJ").removeAttr("disabled")
        $("#slctLicenca").removeAttr("disabled")
        # $("button[data-id='slctLicenca']").removeClass("disabled")

    $("#semProcedimentos").on 'click', ()->
      if $(this).is(":checked")
        $("#planoEmergNao").attr("disabled","disabled")
        $("#planoEmergSim").attr("disabled","disabled")
        $("#planoAcionado").attr("disabled","disabled")
        $("#outrasMedidas").attr("disabled","disabled")
        $("#inputMedidasTomadas").attr("disabled","disabled")
      else
        $("#planoEmergNao").removeAttr("disabled")
        $("#planoEmergSim").removeAttr("disabled")
        $("#planoAcionado").removeAttr("disabled")
        $("#outrasMedidas").removeAttr("disabled")
        $("#inputMedidasTomadas").removeAttr("disabled")

    # if $("#semResponsavel").is(":checked")
    #   $("button[data-id='slctLicenca']").addClass("disabled")
    # else
    #   $("button[data-id='slctLicenca']").removeClass("disabled")

  $("#inputHoraObs").on 'change', ->
    if ($(@).prop 'value') isnt ""
      obsHour = parseInt($(this).prop('value').split(':')[0] , 10)
      if obsHour < 6
        $("#PerObsMadru").prop('checked', 'checked')
      else if obsHour < 12
        $("#PerObsMatu").prop('checked', 'checked')
      else if obsHour < 18
        $("#PerObsVesper").prop('checked', 'checked')
      else
        $("#PerObsNotu").prop('checked', 'checked')

      $("#divPeriodoObs").prop('style','display:none;')

    else
      $("#divPeriodoObs").prop('style','')

  $("#inputHoraInci").on 'change', ->
    if ($(@).prop 'value') isnt ""
      obsHour = parseInt($(this).prop('value').split(':')[0] , 10)

      if obsHour < 6
        $("#PerInciMadru").prop('checked', 'checked')
      else if obsHour < 12
        $("#PerInciMatu").prop('checked', 'checked')
      else if obsHour < 18
        $("#PerInciVesper").prop('checked', 'checked')
      else
        $("#PerInciNotu").prop('checked', 'checked')

      $("#divPeriodoInci").prop('style','display:none;')
    else
      $("#divPeriodoInci").prop('style','display:auto;')

  if ($("#inputHoraObs").prop 'value') isnt ''
    $("#divPeriodoObs").prop('style','display:none;')

  if ($("#inputHoraInci").prop 'value') isnt ''
    $("#divPeriodoInci").prop('style','display:none;')

  #-------------------------------------------------------------------------
  # MASK FOR FIELDS
  #-------------------------------------------------------------------------

  $("#inputDataObs").mask("99/99/9999")
  $("#inputHoraObs").mask("99:99")
  $("#inputDataInci").mask("99/99/9999")
  $("#inputHoraInci").mask("99:99")

  $('#inputCompOrigem')
    .add('#inputCompEvento')
    .add('#inputCompInstituicao')
    .add('#inputCompDano')
    .add('#inputCausaProvavel')
    .add('#inputMedidasTomadas')
    .add('#inputDesOcorrencia')
    .add('#inputDesObs')
    .add('#inputDesDanos')
    .maxlength(
      alwaysShow: true,
      threshold: 10,
      warningClass: "label label-info",
      limitReachedClass: "label label-important",
      placement: 'bottom',
      preText: '',
      separator: ' de ',
      postText: ' caracteres'
  )

  #-------------------------------------------------------------------------
  # FORM DATA TABLE
  #-------------------------------------------------------------------------

  subjects = []

  $.each _tipoProduto, ()->

    element =
      value: @id_produto
      text: $.trim(@nome) + '-' + $.trim(@num_onu) + '-' + $.trim(@classe_risco)
    subjects.push(element)

    # subjects.push(@nome)

  if isLoadForm
    table = new H5.Table (
      container: "myTable"
      url: H5.Data.restURL
      table: "ocorrencia_produto%20left%20join%20produto%20on%20(produto.id_produto%3Docorrencia_produto.id_produto)%20left%20join%20ocorrencia%20on%20(ocorrencia_produto.id_ocorrencia%3Docorrencia.id_ocorrencia)"
      primaryTable: 'ocorrencia_produto'
      parameters: "ocorrencia_produto.id_ocorrencia%3D'" + idOcorrencia + "'"
      fields:
        id_ocorrencia_produto:
          columnName: "Identificador"
          isVisible: false
        nome:
          columnName: "Substância - Nº Onu - CR"
          tableName: "trim(nome) || '-' || trim(num_onu) || '-' || trim(classe_risco) as nome"
          primaryField: "id_produto"
          validation: (value)->
            text = ''
            if value is '' or value is 'Empty'
              text = 'Valor não pode ser vazio'
            return text
          searchData: subjects
        quantidade:
          columnName: "Qtd."
          validation: (value)->
            text = ''
            if value is '' or value is 'Empty'
              text = 'Valor não pode ser vazio'
            return text
        unidade_medida:
          columnName: "Unidade"
          selectArray:[
            value :"m3"
            text: "Metro Cúbico (m3)"
          ,
            value : "l"
            text : "Litro (L)"
          ,
            value: "t"
            text: "Tonelada (T)"
          ,
            value:"kg"
            text: "Quilograma (Kg)"
          ]
          validation: (value)->
            text = ''
            if value is '' or value is 'Empty'
              text = 'Valor não pode ser vazio'
            return text
        id_ocorrencia:
          columnName: "Nro. Ocorrencia"
          tableName: "ocorrencia_produto.id_ocorrencia"
          defaultValue: idOcorrencia
          isVisible: false

      uniqueField:
        field: "id_ocorrencia_produto"
    )
  else
    table = new H5.Table (
      container: "myTable"
      url: H5.Data.restURL
      registUpdate: true
      table: "tmp_ocorrencia_produto%20left%20join%20produto%20on%20(produto.id_produto%3Dtmp_ocorrencia_produto.id_produto)"
      primaryTable: 'tmp_ocorrencia_produto'
      parameters: "nro_ocorrencia%3D'" + nroOcorrencia + "'"
      fields:
        id_ocorrencia_produto:
          columnName: "Identificador"
          isVisible: false
        nro_ocorrencia:
          columnName: " "
          defaultValue: nroOcorrencia
          isVisible: false
        nome:
          columnName: "Substância - Nº Onu - CR"
          tableName: "trim(nome) || '-' || trim(num_onu) || '-' || trim(classe_risco) as nome"
          primaryField: "id_produto"
          validation: (value)->
            text = ''
            if value is '' or value is 'Empty'
              text = 'Valor não pode ser vazio'
            return text
          searchData: subjects
        quantidade:
          columnName: "Qtd."
          validation: (value)->
            text = ''
            if value is '' or value is 'Empty'
              text = 'Valor não pode ser vazio'
            return text
        unidade_medida:
          columnName: "Unidade"
          selectArray:[
            value :"m3"
            text: "Metro Cúbico (m3)"
          ,
            value : "l"
            text : "Litro (L)"
          ,
            value: "t"
            text: "Tonelada (T)"
          ,
            value:"kg"
            text: "Quilograma (Kg)"
          ]
          validation: (value)->
            text = ''
            if value is '' or value is 'Empty'
              text = 'Valor não pode ser vazio'
            return text
      uniqueField:
        field: "id_ocorrencia_produto"
    )