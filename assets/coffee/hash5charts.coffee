google.load "visualization", "1",
  packages: ["corechart"]

google.load "visualization", "1",
  packages: ["gauge"]

class Hash5Charts

  constructor: (options) ->
    defaultOptions =
      type: null
      container: null
      period: 1
      started: true
      title: ""
      defaultClass: ""
      selects: undefined
      resizing: 0
      buttons:
        minusplus: false
        arrows: false
        minimize: false
        maximize: false
        close: false
    # configure object with the options
    @options = $.extend(defaultOptions, options)

  createContainer: ->
    container = document.getElementById(@options.container)
    html = "<div class=\"chart-header\">"
    html += "<div class=\"btn-group chart-icon btn-left\"></div>"
    html += "<h2>" + @options.title + "</h2>"
    html += "<div class=\"btn-group chart-icon btn-right\"></div></div>"
    html += "<div id=\"chart-" + @options.container + "\" class=\"chart-content\"></div>"

    $(container).append html

    # add minus and plus controllers
    if @options.buttons.minusplus
      # add break
      html = "<span class=\"break\"></span>"
      $(container).children().children("h2").prepend html
      # add buttons
      html = "<button id=\"" + @options.container + "-btn-minus\" class=\"btn\"> <i class=\"icon-minus\"></i> </button>"
      html += "<button id=\"" + @options.container + "-btn-plus\" class=\"btn\"> <i class=\"icon-plus\"></i> </button>"
      $(container).children().children(".btn-left").append html
      # associate buttons
      @delBtn = document.getElementById(@options.container + "-btn-minus")
      @addBtn = document.getElementById(@options.container + "-btn-plus")

    else if @options.buttons.arrows
      # add break
      html = "<span class=\"break\"></span>"
      $(container).children().children("h2").prepend html
      # add buttons
      html = "<button id=\"" + @options.container + "-btn-left\" class=\"btn\"> <i class=\"icon-arrow-left\"></i> </button>"
      html += "<button id=\"" + @options.container + "-btn-right\" class=\"btn\"> <i class=\"icon-arrow-right\"></i> </button>"
      $(container).children().children(".btn-left").append html
      # associate buttons
      @leftBtn = document.getElementById(@options.container + "-btn-left")
      @rightBtn = document.getElementById(@options.container + "-btn-right")

    else if @options.selects?
      # add break
      html = "<span class=\"break\"></span>"
      $(container).children().children("h2").prepend html
      # create form
      html = "<form name=\"form-" + @options.container + "\" class=\"form-inline\" action=\"\">"

      $.each @options.selects, (name, data) =>
        html += "<select id=\"slct-" + name + "\" class=\"input-mini\" name=\"" + name + "\">"
        $.each data, (key, value) ->
          html += "<option value=" + key + ">" + value + "</option>"
        html += "</select>"

      $(container).children().children(".btn-left").append html
      $(container).children().children(".btn-left").removeClass "btn-group"

      $.each @options.selects, (name, data) =>
        @[name + "Slct"] = document["form-" + @options.container][name]
        @enableSelect("#slct-" + name + "")

    if @options.buttons.minimize
      html = "<button id=\"" + @options.container + "-min\" class=\"btn btn-minimize\"><i class=\"icon-chevron-up\"></i></button>"
      $(container).children().children(".btn-right").append html
      @minBtn = document.getElementById(@options.container + "-min")
      @enableMinimize(container)
    if @options.buttons.maximize
      html = "<button id=\"" + @options.container + "-max\" class=\"btn btn-maximize\"><i class=\"icon-resize-full\"></i></button>"
      $(container).children().children(".btn-right").append html
      @maxBtn = document.getElementById(@options.container + "-max")
      @enableMaximize(container)
    if @options.buttons.close
      html = "<button id=\"" + @options.container + "-close\" class=\"btn btn-close\"><i class=\"icon-remove\"></i></button>"
      $(container).children().children(".btn-right").append html
      @closeBtn = document.getElementById(@options.container + "-close")
      @enableClose(container)

  createMinimalContainer: ->
    container = document.getElementById(@options.container)
    html = "<div id=\"chart-" + @options.container + "\" class=\"chart-content-small\"></div>"
    $(container).append html

  changeTitle: (title) ->
    container = $("#" + @options.container + " h2")
    container.html(title)
    if @options.buttons.arrows or @options.buttons.minusplus or @options.selects?
      html = "<span class=\"break\"></span>"
      container.prepend html

  enableMinimize: (container) ->
    $(@minBtn).on "click", (event) =>
      event.preventDefault()
      $content = $(container).children().next(".chart-content")
      if $content.is(":visible")
        $(@minBtn).children().removeClass("icon-chevron-up").addClass "icon-chevron-down"
        if @options.buttons.minusplus
          $(@addBtn).prop "disabled", true
          $(@delBtn).prop "disabled", true
        else if @options.buttons.arrows
          $(@leftBtn).prop "disabled", true
          $(@rightBtn).prop "disabled", true
      else
        $(@minBtn).children().removeClass("icon-chevron-down").addClass "icon-chevron-up"
        if @options.buttons.minusplus
          $(@addBtn).prop "disabled", false
          $(@delBtn).prop "disabled", false
        else if @options.buttons.arrows
          $(@leftBtn).prop "disabled", false
          $(@rightBtn).prop "disabled", false
      $content.slideToggle()

  enableMaximize: (container) ->
    $(@maxBtn).on "click", (event) =>
      event.preventDefault()

      $content = $(container).children().next(".chart-content")
      $content.toggleClass "chart-content-overlay"
      $content.hide()
      $content.fadeToggle 500

      if $(@maxBtn).children()[0].className is 'icon-resize-full'
        @defaultClass = $(container)[0].className
        $(@minBtn).prop "disabled", true
        $(@closeBtn).prop "disabled", true
        $(@maxBtn).children().prop "class", "icon-resize-small"
        $("#navbar").hide()
      else
        $(@minBtn).prop "disabled", false
        $(@closeBtn).prop "disabled", false
        $(@maxBtn).children().prop "class", "icon-resize-full"
        $("#navbar").show()

      $(container).toggleClass @defaultClass
      $(container).toggleClass "chart-overlay"
      @drawChart()

  enableClose: (container) ->
    $(@closeBtn).on "click", (event) =>
      event.preventDefault()
      $(container).hide("slide",{},'600')

  enableSelect: (container) ->
    $(container).on "change", (event) =>
      @drawChart()

