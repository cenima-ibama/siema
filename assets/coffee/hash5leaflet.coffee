# display stations
processTMS = (data) ->
  onEachFeature = (feature, layer) ->
    popupContent = "<p>I started out as a GeoJSON " + feature.geometry.type + ", but now I'm a Leaflet vector!</p>"
    popupContent += feature.properties.popupContent  if feature.properties and feature.properties.popupContent
    layer.bindPopup popupContent
    layer.on "mouseover click", (e) ->

      # get the tile URL

      # remove border

      # check if the layer is already loaded
      checkLayer = (layerId) ->
        i = 0
        while i < rapidEye.listLayers.length
          return false  if layerId is rapidEye.listLayers[i]
          i++
        true
      tmsUrl = feature.properties.url_tiles + "{z}/{x}/{y}.png"
      layer.setStyle
        fillColor: "transparent"
        stroke: false

      if checkLayer(layer._leaflet_id)

        # add tile inside polygon
        rapidEye.layers[rapidEye.count].setUrl tmsUrl
        rapidEye.layers[rapidEye.count].redraw()

        # add layer id to the list of layers
        rapidEye.listLayers.push layer._leaflet_id

        # compare if number of tiles is bigger then the
        # number of layers
        rapidEye.listLayers.shift()  if rapidEye.listLayers.length > rapidEye.numberOfLayers

        # make next tile avaliable
        rapidEye.count++

        # if the tile number is bigger then the number of layers
        # reset the controller
        rapidEye.count = 1  if rapidEye.count > rapidEye.numberOfLayers


    # restore style when leave
    layer.on "mouseout", (e) ->

      # restore style
      layer.setStyle stroke: true


  # create object for the tiles
  rapidEye = {}
  rapidEye.count = 1
  rapidEye.listLayers = []
  rapidEye.layers = {}
  rapidEye.layerGroup = []
  rapidEye.numberOfLayers = 4

  # create number of layers to display the tms
  i = 1
  while i <= rapidEye.numberOfLayers
    rapidEye.layers[i] = new L.TileLayer("",
      minZoom: 3
      maxZoom: 17
      tms: true
    )

    # add layer to list of layers
    rapidEye.layerGroup.push rapidEye.layers[i]
    i++

  # load geojson data
  rapidEye.geoJson = L.geoJson(data,
    style:
      fillColor: "transparent"
      color: "purple"
      weight: 4

    onEachFeature: onEachFeature
  )

  # create a group of layers and add then to the layers list
  rapidEyeTMS = new L.LayerGroup(rapidEye.layerGroup)
  rapidEyeTMS.addLayer rapidEye.geoJson
  layersList.addLayer rapidEyeTMS, "RapidEye"

