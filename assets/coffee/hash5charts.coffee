google.load "visualization", "1",
  packages: ["corechart"]

google.load "visualization", "1",
  packages: ["gauge"]

H5.Charts = {
  chartList: null
}

class H5.Charts.Container

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
    @_container = document.getElementById(@options.container)

    chartHeader = document.createElement("div")
    chartHeader.className = "chart-header"
    @_chartHeader = chartHeader

    chartTitle = document.createElement("h2")
    chartTitle.innerHTML = @options.title
    @_chartTitle = chartTitle

    leftCtrl = document.createElement("div")
    leftCtrl.className = "btn-group chart-icon btn-left"
    @_leftCtrl = leftCtrl

    rightCtrl = document.createElement("div")
    rightCtrl.className = "btn-group chart-icon btn-right"
    @_rightCtrl = rightCtrl

    chartContent = document.createElement("div")
    chartContent.id = "chart-" + @options.container
    chartContent.className = "chart-content"
    @_chartContent = chartContent

    $(@_chartHeader).append @_leftCtrl, @_chartTitle, @_rightCtrl
    $(@_container).append @_chartHeader, @_chartContent

    pipeline = "<span class=\"break\"></span>"
    # add minus and plus controllers
    if @options.buttons.minusplus
      # add break
      $(@_chartTitle).prepend pipeline
      # add buttons
      delBtn = document.createElement("button")
      delBtn.id = @options.container + "-btn-minus"
      delBtn.className = "btn"
      @_delBtn = delBtn

      delIcon = document.createElement("i")
      delIcon.className = "icon-minus"
      @_delIcon = delIcon
      $(@_delBtn).append @_delIcon

      addBtn = document.createElement("button")
      addBtn.id = @options.container + "-btn-plus"
      addBtn.className = "btn"
      @_addBtn = addBtn

      addIcon = document.createElement("i")
      addIcon.className = "icon-plus"
      @_addIcon = addIcon
      $(@_addBtn).append @_addIcon

      $(@_leftCtrl).append @_delBtn, @_addBtn

    else if @options.buttons.arrows
      # add break
      $(@_chartTitle).prepend pipeline
      # right buttons
      leftBtn = document.createElement("button")
      leftBtn.id = @options.container + "-btn-left"
      leftBtn.className = "btn"
      @_leftBtn = leftBtn

      leftIcon = document.createElement("i")
      leftIcon.className = "icon-arrow-left"
      @_leftIcon = leftIcon
      $(@_leftBtn).append @_leftIcon

      rightBtn = document.createElement("button")
      rightBtn.id = @options.container + "-btn-right"
      rightBtn.className = "btn"
      @_rightBtn = rightBtn

      rightIcon = document.createElement("i")
      rightIcon.className = "icon-arrow-right"
      @_rightIcon = rightIcon
      $(@_rightBtn).append @_rightIcon

      $(@_leftCtrl).append @_leftBtn, @_rightBtn

    else if @options.selects?
      # add break
      $(@_chartTitle).prepend pipeline
      # create form
      formBtn = document.createElement("form")
      formBtn.name = "form-" + @options.container
      formBtn.className = "form-inline"
      @_formBtn = formBtn

      $.each @options.selects, (name, options) =>
        select = "<select id=\"" + name + "Slct\" class=\"input-mini\" name=\"" + name + "\">"
        $.each options, (value, key) ->
          select += "<option value=" + value + ">" + key + "</option>"
        select += "</select>"
        $(@_formBtn).append select

      $(@_leftCtrl).append @_formBtn
      $(@_leftCtrl).removeClass "btn-group"

      $.each @options.selects, (name, data) =>
        @["_" + name + "Slct"] = document["form-" + @options.container][name]
        @enableSelect("#" + name + "Slct")

    if @options.buttons.minimize

      # add minimize button
      minBtn = document.createElement("button")
      minBtn.id = @options.container + "-btn-minimize"
      minBtn.className = "btn"
      @_minBtn = minBtn

      minIcon = document.createElement("i")
      minIcon.className = "icon-chevron-up"
      @_minIcon = minIcon
      $(@_minBtn).append @_minIcon

      $(@_rightCtrl).append @_minBtn

      @enableMinimize()

    if @options.buttons.maximize

      # add minimize button
      maxBtn = document.createElement("button")
      maxBtn.id = @options.container + "-btn-maximize"
      maxBtn.className = "btn"
      @_maxBtn = maxBtn

      maxIcon = document.createElement("i")
      maxIcon.className = "icon-resize-full"
      @_maxIcon = maxIcon
      $(@_maxBtn).append @_maxIcon

      $(@_rightCtrl).append @_maxBtn

      @enableMaximize()

    if @options.buttons.close

      # add minimize button
      closeBtn = document.createElement("button")
      closeBtn.id = @options.container + "-btn-close"
      closeBtn.className = "btn"
      @_closeBtn = closeBtn

      closeIcon = document.createElement("i")
      closeIcon.className = "icon-remove"
      @_closeIcon = closeIcon
      $(@_closeBtn).append @_closeIcon

      $(@_rightCtrl).append @_closeBtn

      @enableClose()

  createMinimalContainer: ->
    @_container = document.getElementById(@options.container)

    chartContent = document.createElement("div")
    chartContent.id = "chart-" + @options.container
    chartContent.className = "chart-content-small"
    @_chartContent = chartContent

    $(@_container).append @_chartContent

  changeTitle: (title) ->
    $(@_chartTitle).html(title)
    if @options.buttons.arrows or @options.buttons.minusplus or @options.selects?
      pipeline = "<span class=\"break\"></span>"
      $(@_chartTitle).prepend pipeline

  enableMinimize: ->
    $(@_minBtn).on "click", (event) =>
      event.preventDefault()

      if $(@_chartContent).is(":visible")
        @_minIcon.className = "icon-chevron-down"
        if @options.buttons.minusplus
          $(@_addBtn).prop "disabled", true
          $(@_delBtn).prop "disabled", true
        else if @options.buttons.arrows
          $(@_leftBtn).prop "disabled", true
          $(@_rightBtn).prop "disabled", true
      else
        @_minIcon.className = "icon-chevron-up"
        if @options.buttons.minusplus
          $(@_addBtn).prop "disabled", false
          $(@_delBtn).prop "disabled", false
        else if @options.buttons.arrows
          $(@_leftBtn).prop "disabled", false
          $(@_rightBtn).prop "disabled", false
      $(@_chartContent).slideToggle()

  enableMaximize: ->
    $(@_maxBtn).on "click", (event) =>
      event.preventDefault()

      if @_maxIcon.className is "icon-resize-full"
        @defaultClass = @_container.className
        $(@_minBtn).prop "disabled", true
        $(@_closeBtn).prop "disabled", true
        @_maxIcon.className = "icon-resize-small"
        $("#navbar").hide()
      else
        $(@_minBtn).prop "disabled", false
        $(@_closeBtn).prop "disabled", false
        @_maxIcon.className = "icon-resize-full"
        $("#navbar").show()

      $(@_container).toggleClass @defaultClass
      $(@_container).toggleClass "chart-overlay"

      $(@_chartContent).toggleClass "chart-content-overlay"
      $(@_chartContent).hide()
      $(@_chartContent).fadeToggle 500

      @drawChart()

  enableClose: ->
    $(@_closeBtn).on "click", (event) =>
      event.preventDefault()
      $(@_container).hide("slide", {}, 600)

  enableSelect: (select) ->
    $(select).on "change", (event) =>
      @drawChart()

