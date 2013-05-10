$(document).ready ->

  #-------------------------------------------------------------------------
  # BOOTSTRAP
  #-------------------------------------------------------------------------

  $("[rel=tooltip]").tooltip placement: "bottom"

  $(".dropdown-menu input, .dropdown-menu label").click (e) ->
    e.stopPropagation()

  # enable masonry plugin
  $ ->
    $("#charts-content").masonry

      # options
      itemSelector: ".chart"
      animationOptions:
        duration: 1000

  #transformar botões em tablets e smartphones
  updateButtons = ->
    if $(window).width() < 940
      $("#btn-charts").addClass "btn-block"
      $("#btn-map").addClass "btn-block"
    else
      $("#btn-charts").removeClass "btn-block"
      $("#btn-map").removeClass "btn-block"
  updateButtons()

  # configuração dos butão mapa
  $("#btn-map").click ->
    $(this).prop "disabled", true
    $("#btn-charts").prop "disabled", false
    $(".dash").hide()
    $(".nav-collapse").collapse "hide"


  # configuração dos butão de gráficos
  $("#btn-charts").click ->
    $(this).prop "disabled", true
    $("#btn-map").prop "disabled", false
    $(".dash").show()
    $(".nav-collapse").collapse "hide"

  sidebarMapHidden = 1
  $("#sidebar-map").toggleClass "sidebar-map-hidden", 500
  $("#sidebar-map-btn").click ->
    $("#sidebar-map").toggleClass "sidebar-map-hidden", 500
    if sidebarMapHidden
      $(this).children("i").prop "class", "icon-arrow-left"
      sidebarMapHidden--
    else
      $(this).children("i").prop "class", "icon-arrow-right"
      sidebarMapHidden++

  sidebarDashHidden = 1
  $("#sidebar-dash").toggleClass "sidebar-dash-hidden", 500
  $("#sidebar-dash-btn").click ->
    $("#sidebar-dash").toggleClass "sidebar-dash-hidden", 500
    if sidebarDashHidden
      $(this).children("i").prop "class", "icon-arrow-left"
      sidebarDashHidden--
    else
      $(this).children("i").prop "class", "icon-arrow-right"
      sidebarDashHidden++

  # configurações da tela de login
  progressValue = 0
  $("#progress").show "fade", {}, 300
  $("#loading_logo").show "fade", {}, 1000

  # animar barra de progresso
  loading = setInterval(->
    progressValue += 50
    $("#progressbar").css width: progressValue + "%"
    if progressValue >= 100
      window.clearInterval loading
      $("#btn-map").prop "disabled", false
      $("#btn-charts").prop "disabled", true
      $(".loading").fadeOut 700
      $("#navbar").slideDown 300
      H5.Charts.reloadCharts()
  , 1000)

  # Detect whether device supports orientationchange event, otherwise fall back to
  # the resize event.
  supportsOrientationChange = "onorientationchange" of window
  orientationEvent = (if supportsOrientationChange then "orientationchange" else "resize")

  # detectar mudança na resolução ou orientação
  window.addEventListener orientationEvent, (->
    updateButtons()
  ), false

  $(".quick-btn a").on "click", (event) ->
    event.preventDefault()

    # clean all selection
    $(@).each ->
      $("a").removeClass "active"
    # mark selected option
    $(@).addClass "active"

    # save the selected option
    H5.Charts.data.state = $(@).prop("id")

    # update map
    H5.Charts.updateMap()

    # reload charts
    H5.Charts.reloadCharts()

  #change the case to Letter case, ex: helmuth saatkamp to Helmuth Saatkamp
  String::toProperCase = ->
    @replace /\w\S*/g, (txt) ->
      txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()

  #round numbers function
  roundNumber = (number, digits) ->
    multiple = Math.pow(10, digits)
    rndedNum = Math.round(number * multiple) / multiple
    rndedNum

  # precionar o enter ativa busca
  $ ->
    $("input").keydown (e) ->
      if e.keyCode is 13
        setTimeout (->
          $("#submit").click()
        ), 0
