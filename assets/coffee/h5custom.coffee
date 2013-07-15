$(document).ready ->

  #-------------------------------------------------------------------------
  # BOOTSTRAP
  #-------------------------------------------------------------------------

  $("[rel=tooltip]").tooltip placement: "bottom"

  $(".alert").alert()

  $("select").selectpicker({
    width: '80px'
    size: 'auto'
  })

  #-------------------------------------------------------------------------
  # MAP
  #-------------------------------------------------------------------------

  # Detect whether device supports orientationchange event,
  # otherwise fall back to the resize event.
  supportsOrientationChange = "onorientationchange" of window
  orientationEvent = (if supportsOrientationChange then "orientationchange" else "resize")

  # update chart if orientation or the size of the screen changed
  window.addEventListener orientationEvent, (->
    $( '#map-container' ).width( $( window ).width() )
    $( '#map-container' ).height( $( window ).height() - $('#navbar').height() - 1)
  ), false

  #-------------------------------------------------------------------------
  # CHARTS
  #-------------------------------------------------------------------------

  $(".dropdown-menu input, .dropdown-menu label").click (e) ->
    e.stopPropagation()

  # enable masonry plugin
  $ ->
    $("#charts-content").masonry
      # options
      itemSelector: ".chart"
      animationOptions:
        duration: 1000

  $(".navbar a").on "click", (event) ->
    # clean all selection
    if $(@).prop("id") isnt "btn-login"
      $(@).each ->
        $("a").parent().removeClass("active")
      # mark selected option
      $(@).parent().addClass("active")

      if $(@).prop("id") is "btn-map"
        $(".dash").hide()
        # update size of the map container
        setTimeout(->
          $( '#map-container' ).width( $( window ).width() )
          $( '#map-container' ).height( $( window ).height() - $('#navbar').height() - 1)
        , 1000)
      else if $(@).prop("id") is "btn-charts"
        $(".dash").show()
        # reload charts
        # H5.Charts.reloadCharts()

      $('.nav-collapse').collapse('hide')

  $(".quick-btn a").on "click", (event) ->
    event.preventDefault()

    # clean all selection
    $(@).each ->
      $("a").removeClass "active"
    # mark selected option
    $(@).addClass "active"

    # save the selected option
    H5.Data.state = $(@).prop("id")

    # update map
    H5.Charts.updateMap()

    # reload charts
    H5.Charts.reloadCharts()

  #-------------------------------------------------------------------------
  # MISC
  #-------------------------------------------------------------------------

  #change the case to Letter case, ex: helmuth saatkamp to Helmuth Saatkamp
  String::toProperCase = ->
    @replace /\w\S*/g, (txt) ->
      txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()

  #round numbers function
  roundNumber = (number, digits) ->
    multiple = Math.pow(10, digits)
    rndedNum = Math.round(number * multiple) / multiple
    rndedNum

  # configurações da tela de login
  $("#loading_logo").fadeIn 500

  # animar barra de progresso
  setTimeout(->
    $(".loading").fadeOut 700
    $("#navbar").slideDown 300
    H5.Charts.reloadCharts()
  , 2000)