class H5.Charts.GoogleCharts extends H5.Charts.Container

  dataTable: ->
    @data = new google.visualization.DataTable()

  createChart: ->
    # setup new chart
    if @options.type is "Gauge"
      @chart = new google.visualization.Gauge(
        @_chartContent
      )
    else
      @chart = new google.visualization[@options.type + "Chart"](
        @_chartContent
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
      @drawChart()
    ),false

class H5.Charts.SmallContainer

  constructor: (options) ->
    defaultOptions =
      type: null
      container: null
      title: ""
      popover: false
    # configure object with the options
    @options = $.extend(defaultOptions, options)

  createContainer: ->
    @_container = document.getElementById(@options.container)

    leftCtrl = document.createElement("div")
    leftCtrl.className = "left"
    @_leftCtrl = leftCtrl

    rightCtrl = document.createElement("div")
    rightCtrl.className = "right"
    @_rightCtrl = rightCtrl

    $(@_container).append @_leftCtrl, @_rightCtrl

    if @options.popover
      $(@_container).addClass("popover-" + @options.container)
      @createPopover()

  createPopover: ->
    placement = "bottom"
    trigger = "hover"
    html = true
    $(".popover-" + @options.container).popover
      placement: placement
      delay: {show: 700, hide: 300}
      content: "<span>" + @options.popover + "</span>"
      trigger: trigger
      html: html

