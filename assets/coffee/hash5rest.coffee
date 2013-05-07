###
@preserve Copyright (c) 2013, Jason Sanford
Leaflet Vector Layers is a library for showing geometry objects
from multiple geoweb services in a Leaflet map
###

#global H5.Leaflet
((root) ->
  root.H5.Leaflet =
    VERSION: "1.4.0"
    noConflict: ->
      root.H5.Leaflet = @_original
      this

    _original: root.H5.Leaflet
) this

# * H5.Leaflet.Layer is a base class for rendering vector layers on a Leaflet map. It's inherited by AGS, A2E, CartoDB, GeoIQ, etc.
H5.Leaflet.Layer = L.Class.extend(

  # Default options for all layers
  options:
    fields: ""
    scaleRange: null
    map: null
    uniqueField: null
    visibleAtScale: true
    dynamic: false
    autoUpdate: false
    autoUpdateInterval: null
    popupTemplate: null
    popupOptions: {}
    singlePopup: false
    symbology: null
    showAll: false

  initialize: (options) ->
    L.Util.setOptions this, options

  # Show this layer on the map provided
  setMap: (map) ->
    return  if map and @options.map
    if map
      @options.map = map
      if @options.scaleRange and @options.scaleRange instanceof Array and @options.scaleRange.length is 2
        z = @options.map.getZoom()
        sr = @options.scaleRange
        @options.visibleAtScale = (z >= sr[0] and z <= sr[1])
      @_show()
    else if @options.map
      @_hide()
      @options.map = map


  # Get the map (if any) that the layer has been added to
  getMap: ->
    @options.map

  options: (options) ->
    L.Util.setOptions this, options

  _show: ->
    @_addIdleListener()
    @_addZoomChangeListener()  if @options.scaleRange and @options.scaleRange instanceof Array and @options.scaleRange.length is 2
    if @options.visibleAtScale
      if @options.autoUpdate and @options.autoUpdateInterval
        me = this
        @_autoUpdateInterval = setInterval(->
          me._getFeatures()
        , @options.autoUpdateInterval)
      @options.map.fire("moveend").fire "zoomend"

  _hide: ->
    @options.map.off "moveend", @_idleListener  if @_idleListener
    @options.map.off "zoomend", @_zoomChangeListener  if @_zoomChangeListener
    clearInterval @_autoUpdateInterval  if @_autoUpdateInterval
    @_clearFeatures()
    @_lastQueriedBounds = null
    @_gotAll = false  if @_gotAll


  #
  # Hide the vectors in the layer. This might get called if the layer is still on but out of scaleRange.
  #
  _hideVectors: ->

    # TODO: There's probably an easier way to first check for "singlePopup" option then just remove the one
    #       instead of checking for "assocatedFeatures"

    for i in [0 .. @_vectors.length]
      if @_vectors[i].vector
        @options.map.removeLayer @_vectors[i].vector
        if @_vectors[i].popup
          @options.map.removeLayer @_vectors[i].popup
        else
          if @popup and @popup.associatedFeature and @popup.associatedFeature is @_vectors[i]
          @options.map.removeLayer @popup
          @popup = null
      if @_vectors[i].vectors and @_vectors[i].vectors.length
        for j in [0 .. @_vectors[i].vectors.length]
          @options.map.removeLayer @_vectors[i].vectors[j]
          if @_vectors[i].vectors[j].popup
            @options.map.removeLayer @_vectors[i].vectors[j].popup
          else if @popup and @popup.associatedFeature and @popup.associatedFeature is @_vectors[i]
            @options.map.removeLayer @popup
            @popup = null


  # Show the vectors in the layer. This might get called if the layer is on and came back into scaleRange.
  _showVectors: ->
    for i in [0 .. @_vectors.length]
      if @_vectors[i].vector
        @options.map.addLayer @_vectors[i].vector
      if @_vectors[i].vectors and @_vectors[i].vectors.length
        for j in [0 .. @_vectors[i].vectors.length]
          @options.map.addLayer @_vectors[i].vectors[j]

  # Hide the vectors, then empty the vectory holding array
  _clearFeatures: ->
    # TODO - Check to see if we even need to hide these before we remove them from the DOM
    @_hideVectors()
    @_vectors = []

  # Add an event hanlder to detect a zoom change on the map
  _addZoomChangeListener: ->

    # "this" means something different inside the on method.
    @_zoomChangeListener = @_zoomChangeListenerTemplate()
    @options.map.on "zoomend", @_zoomChangeListener, this

  _zoomChangeListenerTemplate: ->

    # Whenever the map's zoom changes, check the layer's visibility (this.options.visibleAtScale)
    return => @_checkLayerVisibility()


  # This gets fired when the map is panned or zoomed
  _idleListenerTemplate: ->
    return =>
      if @options.visibleAtScale

        # Do they use the showAll parameter to load all features once?
        if @options.showAll

          # Have we already loaded these features
          unless @_gotAll

            # Grab the features and note that we've already loaded them (no need to _getFeatures again
            @_getFeatures()
            @_gotAll = true
        else
          @_getFeatures()


  # Add an event hanlder to detect an idle (pan or zoom) on the map
  _addIdleListener: ->

    # "this" means something different inside the on method. Assign it to "me".
    @_idleListener = @_idleListenerTemplate()

    # Whenever the map idles (pan or zoom) get the features in the current map extent
    @options.map.on "moveend", @_idleListener, this


  # Get the current map zoom and check to see if the layer should still be visible
  _checkLayerVisibility: ->

    # Store current visibility so we can see if it changed
    visibilityBefore = @options.visibleAtScale

    # Check current map scale and see if it's in this layer's range
    z = @options.map.getZoom()
    sr = @options.scaleRange
    @options.visibleAtScale = (z >= sr[0] and z <= sr[1])

    # Check to see if the visibility has changed
    if visibilityBefore isnt @options.visibleAtScale
      # It did, hide or show vectors
      this[(if @options.visibleAtScale then "_showVectors" else "_hideVectors")]()

    # Check to see if we need to set or clear any intervals for auto-updating layers
    if visibilityBefore and not @options.visibleAtScale and @_autoUpdateInterval
      clearInterval @_autoUpdateInterval
    else if not visibilityBefore and @options.autoUpdate and @options.autoUpdateInterval
      @_autoUpdateInterval = setInterval(=>
        @_getFeatures()
      , @options.autoUpdateInterval)


  # Set the Popup content for the feature
  _setPopupContent: (feature) ->

    # Store previous Popup content so we can check to see if it changed. If it didn't no sense changing the content as this has an ugly flashing effect.
    previousContent = feature.popupContent

    # GeoJSON calls them properties.
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


  # Show the feature's (or layer's) Popup
  _showPopup: (feature, event) ->

    # Popups on Lines and Polygons are opened slightly different, make note of it
    isLineOrPolygon = event.latlng

    # Set the popupAnchor if a marker was clicked
    unless isLineOrPolygon
      L.Util.extend @options.popupOptions,
        offset: event.target.options.icon.options.popupAnchor

    # Create a variable to hold a reference to the object that owns the Popup so we can show it later
    ownsPopup = undefined

    # If the layer isn't set to show a single Popup
    if not @options.singlePopup
      # Create a Popup and store it in the feature
      feature.popup = new L.Popup(@options.popupOptions, feature.vector)
      ownsPopup = feature
    else
      if @popup
        # If the layer already has an Popup created, close and delete it
        @options.map.removeLayer @popup
        @popup = null

      # Create a new Popup
      @popup = new L.Popup(@options.popupOptions, feature.vector)

      # Store the associated feature reference in the Popup so we can close and clear it later
      @popup.associatedFeature = feature
      ownsPopup = this

    ownsPopup.popup.setLatLng (if isLineOrPolygon then event.latlng else event.target.getLatLng())
    ownsPopup.popup.setContent feature.popupContent
    @options.map.addLayer ownsPopup.popup


  # Optional click event
  _fireClickEvent: (feature, event) ->
    @options.clickEvent feature, event


  # Get the appropriate Google Maps vector options for this feature
  _getFeatureStyle: (feature) ->

    # Create an empty vectorOptions object to add to, or leave as is if no symbology can be found
    vectorOptions = {}

    #GeoJSON calls them properties.
    atts = feature.properties

    # Is there a symbology set for this layer?
    if @options.symbology
      switch @options.symbology.type
        when "single"

          # It's a single symbology for all features so just set the key/value pairs in vectorOptions
          for key of @options.symbology.vectorOptions
            vectorOptions[key] = @options.symbology.vectorOptions[key]
            if vectorOptions.title
              for prop of atts
                re = new RegExp("{" + prop + "}", "g")
                vectorOptions.title = vectorOptions.title.replace(re, atts[prop])
        when "unique"

          # It's a unique symbology. Check if the feature's property value matches that in the symbology and style accordingly
          att = @options.symbology.property
          i = 0
          len = @options.symbology.values.length

          while i < len
            if atts[att] is @options.symbology.values[i].value
              for key of @options.symbology.values[i].vectorOptions
                vectorOptions[key] = @options.symbology.values[i].vectorOptions[key]
                if vectorOptions.title
                  for prop of atts
                    re = new RegExp("{" + prop + "}", "g")
                    vectorOptions.title = vectorOptions.title.replace(re, atts[prop])
            i++
        when "range"

          # It's a range symbology. Check if the feature's property value is in the range set in the symbology and style accordingly
          att = @options.symbology.property
          i = 0
          len = @options.symbology.ranges.length

          while i < len
            if atts[att] >= @options.symbology.ranges[i].range[0] and atts[att] <= @options.symbology.ranges[i].range[1]
              for key of @options.symbology.ranges[i].vectorOptions
                vectorOptions[key] = @options.symbology.ranges[i].vectorOptions[key]
                if vectorOptions.title
                  for prop of atts
                    re = new RegExp("{" + prop + "}", "g")
                    vectorOptions.title = vectorOptions.title.replace(re, atts[prop])
            i++
    vectorOptions

  # Check to see if any attributes have changed
  _getPropertiesChanged: (oldAtts, newAtts) ->
    changed = false
    for key of oldAtts
      changed = true  unless oldAtts[key] is newAtts[key]
    changed

  # Check to see if a particular property changed
  _getPropertyChanged: (oldAtts, newAtts, property) ->
    (oldAtts[property] isnt newAtts[property])


  # Check to see if the geometry has changed
  _getGeometryChanged: (oldGeom, newGeom) ->

    # TODO: make this work for points, linestrings and polygons
    changed = false

    # For now only checking for point changes
    if not oldGeom.coordinates[0] is newGeom.coordinates[0] and oldGeom.coordinates[1] is newGeom.coordinates[1]
      changed = true
    return changed

  _makeJsonpRequest: (url) ->
    head = document.getElementsByTagName("head")[0]
    script = document.createElement("script")
    script.type = "text/javascript"
    script.src = url
    head.appendChild script

  _processRequest: (json) ->

    # If necessary, convert data to make it look like a GeoJSON FeatureCollection
    # PRWSF returns GeoJSON, but not in a FeatureCollection. Make it one.

    data = {}
    data.features = []
    data.total = json.length
    data.type = "FeatureCollection" # Not really necessary, but let's follow the GeoJSON spec for a Feature

    # convert data to make it look like a GeoJSON FeatureCollection
    for i in [0 .. json.length]
      data.features[i] = {}
      # Not really necessary, but let's follow the GeoJSON spec for a Feature
      data.features[i].type = "Feature"
      data.features[i].properties = {}
      for prop of json[i]
        if prop is "geojson"
          data.features[i].geometry = JSON.parse(json[i].geojson)
        else if prop != "properties"
          data.features[i].properties[prop] = json[i][prop]

    #remove json data
    json=null

    @_processFeatures(data)

  _processFeatures: (data) ->

    #
    # Sometimes requests take a while to come back and
    # the user might have turned the layer off
    #
    return  unless @options.map
    bounds = @options.map.getBounds()

    # Check to see if the _lastQueriedBounds is the same as the new bounds
    # If true, don't bother querying again.
    return  if @_lastQueriedBounds and @_lastQueriedBounds.equals(bounds) and not @options.autoUpdate

    # Store the bounds in the _lastQueriedBounds member so we don't have
    # to query the layer again if someone simply turns a layer on/off
    @_lastQueriedBounds = bounds


    # If "data.features" exists and there's more than one feature in the array
    if data and data.features and data.features.length

      # Loop through the return features
      i = 0

      while i < data.features.length

        # All objects are assumed to be false until proven true (remember COPS?)
        onMap = false

        # If we have a "uniqueField" for this layer
        if @options.uniqueField

          # Loop through all of the features currently on the map
          j = 0

          while j < @_vectors.length

            # Does the "uniqueField" property for this feature match the feature on the map
            if data.features[i].properties[@options.uniqueField] is @_vectors[j].properties[@options.uniqueField]

              # The feature is already on the map
              onMap = true

              # We're only concerned about updating layers that are dynamic (options.dynamic = true).
              if @options.dynamic

                # The feature's geometry might have changed, let's check.
                if @_getGeometryChanged(@_vectors[j].geometry, data.features[i].geometry)

                  # Check to see if it's a point feature, these are the only ones we're updating for now
                  if not isNaN(data.features[i].geometry.coordinates[0]) and not isNaN(data.features[i].geometry.coordinates[1])
                    @_vectors[j].geometry = data.features[i].geometry
                    @_vectors[j].vector.setLatLng new L.LatLng(@_vectors[j].geometry.coordinates[1], @_vectors[j].geometry.coordinates[0])

                propertiesChanged = @_getPropertiesChanged(@_vectors[j].properties, data.features[i].properties)

                if propertiesChanged
                  symbologyPropertyChanged = @_getPropertyChanged(@_vectors[j].properties, data.features[i].properties, @options.symbology.property)
                  @_vectors[j].properties = data.features[i].properties
                  @_setPopupContent @_vectors[j]  if @options.popupTemplate
                  if @options.symbology and @options.symbology.type isnt "single" and symbologyPropertyChanged
                    if @_vectors[j].vectors
                      k = 0
                      len3 = @_vectors[j].vectors.length
                      while k < len3
                        if @_vectors[j].vectors[k].setStyle
                          # It's a LineString or Polygon, so use setStyle
                          @_vectors[j].vectors[k].setStyle @_getFeatureStyle(@_vectors[j])
                        else
                          # It's a Point, so use setIcon
                          @_vectors[j].vectors[k].setIcon @_getFeatureStyle(@_vectors[j]).icon  if @_vectors[j].vectors[k].setIcon
                        k++
                    else if @_vectors[j].vector
                      if @_vectors[j].vector.setStyle
                        # It's a LineString or Polygon, so use setStyle
                        @_vectors[j].vector.setStyle @_getFeatureStyle(@_vectors[j])
                      else
                        # It's a Point, so use setIcon
                        @_vectors[j].vector.setIcon @_getFeatureStyle(@_vectors[j]).icon  if @_vectors[j].vector.setIcon
            j++

        # If the feature isn't already or the map OR the "uniqueField" attribute doesn't exist
        if not onMap or not @options.uniqueField
          # Convert GeoJSON to Leaflet vector (Point, Polyline, Polygon)
          vector_or_vectors = @_geoJsonGeometryToLeaflet(data.features[i].geometry, @_getFeatureStyle(data.features[i]))
          data.features[i][(if vector_or_vectors instanceof Array then "vectors" else "vector")] = vector_or_vectors

          # Show the vector or vectors on the map
          if data.features[i].vector
            @options.map.addLayer data.features[i].vector
          else if data.features[i].vectors and data.features[i].vectors.length
            k = 0

            while k < data.features[i].vectors.length
              @options.map.addLayer data.features[i].vectors[k]
              k++

          # Store the vector in an array so we can remove it later
          @_vectors.push data.features[i]
          if @options.popupTemplate
            me = this
            feature = data.features[i]
            @_setPopupContent feature
            ((feature) ->
              if feature.vector
                feature.vector.on "click", (event) ->
                  me._showPopup feature, event

              else if feature.vectors
                k = 0
                len = feature.vectors.length

                while k < len
                  feature.vectors[k].on "click", (event) ->
                    me._showPopup feature, event

                  k++
            ) feature
          if @options.clickEvent
            me = this
            feature = data.features[i]
            ((feature) ->
              if feature.vector
                feature.vector.on "click", (event) ->
                  me._fireClickEvent feature, event

              else if feature.vectors
                k = 0
                len = feature.vectors.length

                while k < len
                  feature.vectors[k].on "click", (event) ->
                    me._fireClickEvent feature, event

                  k++
            ) feature
        i++
)