H5.LayerControl = L.Control.extend(
  options:
    collapsed: true
    position: "topright"
    autoZIndex: true

  constructor: (baseLayers, options) ->
    L.setOptions this, options
    @_layers = {}
    @_lastZIndex = 0
    @_handlingClick = false
    for i of baseLayers
      @_addLayer baseLayers[i], i

  onAdd: (map) ->
    @_initLayout()
    @_update()
    map.on("layeradd", @_onLayerChange, this).on "layerremove", @_onLayerChange, this
    @_container

  onRemove: (map) ->
    map.off("layeradd", @_onLayerChange).off "layerremove", @_onLayerChange

  addLayer: (layer, name) ->
    @_addLayer layer, name
    @_update()
    this

  removeLayer: (layer) ->
    id = L.stamp(layer)
    delete @_layers[id]

    @_update()
    this

  _initLayout: ->
    className = "leaflet-control-layers"
    container = @_container = L.DomUtil.create("div", className)
    unless L.Browser.touch
      L.DomEvent.disableClickPropagation container
      L.DomEvent.on container, "mousewheel", L.DomEvent.stopPropagation
    else
      L.DomEvent.on container, "click", L.DomEvent.stopPropagation
    form = @_form = L.DomUtil.create("form", className + "-list form-layer-list")
    if @options.collapsed
      L.DomEvent.on(container, "mouseover", @_expand, this).on container, "mouseout", @_collapse, this
      link = @_layersLink = L.DomUtil.create("a", className + "-toggle", container)
      link.href = "#"
      link.title = "Layers"
      if L.Browser.touch
        L.DomEvent.on(link, "click", L.DomEvent.stopPropagation).on(link, "click", L.DomEvent.preventDefault).on link, "click", @_expand, this
      else
        L.DomEvent.on link, "focus", @_expand, this
      @_map.on "movestart", @_collapse, this
    else
      @_expand()
    @_baseLayersList = L.DomUtil.create("div", className + "-base", form)
    container.appendChild form

  _addLayer: (layer, name) ->
    id = L.stamp(layer)
    @_layers[id] =
      layer: layer
      name: name

    if @options.autoZIndex and layer.setZIndex
      @_lastZIndex++
      layer.setZIndex @_lastZIndex

  _update: ->
    return  unless @_container
    @_baseLayersList.innerHTML = ""
    i = undefined
    obj = undefined
    for i of @_layers
      obj = @_layers[i]
      @_addItem obj

  _onLayerChange: (e) ->
    id = L.stamp(e.layer)
    @_update()  if @_layers[id] and not @_handlingClick

  _addItem: (obj) ->
    _this = this
    container = @_baseLayersList
    controlgroup = L.DomUtil.create("div", "control-group", container)
    checked = @_map.hasLayer(obj.layer)
    label = L.DomUtil.create("label", "control-label pull-left", controlgroup)
    label.innerHTML = " " + obj.name
    control = L.DomUtil.create("div", "control pull-right", controlgroup)
    toggle = L.DomUtil.create("div", "switch switch-small", control)
    input = L.DomUtil.create("input", "leaflet-control-layers-selector", toggle)
    input.type = "checkbox"
    input.defaultChecked = checked
    input.layerId = L.stamp(obj.layer)
    $(toggle).bootstrapSwitch()
    $(toggle).on "switch-change", (e, data) ->
      _this._onInputClick input, obj

    controlgroup

  _onInputClick: (input, obj) ->
    @_handlingClick = true
    if input.checked
      @_map.addLayer obj.layer
      @_map.fire "layeradd",
        layer: obj

    else
      @_map.removeLayer obj.layer
      @_map.fire "layerremove",
        layer: obj

    @_handlingClick = false

  _expand: ->
    L.DomUtil.addClass @_container, "leaflet-control-layers-expanded"

  _collapse: ->
    @_container.className = @_container.className.replace(" leaflet-control-layers-expanded", "")
)
bingaerial = new L.BingLayer("AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h",
  type: "Aerial"
  attribution: ""
)
bingroad = new L.BingLayer("AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h",
  type: "Road"
  attribution: ""
)
binghybrid = new L.BingLayer("AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h",
  type: "AerialWithLabels"
  attribution: ""
)
openstreetUrl = "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
openstreetAttribution = ""
openstreet = new L.TileLayer(openstreetUrl,
  maxZoom: 18
  attribution: openstreetAttribution
)
cloudmadeUrl = "http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png"
cloudmadeAttribution = ""
cloudmade = new L.TileLayer(cloudmadeUrl,
  maxZoom: 18
  attribution: cloudmadeAttribution
)
map = new L.Map("map-container",
  center: new L.LatLng(-10.0, -58.0)
  zoom: 6
  layers: [openstreet]
  zoomControl: true
)
alertaLayer = new lvector.PRWSF(
  url: "../painel/rest/"
  geotable: "alerta"
  fields: "objectid"
  srid: 4618
  geomFieldName: "shape"
  showAll: true
  popupTemplate: "<div class=\"iw-content\"><center><h3>{objectid}</h3></center></div>"
  singlePopup: true
  where: "ano = '2013'"
  symbology:
    type: "single"
    vectorOptions:
      fillColor: "#ff0000"
      fillOpacity: 0.6
      weight: 1.2
      color: "#ff0000"
      opacity: 0.8
)
alertaLayer.setMap map
$.getJSON "geojson/rapideye/pampas.json", (data) ->
  processTMS data

layersList = new hash5LayerControl(
  OSM: openstreet
  "Bing Aerial": bingaerial
  "Bing Road": bingroad
  "Bing Hybrid": binghybrid
)

# add layer menu
map.addControl layersList

# add custom attribution
map.attributionControl.setPrefix "Hexgis Hash5"

# add scale
L.control.scale().addTo map