class Hash5GoogleCharts extends Hash5Charts

  dataTable: ->
    @data = new google.visualization.DataTable()

  createChart: ->
    # setup new chart
    if @options.type is "Gauge"
      @chart = new google.visualization.Gauge(
        document.getElementById("chart-" + @options.container)
      )
    else
      @chart = new google.visualization[@options.type + "Chart"](
        document.getElementById("chart-" + @options.container)
      )
    # only init one time
    @options.started = false
    @detectScreenChanges()

  detectScreenChanges: ->
    # Detect whether device supports orientationchange event,
    # otherwise fall back to the resize event.
    supportsOrientationChange = "onorientationchange" of window
    orientationEvent = (if supportsOrientationChange then "orientationchange" else "resize")

    # update chart if orientation or the size of the screen changed
    window.addEventListener orientationEvent, (=>
      setTimeout =>
        @drawChart()
      , 250
    ),false

class Hash5Knobs extends Hash5Charts

  createKnob: ->
    container = document.getElementById(@options.container)
    html = "<div class=\"left\">"
    html+= "<input type=\"text\" class=\"dial\">"
    html+= "</div>"
    html+= "<div class=\"right\">"
    html+= "</div>"
    $(container).append html
    @insertKnob(container)

  updateKnob: (value, name) ->
    container = document.getElementById(@options.container)
    info = $(container).children(".right")
    info.html("<strong>" + value + "</strong> " + name + "")
    @animateKnob(value)

  animateKnob: (val) ->
    container = document.getElementById(@options.container)
    dial = $(container).children().children('input')
    # $(value: -100).animate
    #   value: val,
    #     duration: 2000
    #     easing: "easeOutSine"
    #     step: ->
    #       dial.val(Math.ceil(@value)).trigger "change"
    dial.val(Math.ceil(val)).trigger "change"

  insertKnob: (container) ->
    dial = $(container).children().children('input')
    dial.knob
      'min':-100
      'max':100
      'bgColor': "#EDEDED"
      'angleOffset':-125
      'angleArc':250
      'readOnly': true
      'width': 60
      'height': 60
      'thickness': 0.5
      'displayInput': false
      draw: ->
        value = this.val()
        _min = this.o.min
        _max = this.o.max
        if _min <= value <= _min*0.3 then color = pusher.color("#67C2EF")
        else if _min*0.3 < value <= _max*0.3 then color = pusher.color("#CBE968")
        else if _max*0.3 < value <= _max*0.7 then color = pusher.color("#FABB3D")
        else if _max*0.7 < value <=  _max*0.9 then color = pusher.color("#FA603D")
        else color = pusher.color("#FF5454")
        this.o.fgColor = color.html()
