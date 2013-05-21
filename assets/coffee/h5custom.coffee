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

  $(".navbar a").on "click", (event) ->
    event.preventDefault()
    # clean all selection
    if $(@).prop("id") isnt "btn-login"
      $(@).each ->
        $("a").parent().removeClass("active")
      # mark selected option
      $(@).parent().addClass("active")

      if $(@).prop("id") is "btn-map"
        $(".dash").hide()
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

  # configurações da tela de login
  $("#loading_logo").fadeIn 500

  # animar barra de progresso
  setTimeout(->
    $(".loading").fadeOut 700
    $("#navbar").slideDown 300
    H5.Charts.reloadCharts()
  , 2000)