# Extend Layer to support GeoJSON geometry parsing

# Convert GeoJSON to Leaflet vectors
H5.Leaflet.GeoJSONLayer = H5.Leaflet.Layer.extend(

  _geoJsonGeometryToLeaflet: (geometry, opts) ->
  # Create a variable for a single vector and for multi part vectors.
  vector, vectors = undefined
  switch geometry.type
    when "Point"
      if opts.circleMarker
        vector = new L.CircleMarker(new L.LatLng(geometry.coordinates[1], geometry.coordinates[0]), opts)
      else
        vector = new L.Marker(new L.LatLng(geometry.coordinates[1], geometry.coordinates[0]), opts)
    when "MultiPoint"
      vectors = []
      for i in [0 .. geometry.coordinates.length]
        vectors.push new L.Marker(new L.LatLng(geometry.coordinates[i][1], geometry.coordinates[i][0]), opts)
    when "LineString"
      latlngs = []
      for i in [0 .. geometry.coordinates.length]
        latlngs.push new L.LatLng(geometry.coordinates[i][1], geometry.coordinates[i][0])
      vector = new L.Polyline(latlngs, opts)
    when "MultiLineString"
      vectors = []
      for i in [0 .. geometry.coordinates.length]
        latlngs = []
        for j in [0 .. geometry.coordinates[i].length]
          latlngs.push new L.LatLng(geometry.coordinates[i][j][1], geometry.coordinates[i][j][0])
        vectors.push new L.Polyline(latlngs, opts)
    when "Polygon"
      latlngss = []
      for i in [0 .. geometry.coordinates.length]
        latlngs = []
        for j in [0 .. geometry.coordinates[i].length]
          latlngs.push new L.LatLng(geometry.coordinates[i][j][1], geometry.coordinates[i][j][0])
        latlngss.push latlngs
      vector = new L.Polygon(latlngss, opts)
    when "MultiPolygon"
      vectors = []
      for i in [0 .. geometry.coordinates.length]
        latlngss = []
        for j in [0 .. geometry.coordinates[i].length]
          latlngs = []
          for k in [0 .. geometry.coordinates[i][j].length]
            latlngs.push new L.LatLng(geometry.coordinates[i][j][k][1], geometry.coordinates[i][j][k][0])
          latlngss.push latlngs
        vectors.push new L.Polygon(latlngss, opts)
    when "GeometryCollection"
      vectors = []
      for i in [0 .. geometry.coordinates.length]
        vectors.push @_geoJsonGeometryToLeaflet(geometry.geometries[i], opts)

  vector or vectors
)
H5.Leaflet.Postgis = H5.Leaflet.GeoJSONLayer.extend(
  initialize: (options) ->

    # Check for required parameters
    i = 0
    len = @_requiredParams.length

    while i < len
      throw new Error("No \"" + @_requiredParams[i] + "\" parameter found.")  unless options[@_requiredParams[i]]
      i++

    # If the url wasn't passed with a trailing /, add it.
    options.url += "/"  if options.url.substr(options.url.length - 1, 1) isnt "/"

    # Extend Layer to create PRWSF
    H5.Leaflet.Layer::initialize.call this, options

    # _globalPointer is a string that points to a global function variable
    # Features returned from a JSONP request are passed to this function
    @_globalPointer = "PRWSF_" + Math.floor(Math.random() * 100000)
    window[@_globalPointer] = this

    # Create an array to hold the features
    @_vectors = []
    if @options.map
      if @options.scaleRange and @options.scaleRange instanceof Array and @options.scaleRange.length is 2
        z = @options.map.getZoom()
        sr = @options.scaleRange
        @options.visibleAtScale = (z >= sr[0] and z <= sr[1])
      @_show()

  options:
    geotable: null
    srid: null
    geomFieldName: "the_geom"
    geomPrecision: ""
    fields: null
    where: null
    limit: null
    uniqueField: null

  _requiredParams: ["url", "geotable"]
  _getFeatures: ->

    # Build Query
    where = (if (@options.where) then "&parameters=" + encodeURIComponent(@options.where) else null)
    if not @options.showAll
      bounds = @options.map.getBounds()
      sw = bounds.getSouthWest()
      ne = bounds.getNorthEast()
      where += (if where then " AND " else "")
      if @options.srid
        where += @options.geomFieldName + " && transform(st_setsrid(st_makebox2d(st_point(" + sw.lng + "," + sw.lat + "),st_point(" + ne.lng + "," + ne.lat + ")),4326)," + @options.srid + ")"
      else
        where += "transform(" + @options.geomFieldName + ", 4326) && st_setsrid(st_makebox2d(st_point(" + sw.lng + "," + sw.lat + "),st_point(" + ne.lng + "," + ne.lat + ")),4326)"

    # Build fields
    fields = ((if @options.fields then @options.fields else "*")) + ", st_asgeojson(transform(" + @options.geomFieldName + ",4326)" + ((if @options.geomPrecision then "," + @options.geomPrecision else "")) + ") as geojson"

    # Build URL
    # The attribute query service
    # The SQL where statement
    # The table name

    url = @options.url + "v1/ws_geo_attributequery.php" +
      "?table=" + @options.geotable +
      "&fields=" + encodeURIComponent(fields) +
      where +
      "&limit=" + @options.limit +
      "&callback=" + @_globalPointer + "._processRequest" # Need this for JSONP

    # JSONP request
    @_makeJsonpRequest url
)
