H5.Data.restURL = "http://" + document.domain + "/siema/rest"
$(document).ready ->

  _tipoLocalizacao = null
  _tipoEvento = null
  _tipoDanoIdentificado  = null
  _tipoInstituicaoAtuando = null
  _tipoFonteInformacao = null
  _tipoProduto = null

  idOcorrencia = null

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

  _tipoProduto = rest.data

  #-------------------------------------------------------------------------
  # FORM
  #-------------------------------------------------------------------------

  # List that stores the order on tabs to be accessed, going backwards
  history = []
  # Stores the next tab to be accessed
  collapse=2

  $('#addMeModal').on 'hidden', ->
    history = []
    collapse = 2

    btnBack = document.getElementById("modalBtnBack")

    btnBack.href = '#tab1'
    $("#modalBtnBack").tab('show')
    $("#modalBtnBack").show()
    $("#modalBtnNext").show()
    $("#submit").hide()
    $("#modalBtnCancel").hide()
    $("#btnClose").hide()
    $(".modal-footer").show()

  #hide footer o form when click on topbar
  $("#btn-form").click (event) ->
    $(".modal-footer").hide()

  # Dealing with going backwards on the form and possibles jumps
  $("#modalBtnBack").click (event) ->
    event.preventDefault()

    btnNext = document.getElementById("modalBtnNext")

    if history.length > 0
      tab = history.pop()
      @.href = tab.tab
      collapse = tab.collapse

    $(".modal-footer").hide()

    $(@).tab('show')

  # Dealing with going backwards on the form and possibles jumps
  $("#modalBtnCancel").click (event) ->
    event.preventDefault()

    btnNext = document.getElementById("modalBtnNext")
    btnBack = document.getElementById("modalBtnBack")

    if history.length > 0
      tab = history.pop()
      @.href = tab.tab
      collapse = tab.collapse

    $(".modal-footer").show()
    $(btnNext).show()
    $(btnBack).show()
    $("#submit").hide()
    $(@).hide()

    # Clean the temporary produt table (tmp_ocorrencia_produto)
    rest = new H5.Rest (
     url: H5.Data.restURL
     table: "tmp_ocorrencia_produto"
     restService: "ws_deletequery.php"
    )

    # Clean the temporary polygon table (tmp_pol)
    rest = new H5.Rest (
     url: H5.Data.restURL
     table: "tmp_pol"
     restService: "ws_deletequery.php"
    )

    # Clean the temporary polyline table (tmp_lin)
    rest = new H5.Rest (
     url: H5.Data.restURL
     table: "tmp_lin"
     restService: "ws_deletequery.php"
    )

    # Clean the temporary point table (tmp_pon)
    rest = new H5.Rest (
     url: H5.Data.restURL
     table: "tmp_pon"
     restService: "ws_deletequery.php"
    )

    $(@).tab('show')


  $("#btnBeginForm").click (event) ->
    if !(document.getElementById('divLogin'))?
      progressBar = document.getElementById("authProgress")
      textProgress = document.getElementById("textProgress")
      containerProgress = document.getElementById("containerProgress")
      checkedUser = document.getElementById("checkedUser")
      tipoForm = document.getElementById("tipoForm")
      btnLogout = document.getElementById("btnLogout")

      $(tipoForm).hide()
      $(btnLogout).hide()
      i=0
      progressAnimetion = setInterval( ->
        $(progressBar).width(i++ + "0%")
        if i is 15
          $(containerProgress).hide()
          $(textProgress).hide()
          $(textProgress).html('Usuário registrado.')
          $(textProgress).fadeToggle()
          $(checkedUser).show()
          $(tipoForm).show()
          $(btnLogout).show()
          clearInterval(progressAnimetion)
      , 100)
    if $("#containerProgress").is(":hidden")
      $(tipoForm).show()
      $(btnLogout).show()


  # Dealing with going foward on the form and possibles jumps
  $("#modalBtnNext").click (event) ->
    event.preventDefault()

    # if ("#tab" + collapse) isnt "#tab8"
    history.push(
      tab: "#tab" + collapse
      collapse: collapse
    )

    @.href = "#tab" + ++collapse

    if ("#tab" + collapse) is "#tab2"
      if !(document.getElementById('divLogin'))?
        progressBar = document.getElementById("authProgress")
        textProgress = document.getElementById("textProgress")
        containerProgress = document.getElementById("containerProgress")
        checkedUser = document.getElementById("checkedUser")
        tipoForm = document.getElementById("tipoForm")
        btnLogout = document.getElementById("btnLogout")

        $(tipoForm).hide()
        $(btnLogout).hide()
        i=0
        progressAnimetion = setInterval( ->
          $(progressBar).width(i++ + "0%")
          if i is 15
            $(containerProgress).hide()
            $(textProgress).hide()
            $(textProgress).html('Usuário registrado.')
            $(textProgress).fadeToggle()
            $(checkedUser).show()
            $(tipoForm).show()
            $(btnLogout).show()
            clearInterval(progressAnimetion)
        , 100)
      $(".modal-footer").hide()
    else
      $(".modal-footer").show()

      # Point of division: selecting type of the accident
      if ("#tab" + collapse) is "#tab4"
        isPubExt = document.getElementById("radioPubExt").checked

        # Verifies which type of accident was chosen
        if isPubExt
          collapse = 5
          @.href = "#tab" + 5

      else if ("#tab" + collapse) is "#tab8"
        isAcidOleo = document.getElementById("optionsAcidenteOleo").checked
        isOutros = document.getElementById("optionsAcidenteOutros").checked
        isAtual = document.getElementById("optionsAtualizarAcidente").checked

        hasOleo = document.getElementById("hasOleo")
        isServIBAMA = document.getElementById("isServIBAMA")

        hasOleo.checked = isAcidOleo

    if ("#tab" + collapse) is "#tab8"

      $("#submit").show()
      $("#modalBtnNext").hide()
      $("#modalBtnBack").hide()
      $("#modalBtnCancel").show()

      if isAtual
        if($("#inputRegistro").prop("value") isnt "")
          defaultHtml = document.getElementById("defaultHtml")
          if(defaultHtml.innerHTML is "")
            defaultHtml.innerHTML = $("#formLoad").prop("action")
          action = defaultHtml.innerHTML + "/" + $("#inputRegistro").prop("value")
          $("#formLoad").prop "action", action
          $("#formLoad").submit()
        else
          $("#inputRegistro").focus()
      else

        $("#formCreate").submit()

    $(@).tab('show')

 # Dealing with the jump on the register part on the accident form
  $("#tipoForm").click (event) ->
    event.preventDefault()

    history.push(
      tab: "#tab2"
      collapse: collapse
    )

    this.href = "#tab7"
    collapse = 7

    $(".modal-footer").show()

    $(@).tab('show')

  # Dealing with the jump on the register part on the accident form
  $("#denunciaAnonima").click (event) ->
    event.preventDefault()

    history.push(
      tab: "#tab2"
      collapse: collapse
    )

    this.href = "#tab7"
    collapse = 7

    $(".modal-footer").show()

    $(@).tab('show')

  # Dealing with the register part on the accident form
  $("#btnCadastrar").click (event) ->
    event.preventDefault()

    history.push(
      tab: "#tab2"
      collapse: collapse
    )

    this.href = "#tab3"
    collapse = 3

    $(".modal-footer").show()

    $(@).tab('show')

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
  nroOcorrencia = $("#comunicado").val()

  # Add a move property to the marker
  Marker.on "move", (event) ->
    $("#inputLat").val event.latlng.lat
    $("#inputLng").val event.latlng.lng

    $("#inputEPSG").val "4674"
    $("#inputEPSG").prop "disabled", "disabled"

    if not window.parent.H5.isMobile.any()
      latlng = new L.LatLng(($("#inputLat").prop "value" ) ,($("#inputLng").prop "value" ))
      window.parent.H5.Map.base.setView(latlng, minimapView.getZoom(), false)

  minimapView = new L.Map("minimap",
    center: new L.LatLng(-10.0, -50.0)
    zoom: 3
    layers: [binghybrid]
    zoomControl: true
    )

  # Add draw functionality to a map
  drawnItems = new L.FeatureGroup()
  minimapView.addLayer(drawnItems)

  drawControl = new L.Control.Draw({
      draw: {
        marker: false
      },
      edit: {
        featureGroup: drawnItems
      }
  })
  minimapView.addControl(drawControl);

  minimapView.on 'draw:created', (e)->
    type = e.layerType

    layer = e.layer

    console.log (e.layer)

    drawnItems.addLayer(layer);

    if (type is 'polygon')
      # Saves a polygon
      firstPoint = ""

      sql = "(id_tmp_pol, nro_ocorrencia, shape) values ( " +  layer._leaflet_id + "," + nroOcorrencia + ",ST_MakePolygon(ST_GeomFromText('LINESTRING("

      $.each layer._latlngs, ->
        if firstPoint is ""
          firstPoint = @

        sql = sql + @.lat + " " + @.lng

        sql = sql +  ","

      sql = sql + firstPoint.lat + " " + firstPoint.lng + ")', " + $("#inputEPSG").val() + ")))"

      console.log(sql)

      # Insert the figure in a temporary table.
      rest = new H5.Rest (
       url: H5.Data.restURL
       fields: sql
       table: "tmp_pol"
       restService: "ws_insertquery.php"
      )

    else if (type is 'polyline')
      # Saves a polyline
      firstPoint = ""

      sql = "(id_tmp_lin, nro_ocorrencia, shape) values ( " +  layer._leaflet_id + "," + nroOcorrencia + ",ST_GeomFromText('LINESTRING("

      $.each layer._latlngs, ->
        if firstPoint is ""
          firstPoint = true
          sql = sql + @.lat + " " + @.lng
        else
          sql = sql + "," + @.lat + " " + @.lng

      sql = sql + ")', " + $("#inputEPSG").val() + "))"

      console.log(sql)

      # Insert the figure in a temporary table.
      rest = new H5.Rest (
       url: H5.Data.restURL
       fields: sql
       table: "tmp_lin"
       restService: "ws_insertquery.php"
      )
    else if (type is 'rectangle')
      console.log layer

      sql = "(id_tmp_pol, nro_ocorrencia, shape) values ( " +  layer._leaflet_id + "," + nroOcorrencia + ",ST_Envelope(ST_GeomFromText('LINESTRING("

      sql = sql +
            layer._latlngs[0].lat + " " + layer._latlngs[0].lng + ", " +
            layer._latlngs[2].lat + " " + layer._latlngs[2].lng

      sql = sql + ")', " + $("#inputEPSG").val() + ")))"

      console.log sql

      # Insert the figure in a temporary table.
      rest = new H5.Rest (
        url: H5.Data.restURL
        fields: sql
        table: "tmp_pol"
        restService: "ws_insertquery.php"
      )


  minimapView.on 'draw:deleted', (e)->
    console.log e

    type = ""
    sqlPon = "id_tmp_pol=0 "
    sqlLin = "id_tmp_lin=0 "

    $.each e.layers._layers, ->

      type = @.toGeoJSON().geometry.type

      if type is 'Polygon'
        sqlPon = sqlPon + "or id_tmp_pol=" + @._leaflet_id + " "
      else if type is 'LineString'
        sqlLin = sqlLin + "or id_tmp_lin=" + @._leaflet_id + " "

    if type is 'Polygon'
      sqlPon = sqlPon + "and nro_ocorrencia='" + nroOcorrencia + "'"

      # Remove lines
      rest = new H5.Rest (
        url: H5.Data.restURL
        table: "tmp_pol"
        parameters: sqlPon
        restService: "ws_deletequery.php"
      )

    else if type is 'LineString'
      sqlLin = sqlLin + "and nro_ocorrencia='" + nroOcorrencia + "'"

      # Remove lines
      rest = new H5.Rest (
        url: H5.Data.restURL
        table: "tmp_lin"
        parameters: sqlLin
        restService: "ws_deletequery.php"
      )


  minimapView.on 'draw:edited', (e)->
    console.log 'editing..'
    console.log e

    type = ""
    sqlPon = ""
    sqlLin = ""

    $.each e.layers._layers, ->

      firstPoint = ""

      type = @.toGeoJSON().geometry.type

      if type is 'Polygon'
        sql = "shape%3DST_MakePolygon(ST_GeomFromText('LINESTRING("

        $.each @._latlngs, ->
          if firstPoint is ''
            firstPoint = @

          sql = sql + "" + @.lat + " " + @.lng

          sql = sql + ","

        sql = sql + firstPoint.lat + " " + firstPoint.lng + ")', " + $("#inputEPSG").val() + "))"

        # # Remove lines
        rest = new H5.Rest (
          url: H5.Data.restURL
          table: "tmp_pol"
          fields: sql
          parameters: "id_tmp_pol%3D" + @._leaflet_id
          restService: "ws_updatequery.php"
        )
      else if type is 'LineString'
        sqlLin = sqlLin + "or id_tmp_lin=" + @._leaflet_id + " "
        sql = "shape%3DST_Envelope(ST_GeomFromText('LINESTRING("

        sql = sql +
              layer._latlngs[0].lat + " " + layer._latlngs[0].lng + ", " +
              layer._latlngs[2].lat + " " + layer._latlngs[2].lng

        sql = sql + ")', " + $("#inputEPSG").val() + ")))"
        # # Remove lines
        # rest = new H5.Rest (
        #   url: H5.Data.restURL
        #   table: "tmp_lin"
        #   parameters: sqlLin
        #   restService: "ws_deletequery.php"
        # )


    # if type is 'Polygon'
    #   sqlPon = sqlPon + "and nro_ocorrencia='" + nroOcorrencia + "'"


    # else if type is 'LineString'
    #   sqlLin = sqlLin + "and nro_ocorrencia='" + nroOcorrencia + "'"

  # Add possibles vectors already created (be when reloading the page, be when loading a saved report)
  # Search on database vectors already on the tmp_pol table
  rest = new H5.Rest (
    url: H5.Data.restURL
    fields: 'id_tmp_lin, ST_AsGeoJson(shape) as shape'
    table: "tmp_lin"
    parameters: "nro_ocorrencia='" + nroOcorrencia + "'"
  )
  polylineList = rest.data

  $.each polylineList, ()->

    element = JSON.parse(@.shape)

    polyline = new L.Polyline(element.coordinates)
    polyline._leaflet_id = @.id_tmp_lin
    drawnItems.addLayer(polyline)

  # Add possibles vectors already created (be when reloading the page, be when loading a saved report)
  # Search on database vectors already on the tmp_pol table
  rest = new H5.Rest (
    url: H5.Data.restURL
    fields: 'id_tmp_pol, ST_AsGeoJson(shape) as shape'
    table: "tmp_pol"
    parameters: "nro_ocorrencia='" + nroOcorrencia + "'"
  )
  polygonList = rest.data

  $.each polygonList, ()->

    element = JSON.parse(@.shape)

    polygon = new L.Polygon(element.coordinates)
    polygon._leaflet_id = @.id_tmp_pol
    drawnItems.addLayer(polygon)


  #add search for the address inputText
  GeoSearch =
    _provider: new L.GeoSearch.Provider.Google
    _geosearch: (qry) ->
      try
        # console.log @_provider
        console.log qry
        if typeof @_provider.GetLocations is "function"
          # console.log "Is function"
          results = @_provider.GetLocations(qry, ((results) ->
            console.log results
            @_processResults results
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

    _processResults: (results) ->
      if results
        @_showLocation results[0]

    _showLocation: (location) ->
      latlng = new L.LatLng(location.Y,location.X)
      if (!minimapView.hasLayer(Marker))
        minimapView.addLayer(Marker)

      Marker.setLatLng(latlng).update()

      minimapView.setView(latlng, 15, false)
      if not window.parent.H5.isMobile.any()
        window.parent.H5.Map.base.setView(latlng, 10, false)
      $("#inputLat").val location.Y
      $("#inputLng").val location.X

    _printError: (error) ->
      alert "Erro na Busca: " + error

  # Update marker from changed inputs
  $("#inputLat, #inputLng").on 'change', (event) ->
    if (($("#inputLat").prop "value" ) isnt "") and (($("#inputLng").prop "value" ) isnt "")
      latlng = new L.LatLng(($("#inputLat").prop "value" ) ,($("#inputLng").prop "value" ))
      if (!minimapView.hasLayer(Marker))
        minimapView.addLayer(Marker)

      Marker.setLatLng(latlng).update()
      minimapView.setView(latlng, 8, false)

    #link the big map with the form map
    if not window.parent.H5.isMobile.any()
      window.parent.H5.Map.base.setView(latlng, 8, false)

    $("#inputEPSG").val ""
    $("#inputEPSG").removeAttr("disabled")

  #connect the GeoSearch to the inputAddress
  $("#inputEndereco").on 'keyup', (event) ->
    # console.log "Entrei no key pressed"
    enterKey = 13
    if event.keyCode is enterKey
      # console.log this.value
      municipio = $("#inputMunicipio").val()
      uf = $("#inputUF").val()
      if municipio.length is 0 and uf.length is 0
        GeoSearch._geosearch(this.value)
      else
        GeoSearch._geosearch(this.value + ", " + municipio + " - " + uf)


  # Add a move property to the marker
  Marker.on "move", (event) ->
    $("#inputLat").val event.latlng.lat
    $("#inputLng").val event.latlng.lng

    $("#inputEPSG").val "4674"
    $("#inputEPSG").prop "disabled", "disabled"

    if not window.parent.H5.isMobile.any()
      latlng = new L.LatLng(($("#inputLat").prop "value" ) ,($("#inputLng").prop "value" ))
      window.parent.H5.Map.base.setView(latlng, minimapView.getZoom(), false)

  # Create marker from a click event
  minimapView.on "click", (event) ->
    if not minimapView.hasLayer(Marker)
      minimapView.addLayer(Marker)

    Marker.setLatLng(event.latlng).update()

    $("#inputLat").prop("value", event.latlng.lat)
    $("#inputLng").prop("value", event.latlng.lng)

    $("#inputEPSG").val "4674"
    $("#inputEPSG").prop "disabled", "disabled"

  # Create marker from a click event
  minimapView.on "move zoom", (event) ->
    window.parent.H5.Map.base.setView(minimapView.getCenter(), minimapView.getZoom(), false)

  # Create a marker from input values on the page's reload
  if (($("#inputLat").prop "value" ) isnt "" ) and (($("#inputLng").prop "value" ) isnt "")
    latlng = new L.LatLng(($("#inputLat").prop "value" ),($("#inputLng").prop "value" ))
    disabled = $("#inputEPSG").prop("disabled")
    value = $("#inputEPSG").prop("value")
    Marker.setLatLng(latlng).update()
    $("#inputEPSG").prop("disabled", disabled)
    $("#inputEPSG").prop("value", value)
    minimapView.addLayer(Marker)

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
          addSelection('labelInputCompOrigem',value.des_tipo_localizacao)

      $(input).click ()->
        if $(this).is(":checked")
          addSelection('labelInputCompOrigem',value.des_tipo_localizacao)

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

      $(input).click ()->
        if $(this).is(":checked")
          addSelection('labelInputCompEvento',value.nome)

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

      $(input).click ()->
        if $(this).is(":checked")
          addSelection('labelInputCompDano',value.nome)

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

      $(input).click ()->
        if $(this).is(":checked")
          addSelection('labelInputCompInstituicao',value.nome)

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



  #-------------------------------------------------------------------------
  # DISABLE SELECTED INPUTS
  #-------------------------------------------------------------------------

    $("#semLocalizacao").on 'click', ()->
      if $(this).is(":checked")
        $("#inputLat").attr("disabled","disabled")
        $("#inputLng").attr("disabled","disabled")
        $("#inputEPSG").attr("disabled","disabled")
        $("#inputMunicipio").attr("disabled","disabled")
        $("#inputUF").attr("disabled","disabled")
        $("#inputEndereco").attr("disabled","disabled")
        $("#btnAddToMap").attr("disabled","disabled")
        $("button[data-id='slctLicenca']").attr("disabled", "disabled")
      else
        $("#inputLat").removeAttr("disabled")
        $("#inputLng").removeAttr("disabled")
        $("#inputEPSG").removeAttr("disabled")
        $("#inputMunicipio").removeAttr("disabled")
        $("#inputUF").removeAttr("disabled")
        $("#inputEndereco").removeAttr("disabled")
        $("#btnAddToMap").removeAttr("disabled")
        $("button[data-id='slctLicenca']").removeAttr("disabled")

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
      else
        $("#inputDataInci").removeAttr("disabled")
        $("#inputHoraInci").removeAttr("disabled")
        $("#PerInciMatu").removeAttr("disabled")
        $("#PerInciVesper").removeAttr("disabled")
        $("#PerInciNotu").removeAttr("disabled")
        $("#PerInciMadru").removeAttr("disabled")


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
      else
        $("input[name='instituicaoAtuandoLocal[]']").each ()->
          $(this).removeAttr("disabled")

        $("#inputInstituicaoOutro").removeAttr("disabled")
        $("#inputCompInstituicao").removeAttr("disabled")

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

  if $(window.top.document.getElementById("optionsAtualizarAcidente")).is(":checked")
    table = new H5.Table (
      container: "myTable"
      url: restURL
      table: "ocorrencia_produto%20left%20join%20produto%20on%20(produto.id_produto%3Docorrencia_produto.id_produto)%20left%20join%20ocorrencia%20on%20(ocorrencia_produto.id_ocorrencia%3Docorrencia.id_ocorrencia)"
      primaryTable: 'ocorrencia_produto'
      parameters: "ocorrencia_produto.id_ocorrencia%3D'" + idOcorrencia + "'"
      fields:
        id_ocorrencia_produto:
          columnName: "Identificador"
          tableName: "id_ocorrencia_produto"
          isVisible: false
          validation: null
        nome:
          columnName: "Nome da Substância - Nro. da Onu - Classe de Risco"
          tableName: "trim(nome) || '-' || trim(num_onu) || '-' || trim(classe_risco) as nome"
          primaryField: "id_produto"
          validation: null
          searchData: subjects
        quantidade:
          columnName: "Qtd."
          tableName: "quantidade"
          validation: null
        unidade_medida:
          columnName: "Unidade"
          tableName: "unidade_medida"
          validation: null
        id_ocorrencia:
          columnName: "Nro. Ocorrencia"
          tableName: "ocorrencia_produto.id_ocorrencia"
          defaultValue: idOcorrencia
          validation: null
          isVisible: false

      uniqueField:
        field: "id_ocorrencia_produto"
        insertable: false
    )
  else
    table = new H5.Table (
      container: "myTable"
      url: restURL
      table: "tmp_ocorrencia_produto%20left%20join%20produto%20on%20(produto.id_produto%3Dtmp_ocorrencia_produto.id_produto)"
      primaryTable: 'tmp_ocorrencia_produto'
      fields:
        id_ocorrencia_produto:
          columnName: "Identificador"
          tableName: "id_ocorrencia_produto"
          isVisible: false
          validation: null
        nro_ocorrencia:
          columnName: " "
          tableName: "nro_ocorrencia"
          defaultValue: nroOcorrencia
          isVisible: false
          validation: null
        nome:
          columnName: "Nome da Substância - Nro. da Onu - Classe de Risco"
          tableName: "trim(nome) || '-' || trim(num_onu) || '-' || trim(classe_risco) as nome"
          primaryField: "id_produto"
          validation: null
          searchData: subjects
        quantidade:
          columnName: "Qtd."
          tableName: "quantidade"
          validation: null
        unidade_medida:
          columnName: "Unidade"
          tableName: "unidade_medida"
          validation: null
      uniqueField:
        field: "id_ocorrencia_produto"
        insertable: false
    )