class H5.Charts.Knobs extends H5.Charts.SmallContainer

  createContainer: ->
    super
    dial = document.createElement("input")
    dial.type = "text"
    dial .className = "dial"
    @_dial = dial

    $(@_leftCtrl).append @_dial

    @createChart()

  createChart: ->
    $(@_dial).knob(
      min:-100
      max:100
      bgColor: "#DEDEDE"
      fgColor: "#DEDEDE"
      angleOffset:-125
      angleArc:250
      readOnly: true
      width: 58
      height: 58
      thickness: 0.5
      displayInput: false
      color: "alert"
      draw: ->
        value = @val()
        _min = @o.min
        _max = @o.max
        if @color is "coldtohot"
          if _min <= value <= _min*0.3 then color = pusher.color("#67C2EF")
          else if _min*0.3 < value <= _max*0.3 then color = pusher.color("#CBE968")
          else if _max*0.3 < value <= _max*0.7 then color = pusher.color("#FABB3D")
          else if _max*0.7 < value <=  _max*0.9 then color = pusher.color("#FA603D")
          else color = pusher.color("#FF5454")
        else
          if value <= 0 then color = pusher.color("#D0FC3F")
          else if 0 < value <= _max*0.6 then color = pusher.color("#FCAC0A")
          else color = pusher.color("#FC2121")
        this.o.fgColor = color.html()
    )
    $(@_dial).val(0).trigger "change"

  updateInfo: (value) ->
    $(@_rightCtrl).html("<strong>" + value + "%</strong><br/> " + @options.title)
    @updateChart(parseFloat(value))

  updateChart: (total) ->
    dial = $(@_leftCtrl).find('.dial')
    $(value: dial.val()).animate
      value: total,
        duration: 2000
        easing: "easeOutSine"
        step: ->
          dial.val(Math.floor @value).trigger "change"

class H5.Charts.Sparks extends H5.Charts.SmallContainer

  createContainer: ->
    super
    spark = document.createElement("div")
    spark.className = "minichart"
    @_spark = spark

    $(@_leftCtrl).append @_spark

  updateInfo: (data, value) ->
    $(@_rightCtrl).html("<strong>" + value + "</strong><br /> " + @options.title)
    @updateChart(data)

  updateChart: (data) ->
    $(@_spark).sparkline data,
      width: 58 #Width of the chart
      height: 62 #Height of the chart
      lineColor: "#2FABE9" #Used by line to specify the colour of the line drawn
      fillColor: "#67C2EF" #Specify the colour used to fill the area under the graph
      spotColor: "#CBE968" #The CSS colour of the final value marker.
      maxSpotColor: "#FF5454" #The CSS colour of the marker displayed for the maximum value.
      minSpotColor: "#67C2EF" #The CSS colour of the marker displayed for the mimum value.
      spotRadius: 1.5 #Radius of all spot markers.
      lineWidth: 1 #In pixels (default: 1)
