L.control.switch = L.Control.extend (
  options:
    collapsed: true
    position: "topright"
    autoZIndex: true
    enableTabs: false
    miscTabsName: "Outros"

  initialize: (baseLayers, overlayers, tabs, options) ->
    L.Util.setOptions this, options
    @_layers = {}
    @_lastZIndex = 0
    @_handlingClick = false

    if tabs
      @_tabs = tabs
      @options.enableTabs = true

    $.each baseLayers, (name, obj) =>
      @_addLayer(obj.layer, name, false, false, null)

    $.each overlayers, (name, obj) =>
      control = if (typeof obj.overlayControl is "boolean") then obj.overlayControl else false
      @_addLayer(obj.layer, name, true, control, obj.tab)

  onAdd: (map) ->
    @_initLayout()
    @_update()

    map
      .on("layeradd", @_onLayerChange, this)
      .on("layerremove", @_onLayerChange, this)

    return @_container

  onRemove: (map) ->
    map
      .off("layeradd", @_onLayerChange)
      .off("layerremove", @_onLayerChange)

  addBaseLayer: (layer, name) ->
    @_addLayer layer, name
    @_update()
    return this

  addOverLayer: (layer, name, overlayControl, tab) ->
    @_addLayer layer, name, true, overlayControl, tab
    @_update()
    return this

  removeLayer: (layer) ->
    id = L.stamp(layer)
    delete @_layers[id]

    @_update()
    return this

  _initLayout: ->
    className = "switch-control-layers"
    container = @_container = L.DomUtil.create("div", "leaflet-bar " + className)

	# Makes this work on IE10 Touch devices by stopping it from firing a mouseout event when the touch is released
    container.setAttribute('aria-haspopup', true)

    unless L.Browser.touch
      L.DomEvent.disableClickPropagation container
      L.DomEvent.on(container, "mousewheel", L.DomEvent.stopPropagation)
    else
      L.DomEvent.on(container, "click", L.DomEvent.stopPropagation)

    form = @_form = L.DomUtil.create("form", className + "-list form-layer-list")

    if @options.collapsed
      L.DomEvent
        .on(container, "mouseover", @_expand, this)
        .on(container, "mouseout", @_collapse, this)

      link = @_layersLink = L.DomUtil.create("a", className + "-toggle", container)
      link.href = "#"
      link.title = "Layers"

      if L.Browser.touch
        L.DomEvent
          .on(link, "click", L.DomEvent.stop)
          .on(link, "click", @_expand, this)
          .on(link, "click", @_activeTab, this)
      else
        L.DomEvent
          .on(link, "mouseover", @_activeTab, this)
          .on(link, "focus", @_expand, this)

      @_map.on("click", @_collapse, this)
    else
      @_expand()

    @_baseLayersList = L.DomUtil.create('div', className + '-base', form)
    unless @options.enableTabs
      @_separator = L.DomUtil.create('div', className + '-separator', form)
    @_overlayersList = L.DomUtil.create('div', className + '-overlayers', form)

    if @options.enableTabs
      @_tabsOverLayers = L.DomUtil.create('ul', 'nav nav-tabs', form)
      $(@_tabsOverLayers).attr 'id', 'tabsOverLayers'
      @_tabsContentOverLayers = L.DomUtil.create('div', 'tab-content', form)
      $(@_tabsContentOverLayers).attr 'id', 'tabsContent'
      @_tabsList = {}

      $.each @_tabs, (tab, obj) =>
        @_createTab(tab, obj)
        if obj.tabs is undefined
          @options.miscTabs = true

      if @options.miscTabs
        obj = {
          icon: "http://" + document.domain + "/siema/assets/img/icons/world.png"
          # name: @options.miscTabsName
        }
        @_createTab(@options.miscTabsName, obj)

    $(container).append form

  _createTab: (tab, obj) ->
    #cria tab
    newTab = L.DomUtil.create('li', '', @_tabsOverLayers)

    #cria tab content :)
    newTabContent = L.DomUtil.create('div', 'tab-pane', @_tabsContentOverLayers)
    $(newTabContent).attr 'id', tab
    @_tabsList[tab] = newTabContent

    newTabName = L.DomUtil.create('a', '', newTab)
    $(newTabName).attr 'id', "tabLink" + tab
    $(newTabName).attr 'href', '#' + tab
    $(newTabName).attr 'data-toggle', 'tab'

    if obj.name
      newTabName.innerHTML =  obj.name
    else
      newTabName.innerHTML =  '<img src=" ' + obj.icon + '" width="22px" height="22px">'

    if obj.selected
      @_selectedTab = newTabName
      @_selectedTabContent = newTabContent

    L.DomEvent
      .on(newTabName, "click", (->
        @_selectedTab = newTabName
        @_selectedTabContent = newTabContent
      ), this)

    return

  _activeTab: ->
    $(@_selectedTab).trigger("click")

  _addLayer: (layer, name, overlayer, overlayControl, tab) ->
    id = L.stamp(layer)

    @_layers[id] =
      layer: layer
      name: name
      overlayer: overlayer
      overlayControl: overlayControl
      tab: tab

    if @options.autoZIndex and layer.setZIndex
      @_lastZIndex++
      layer.setZIndex @_lastZIndex

  _update: ->
    return unless @_container

    @_baseLayersList.innerHTML = ""
    @_overlayersList.innerHTML = ""

    if @options.enableTabs
      $.each @_tabsList, ->
        $(this).html("")

    baseLayersPresent = false
    overlayersPresent = false

    for i of @_layers
      obj = @_layers[i]
      @_addItem obj
      overlayersPresent = overlayersPresent or obj.overlayer
      baseLayersPresent = baseLayersPresent or not obj.overlayer

      unless @options.enableTabs
        @_separator.style.display = (if overlayersPresent and baseLayersPresent then "" else "none")

  _onLayerChange: (e) ->
    obj = @_layers[L.stamp(e.layer)]

    return unless obj

    @_update() unless @_handlingClick

    type = (if obj.overlayer then ((if e.type is "layeradd" then "overlayeradd" else "overlayerremove")) else ((if e.type is "layeradd" then "baselayerchange" else null)))

    @_map.fire(type, obj) if type

  _addItem: (obj) ->
    if obj.overlayer
      if @options.enableTabs
        if obj.tab
          container = @_tabsList[obj.tab]
        else if @options.miscTabs
          container = @_tabsList[@options.miscTabsName]
      else
        container = @_overlayersList
    else
      container = @_baseLayersList

    controlgroup = L.DomUtil.create("div", "control-group", container)
    checked = @_map.hasLayer(obj.layer)

    # layer name
    label = L.DomUtil.create("label", "control-label", controlgroup)
    if ( obj.name.length < 12 and not obj.overlayControl ) or not obj.overlayControl
      if obj.name.length > 21
        name = obj.name.substr(0,21) + "…"
        label.innerHTML = "<abbr title=\"" + obj.name + "\">" + name + "</abbr>"
      else
        label.innerHTML = obj.name
    else
      name = obj.name.substr(0,12) + "…"
      label.innerHTML = "<abbr title=\"" + obj.name + "\">" + name + "</abbr>"

    control = L.DomUtil.create("div", "control", controlgroup)
    toggle = L.DomUtil.create("div", "switch-small", control)
    L.DomUtil.addClass toggle, "baseLayers" unless obj.overlayer
    input = L.DomUtil.create("input", "", toggle)
    if obj.overlayControl
      input.type = "checkbox"
      L.DomUtil.addClass input, "switch-control-layers-selector"

      # insert slider, but exclude from touch devices
      if obj.overlayControl
        slider = L.DomUtil.create("div", "", controlgroup)
        L.DomUtil.addClass slider, "switch-control-layers-slider"
        $(slider).slider
          min: 0
          max: 100
          value: 100
          range: "min"
          slide: (event, ui) ->
            obj.layer.eachLayer( (layer) =>
              if layer.setOpacity
                layer.Opacity (ui.value/100)
              else
                layer.setStyle (
                  fillOpacity: ui.value/100
                  opacity: ui.value/100
                )
            )
    else
      input.type = "radio"
      $(input).attr("name", "leaflet-base-layers")
    input.defaultChecked = checked
    input.layerId = L.stamp(obj.layer)

    # create switch
    $(toggle).bootstrapSwitch()

    $(toggle).on "switch-change", (e, data) =>
      unless obj.overlayer
        $('.baseLayers').bootstrapSwitch('toggleRadioState')
      @_onInputClick input, obj

    return controlgroup

  _onInputClick: (input, obj) ->
    @_handlingClick = true

    if input.checked and not @_map.hasLayer(obj.layer)
      @_map.addLayer obj.layer
    else
      unless input.checked and @_map.hasLayer(obj.layer)
        @_map.removeLayer obj.layer

    @_handlingClick = false

  _expand: ->
    L.DomUtil.addClass @_form, "switch-control-layers-expanded"
    if (H5.Map.minimap)
      H5.Map.minimap.toggleMinimap(false);

  _collapse: ->
    @_form.className = @_form.className.replace(" switch-control-layers-expanded", "")
    if (H5.Map.minimap)
      H5.Map.minimap.toggleMinimap(true);
)
