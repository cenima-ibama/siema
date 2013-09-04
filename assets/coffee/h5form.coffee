$(document).ready ->

  _tipoLocalizacao = null
  _tipoEvento = null
  _tipoDanoIdentificado  = null
  _tipoInstituicaoAtuando = null
  _tipoFonteInformacao = null

  @produto = null

  #-------------------------------------------------------------------------
  # FORM
  #-------------------------------------------------------------------------


  # Get the product name from the database, by ajax
  rest = new H5.Rest (
    url: "../../../../siema/rest_v2"
    table: "produto"
    fields: "id_produto,nome,num_onu,classe_risco"
    order: "nome"
  )

  @produto = rest.data

  # List that stores the order on tabs to be accessed, going backwards
  history = []
  # Stores the next tab to be accessed
  collapse=2

  $('#addMeModal').on 'hidden', ->
    history = []
    collapse = 1

    btnBack = document.getElementById("modalBtnBack")

    btnBack.href = '#tab1'
    $("#modalBtnBack").tab('show')
    $("#modalBtnNext").prop 'style', ''
    $("#submit").prop 'style', 'display:none;'
    $(".modal-footer").show()

  if !$("#comunicado").val()
    date = new Date()

    seconds = parseInt(date.getSeconds() + (date.getHours() * 60 * 60), 10)
    nroComunicado = "" + parseInt(date.getFullYear(),10) + parseInt(date.getMonth() + 1,10) + parseInt(date.getDate(),10) + seconds

    $("#comunicado").val(nroComunicado)
    $("#nroComunicado").html(nroComunicado)

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

    if ("#tab" + collapse) is "#tab2"
      $(".modal-footer").hide()
    else
      $(".modal-footer").show()

    if ("#tab" + collapse) is "#tab7"
      $(btnNext).prop 'style', ''
      $(@).html('Voltar')
      $("#submit").prop 'style', 'display:none;'

    $(@).tab('show')


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

        if isAtual
          # Get the accident data for atualization
          rest = new H5.Rest (
            url: "../../../siema/rest_v2"
            table: "tipo_dano_identificado"
            fields: "id_tipo_dano_identificado, nome"
            order: "id_tipo_dano_identificado"
          )

        hasOleo.checked = isAcidOleo

    if ("#tab" + collapse) is "#tab8"

      $("#submit").prop 'style', ''
      $("#modalBtnNext").prop 'style', 'display:none;'
      $("#modalBtnBack").html('<i class="icon-remove"></i> Cancelar')


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


  # $(".accordion-body").on 'shown', ->
  #   $(@).focus()

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
  $( '#minimap' ).css("height", "205px")
  $( '#minimap' ).css("width", "100%")
  $( '#minimap' ).css("box-shadow", "0 0 0 1px rgba(0, 0, 0, 0.15)")
  $( '#minimap' ).css("border-radius", "4px")


  #-------------------------------------------------------------------------
  # MARKER CREATION
  #-------------------------------------------------------------------------

  Marker = new L.Marker([0 ,0], {draggable:true})

  # Add a move property to the marker
  Marker.on "move", (event) ->
    $("#inputLat").prop "value", event.latlng.lat
    $("#inputLng").prop "value", event.latlng.lng

    $("#inputEPSG").prop "value", "4674"
    $("#inputEPSG").prop "disabled", "disabled"

  H5.Map.minimap = new L.Map("minimap",
    center: new L.LatLng(-10.0, -58.0)
    zoom: 6
    layers: [binghybrid]
    zoomControl: true
    )

  # Update marker from changed inputs
  $("#inputLat, #inputLng").on 'change', ->
    if (($("#inputLat").prop "value" ) isnt "") and (($("#inputLng").prop "value" ) isnt "")
      latlng = new L.LatLng(($("#inputLat").prop "value" ) ,($("#inputLng").prop "value" ))
      if (!H5.Map.minimap.hasLayer(Marker))
        H5.Map.minimap.addLayer(Marker)

      Marker.setLatLng(latlng).update()

    $("#inputEPSG").prop "value", ""
    $("#inputEPSG").removeAttr("disabled")

  # Create marker from a click event
  H5.Map.minimap.on "click", (event) ->
    if not H5.Map.minimap.hasLayer(Marker)
      H5.Map.minimap.addLayer(Marker)

    Marker.setLatLng(event.latlng).update()

    $("#inputLat").prop("value", event.latlng.lat)
    $("#inputLng").prop("value", event.latlng.lng)

    $("#inputEPSG").prop "value", "4674"
    $("#inputEPSG").prop "disabled", "disabled"

  # Create a marker from input values on the page's reload
  if (($("#inputLat").prop "value" ) isnt "" ) and (($("#inputLng").prop "value" ) isnt "")
    latlng = new L.LatLng(($("#inputLat").prop "value" ),($("#inputLng").prop "value" ))
    disabled = $("#inputEPSG").prop("disabled")
    value = $("#inputEPSG").prop("value")
    Marker.setLatLng(latlng).update()
    $("#inputEPSG").prop("disabled", disabled)
    $("#inputEPSG").prop("value", value)
    H5.Map.minimap.addLayer(Marker)

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
      url: "../../../../siema/rest_v2"
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
      url: "../../../../siema/rest_v2"
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
      url: "../../../../siema/rest_v2"
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
      url: "../../../../siema/rest_v2"
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
      url: "../../../../siema/rest_v2"
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

    $.each @produto, ()->
      subjects.push(@nome)

    $("#nomeProduto").typeahead({source: subjects})

    $("#btnAddProduto").on 'click', ()=>
      $.each @produto, ()->
        if @nome is $("#nomeProduto").prop('value')
          newRow = document.getElementById('tblProdutos').insertRow()

          td = newRow.insertCell()
          td.innerHTML = '<input name="produtos[]" value=' + @id_produto + ' />' +
                         '<input name=' + @id_produto + '[]" value=' + @id_produto + ' />' +
          td.style = 'display:none;'

          td = newRow.insertCell()
          td.innerHTML = @nome

          td = newRow.insertCell()
          td.innerHTML = @num_onu

          td = newRow.insertCell()
          td.innerHTML = @classe_risco


    # Code to disable inputs on the form

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

    $("#produtoDesc").on 'click', ()->
      if $(this).is(":checked")
        $("#inputTipoSubstancia").attr("disabled","disabled")
        $("#inputValorEstimado").attr("disabled","disabled")
        $("#btnAddProduto").attr("disabled","disabled")
      else
        $("#inputTipoSubstancia").removeAttr("disabled")
        $("#inputValorEstimado").removeAttr("disabled")
        $("#btnAddProduto").removeAttr("disabled")

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
        $("#inputCPFCNPJ").disabled("removeAttr")
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
      $("#divPeriodoObs").prop('style','display:auto;')

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

  # Mask for fields
  $("#inputDataObs").mask("99/99/9999")
  $("#inputHoraObs").mask("99:99")
  $("#inputDataInci").mask("99/99/9999")
  $("#inputHoraInci").mask("99:99")


  $('#inputCompOrigem').maxlength(
    alwaysShow: true,
    threshold: 10,
    warningClass: "label label-info",
    limitReachedClass: "label label-important",
    placement: 'bottom',
    preText: '',
    separator: ' de ',
    postText: ' carácteres.'
  )
