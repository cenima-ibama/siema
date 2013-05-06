H5.Leaflet = {
  layersList: null
}
# H5.Leaflet.VectorLayer {{{
class H5.Leaflet.VectorLayer
  options:
    fields: ""
    scaleRange: null
    layer: null
    uniqueField: null
    visibleAtScale: true
    autoUpdate: false
    autoUpdateInterval: null
    popupTemplate: null
    popupOptions: {}
    singlePopup: false
    symbology: null
    showAll: false
    symbology: {}

  constructor: (options) ->
    L.setOptions this, options

  # Show this layer on the map provided
  setMap: (map) ->
    return  if map and @options.map
    if map
      @options.map = map
      if @options.scaleRange and @options.scaleRange instanceof Array and @options.scaleRange.length is 2
        z = @options.map.getZoom()
        sr = @options.scaleRange
        @options.visibleAtScale = (z >= sr[0] and z <= sr[1])

  # Set the Popup content for the feature
  _setPopupContent: (feature) ->

    # Store previous Popup content so we can check to see if it changed. If it didn't no sense changing the content as this has an ugly flashing effect.
    previousContent = feature.popupContent
    atts = feature.properties
    popupContent = undefined

    # Check to see if it's a string-based popupTemplate or function
    if typeof @options.popupTemplate is "string"

      # Store the string-based popupTemplate
      popupContent = @options.popupTemplate

      # Loop through the properties and replace mustache-wrapped property names with actual values
      for prop of atts
        re = new RegExp("{" + prop + "}", "g")
        popupContent = popupContent.replace(re, atts[prop])
    else if typeof @options.popupTemplate is "function"

      # It's a function-based popupTempmlate, so just call this function and pass properties
      popupContent = @options.popupTemplate(atts)
    else

      # Ummm, that's all we support. Seeya!
      return

    # Store the Popup content
    feature.popupContent = popupContent

    # Check to see if popupContent has changed and if so setContent
    if feature.popup

      # The Popup is associated with a feature
      if feature.popupContent isnt previousContent
        feature.popup.setContent feature.popupContent

    # The Popup is associated with the layer (singlePopup: true)
    else if @popup and @popup.associatedFeature is feature
      if feature.popupContent isnt previousContent
        @popup.setContent feature.popupContent

  _getFeatureStyle: (feature) ->

    # Create an empty style object to add to, or leave as is if no symbology can be found
    style = {}
    atts = feature.properties

    # Is there a symbology set for this layer?
    if @options.symbology
      switch @options.symbology.type
        when "single"
          # It's a single symbology for all features so just set the key/value pairs in style
          for key of @options.symbology.style
            style[key] = @options.symbology.style[key]
        when "unique"
          # It's a unique symbology. Check if the feature's property value matches that in the symbology and style accordingly
          att = @options.symbology.property
          i = 0
          len = @options.symbology.values.length
          while i < len
            if atts[att] is @options.symbology.values[i].value
              for key of @options.symbology.values[i].style
                style[key] = @options.symbology.values[i].style[key]
            i++
        when "range"
          # It's a range symbology. Check if the feature's property value is in the range set in the symbology and style accordingly
          att = @options.symbology.property
          i = 0
          len = @options.symbology.ranges.length
          while i < len
            if atts[att] >= @options.symbology.ranges[i].range[0] and atts[att] <= @options.symbology.ranges[i].range[1]
              for key of @options.symbology.ranges[i].style
                style[key] = @options.symbology.ranges[i].style[key]
            i++
    return style

  _updatePosition: (feature) ->
    if feature.geometry.type is "Point"
      for i in [0 .. @layer._layers.length]
        if feature.properties[@options.uniqueField]is @layer._layers[i].properties[@options.uniqueField]
          @layer._layers[i].setLatLngs[feature.geometry.coordinates].update()

