  #-------------------------------------------------------------------------
  # Wizard Modal
  #-------------------------------------------------------------------------

  # List that stores the order on tabs to be accessed, going backwards
  history = []
  # Stores the next tab to be accessed
  collapse=2


  $('#addMeModal').on 'showed', ->
    $(".modal-footer").hide()


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

    #Deletar dados temporários ao fechar o form de cadastro.
    deleteTempData();

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

    nroOcorrencia = $(window.top.form_frame.document.getElementById("comunicado")).val()

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
    rest = new window.parent.H5.Rest (
      url: window.parent.H5.Data.restURL
      table: "tmp_ocorrencia_produto"
      parameters: "nro_ocorrencia%3D" + nroOcorrencia
      restService: "ws_deletequery.php"
    )

    # Clean the temporary polygon table (tmp_pol)
    rest = new window.parent.H5.Rest (
      url: window.parent.H5.Data.restURL
      table: "tmp_pol"
      parameters: "nro_ocorrencia%3D" + nroOcorrencia
      restService: "ws_deletequery.php"
    )

    # Clean the temporary polyline table (tmp_lin)
    rest = new window.parent.H5.Rest (
      url: window.parent.H5.Data.restURL
      table: "tmp_lin"
      parameters: "nro_ocorrencia%3D" + nroOcorrencia
      restService: "ws_deletequery.php"
    )

    # Clean the temporary point table (tmp_pon)
    rest = new window.parent.H5.Rest (
      url: window.parent.H5.Data.restURL
      table: "tmp_pon"
      parameters: "nro_ocorrencia%3D" + nroOcorrencia
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
      btnLogout = document.getElementById("btnSair")
      consultTab = document.getElementById("btn-consult")
      manageTab = document.getElementById("btn-manag")

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

      if isAtual
        #if($("#inputRegistro").prop("value") isnt "")
          # defaultHtml = document.getElementById("defaultHtml")
          # if(defaultHtml.innerHTML is "")
          #   defaultHtml.innerHTML = $("#formLoad").prop("action")
          # action = defaultHtml.innerHTML + "/" + $("#inputRegistro").prop("value")
          # $("#formLoad").prop "action", action

        #   nroOcorrencia = $("#inputRegistro").prop("value")
        #   $("#nroOcorrenciaLoad").val(nroOcorrencia)
        #   $("#formLoad").submit()
        # else
        #   $("#inputRegistro").focus()
        # nroOcorrencia = $("#inputRegistro").prop("value")
        # $("#nroOcorrenciaLoad").val(nroOcorrencia)
        validado = validateUpdate()

        if(validado == "true")
          nroOcorrencia = $("#inputRegistro").prop("value")
          $("#nroOcorrenciaLoad").val(nroOcorrencia)

          showButtonsCadastro()
          $("#formLoad").submit()
          $(@).tab('show')
        else
           $(".modal-footer").show()
           this.href = "#tab" + --collapse;
           history.push(
              tab: "#tab" + 2
              collapse: 2
           )

      else
        $("#formCreate").submit()
        showButtonsCadastro()
        $(@).tab('show')

      # Clean old cells on the temporary table (tmp_ocorrencia_produto)
      rest = new window.parent.H5.Rest (
        url: window.parent.H5.Data.restURL
        table: "tmp_ocorrencia_produto"
        parameters: "date_part\(\'day\'\,now\(\)\-dt_registro\)\>0"
        restService: "ws_deletequery.php"
      )

      # Clean old cells on the temporary table (tmp_pon)
      rest = new window.parent.H5.Rest (
        url: window.parent.H5.Data.restURL
        table: "tmp_pon"
        parameters: "date_part\(\'day\'\,now\(\)\-dt_registro\)\>0"
        restService: "ws_deletequery.php"
      )

      # Clean old cells on the temporary table (tmp_pol)
      rest = new window.parent.H5.Rest (
        url: window.parent.H5.Data.restURL
        table: "tmp_pol"
        parameters: "date_part\(\'day\'\,now\(\)\-dt_registro\)\>0"
        restService: "ws_deletequery.php"
      )
      # Clean old cells on the temporary table (tmp_lin)
      rest = new window.parent.H5.Rest (
        url: window.parent.H5.Data.restURL
        table: "tmp_lin"
        parameters: "date_part\(\'day\'\,now\(\)\-dt_registro\)\>0"
        restService: "ws_deletequery.php"
      )



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
  $("#btnCadastrarCTF").click (event) ->
    event.preventDefault()

    history.push(
      tab: "#tab2"
      collapse: collapse
    )

    this.href = "#tab5"
    collapse = 5

    $(".modal-footer").show()

    $(@).tab('show')

  $("#optionCPF").on 'click',(event) ->
    $("#fieldCPF").prop 'style', ''
    $("#fieldCNPJ").prop 'style', 'display:none;'
    $("#inputCNPJ").val("")

  $("#optionCNPJ").on 'click',(event) ->
    $("#fieldCNPJ").prop 'style', ''
    $("#fieldCPF").prop 'style', 'display:none;'
    $("#inputCPF").val("")

  deleteTempData = ->

    nroOcorrencia = $(window.top.form_frame.document.getElementById("comunicado")).val()

    # Clean the temporary produt table (tmp_ocorrencia_produto)
    rest = new window.parent.H5.Rest (
      url: window.parent.H5.Data.restURL
      table: "tmp_ocorrencia_produto"
      parameters: "nro_ocorrencia%3D" + nroOcorrencia
      restService: "ws_deletequery.php"
    )

    # Clean the temporary polygon table (tmp_pol)
    rest = new window.parent.H5.Rest (
      url: window.parent.H5.Data.restURL
      table: "tmp_pol"
      parameters: "nro_ocorrencia%3D" + nroOcorrencia
      restService: "ws_deletequery.php"
    )

    # Clean the temporary polyline table (tmp_lin)
    rest = new window.parent.H5.Rest (
      url: window.parent.H5.Data.restURL
      table: "tmp_lin"
      parameters: "nro_ocorrencia%3D" + nroOcorrencia
      restService: "ws_deletequery.php"
    )

    # Clean the temporary point table (tmp_pon)
    rest = new window.parent.H5.Rest (
      url: window.parent.H5.Data.restURL
      table: "tmp_pon"
      parameters: "nro_ocorrencia%3D" + nroOcorrencia
      restService: "ws_deletequery.php"
    )

  validateUpdate = ->
    nroOcorrencia = $("#inputRegistro").prop("value")
    erroMsg = ""
    validado = false

    $.ajax({
      url:"http://localhost/siema/index.php/form/validateUpdate",
      data: {id: nroOcorrencia},
      async: false,
      type: "POST",
      dataType: "json",
      success: (result) ->
        validado = result.status
        erroMsg = result.mensagem
      error: ->
        validado = false
        erroMsg = "A requisição falhou. Não foi possível validar a solicitação."
    })

    #Show erro message.
    if validado == "false"
      $("#divErrorUpdate").show()
      $("#divErrorUpdate").html(erroMsg)
      $("#divErrorUpdate").fadeOut(8500)

    return validado;

  showButtonsCadastro = ->
    $("#submit").show()
    $("#modalBtnNext").hide()
    $("#modalBtnBack").hide()
    $("#modalBtnCancel").show()