class H5.Leaflet.PostgisLayer extends H5.Leaflet.VectorLayer
  options:
    url: null
    srid: null
    geomFieldName: "the_geom"
    table: null
    fields: null
    where: null
    limit: null
    uniqueField: null

  constructor: (options) ->

    # Check for required parameters
    i = 0
    len = @_requiredParams.length

    while i < len
      throw new Error("No \"" + @_requiredParams[i] + "\" parameter found.") unless options[@_requiredParams[i]]
      i++

    L.setOptions this, options

    # # Build Query
    # where = (if (@options.where) then encodeURIComponent(@options.where) else null)
    # unless @options.showAll
      # bounds = @options.map.getBounds()
      # sw = bounds.getSouthWest()
      # ne = bounds.getNorthEast()
      # where += (if where.length then " AND " else "")
      # if @options.srid
        # where += @options.geomFieldName + " && st_setsrid(st_makebox2d(st_point(" + sw.lng + "," + sw.lat + "),st_point(" + ne.lng + "," + ne.lat + "))," + @options.srid + ")"
      # else
        # where += "" + @options.geomFieldName + ",4326) && st_setsrid(st_makebox2d(st_point(" + sw.lng + "," + sw.lat + "),st_point(" + ne.lng + "," + ne.lat + "))"
    # @options.where = where

    # Build fields
    @options.fields = ((if @options.fields then @options.fields + "*" else "" )) + ", st_asgeojson(" + @options.geomFieldName + ") as geojson"

    console.log(@options)
    @_show()

  update: ->
    @_getGeoJson()
    # load geojson data
    if not @layer
      throw new Error("No layer founded")
    else
      layer = new L.GeoJson(@geoJson,
        onEachFeature: @_updatePosition
      )

  _requiredParams: ["url", "table"]

  _show: ->
    @_getGeoJson()
    # load geojson data
    if not @layer
      @layer = L.geoJson(@geoJson,
        style: @_getFeatureStyle
        onEachFeature: @_setPopupContent
      )
    else
      layer = L.geoJson(@geoJson,
        onEachFeature: @_updatePosition
      )
    if @options.autoUpdate and @options.autoUpdateInterval
      @_autoUpdateInterval = setInterval(=>
        @_show()
      , @options.autoUpdateInterval)

  _getGeoJson: ->
    #request data from the server
    rest = new H5.PgRest {
      url: @options.url
      table: @options.table
      fields: @options.fields
      parameters: @options.where
      limit: @options.limit
    }

    json = rest.request()

    @geoJson = {}
    @geoJson.features = []
    @geoJson.total = json.length
    @geoJson.type = "FeatureCollection" # Not really necessary, but let's follow the GeoJSON spec for a Feature
    # convert @geoJson to make it look like a GeoJSON FeatureCollection
    i = 0
    len = json.length

    while i < len
      @geoJson.features[i] = {}
      @geoJson.features[i].properties = {}
      for prop of json[i]
        if prop is "geojson"
          @geoJson.features[i].geometry = JSON.parse(json[i].geojson)
        else if prop != "properties"
          @geoJson.features[i].properties[prop] = json[i][prop]
      # Not really necessary, but let's follow the GeoJSON spec for a Feature
      @geoJson.features[i].type = "Feature"
      i++

    json = null
    console.log @geoJson
# }}}
# H5.Leaflet.RapidEyeTMS {{{
class H5.Leaflet.RapidEyeTMS
  options:
    url: null
    table: null
    numberOfLayers: 4

  constructor: (options) ->
    # create object for the tiles
    @listLayers = []
    @layers = {}
    @layerGroup = []
    @count = 1

    # configure object with the options
    L.setOptions this, options

    @_createTMSLayers()
    @_loadGeoJSON()
    @_addToLayerControl()

  _onEachFeature: (feature, layer) ->

    popupContent = "<p>I started out as a GeoJSON " + feature.geometry.type + ", but now I'm a Leaflet vector!</p>"
    popupContent += feature.properties.popupContent  if feature.properties and feature.properties.popupContent

    layer.bindPopup popupContent

    #load TMS on mouseover or click
    layer.on "mouseover click", (e) ->

      # get the tile URL
      tmsUrl = feature.properties.url_tiles + "{z}/{x}/{y}.png"

      # remove border
      layer.setStyle
        fillColor: "transparent"
        stroke: false

      if @_checkLayer(layer._leaflet_id)

        # add tile inside polygon
        @layers[@count].setUrl tmsUrl
        @layers[@count].redraw()

        # add layer id to the list of layers
        @listLayers.push layer._leaflet_id

        # compare if number of tiles is bigger then the
        # number of layers
        @listLayers.shift() if @listLayers.length > @options.numberOfLayers

        # make next tile avaliable
        @count++

        # if the tile number is bigger then the number of layers
        # reset the controller
        @count = 1 if @count > @options.numberOfLayers

    # restore style when leave
    layer.on "mouseout", (e) ->

      # restore style
      layer.setStyle stroke: true

  _createTMSLayers: ->
    # create number of layers to display the tms
    i = 1
    while i <= @options.numberOfLayers
      @layers[i] = new L.TileLayer("",
        minZoom: 3
        maxZoom: 17
        tms: true
      )
      # add layer to list of layers
      @layerGroup.push @layers[i]
      i++

  _checkLayer: (layerId) ->
    # check if the layer is already loaded
      for i in [0 .. @listLayers.length]
        return false if layerId is @listLayers[i]
      return true

  _loadGeoJSON: ->
    #request data from the server
    rest = new H5.PgRest {
      url: @options.url
      table: @options.table
    }
    # load geojson data
    @_vectors = L.geoJson(rest.request(),
      style:
        fillColor: "transparent"
        color: "purple"
        weight: 4

      onEachFeature: @_onEachFeature
    )

  _addToLayerControl: ->
    # create a group of layers and add then to the layers list
    rapidEyeLayer = new L.LayerGroup(@layerGroup)
    rapidEyeLayer.addLayer @_vectors
    if H5.Leaflet.layersList then H5.Leaflet.layersList.addLayer rapidEyeLayer, "RapidEye"
# }}}
# H5.Leaflet.LayerControl {{{
H5.Leaflet.LayerControl = L.Control.extend (
  options:
    collapsed: true
    position: "topright"
    autoZIndex: true

  initialize: (baseLayers, options) ->
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
# }}}
