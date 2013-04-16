#global lvector 
((root) ->
  root.lvector =
    VERSION: "1.4"
    noConflict: ->
      root.lvector = @_originallvector
      this

    _originallvector: root.lvector
) this

# lvector.Util is a namespace for various utility functions.
lvector.Util =
  extend: (dest) -> #Object
#-> Object
# merge src properties into dest
    sources = Array::slice.call(arguments_, 1)
    j = 0
    len = sources.length

    while j < len
      src = sources[j] or {}
      for i of src
        dest[i] = src[i]  if src.hasOwnProperty(i)
      j++
    dest

  setOptions: (obj, options) ->
    obj.options = lvector.Util.extend({}, obj.options, options)


# Class powers the OOP facilities of the library.
lvector.Class = ->

lvector.Class.extend = (props) -> #Object
#-> Class
  
  # extended class with the new prototype
  NewClass = ->
    @initialize.apply this, arguments_  if @initialize

  
  # instantiate class without calling constructor
  F = ->

  F:: = @::
  proto = new F()
  proto.constructor = NewClass
  NewClass:: = proto
  
  # add superclass access
  NewClass.superclass = @::
  
  # add class name
  #proto.className = props;
  
  #inherit parent's statics
  for i of this
    NewClass[i] = this[i]  if @hasOwnProperty(i) and i isnt "prototype" and i isnt "superclass"
  
  # mix static properties into the class
  if props.statics
    lvector.Util.extend NewClass, props.statics
    delete props.statics
  
  # mix includes into the prototype
  if props.includes
    lvector.Util.extend.apply null, [proto].concat(props.includes)
    delete props.includes
  
  # merge options
  props.options = lvector.Util.extend({}, proto.options, props.options)  if props.options and proto.options
  
  # mix given properties into the prototype
  lvector.Util.extend proto, props
  
  # allow inheriting further
  NewClass.extend = arguments_.callee
  
  # method for adding properties to prototype
  NewClass.include = (props) ->
    lvector.Util.extend @::, props

  NewClass


# lvector.Layer is a base class for rendering vector layers on a Leaflet map. It's inherited by AGS, A2E, CartoDB, GeoIQ, etc.
lvector.Layer = lvector.Class.extend(
  
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
    lvector.Util.setOptions this, options

  
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

  setOptions: (o) ->

  
  # TODO - Merge new options (o) with current options (this.options)
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

  
  # Hide the vectors in the layer. This might get called if the layer is still on but out of scaleRange.
  _hideVectors: ->
    
    # TODO: There's probably an easier way to first check for "singlePopup" option then just remove the one
    #       instead of checking for "assocatedFeatures"
    i = 0

    while i < @_vectors.length
      if @_vectors[i].vector
        @options.map.removeLayer @_vectors[i].vector
        if @_vectors[i].popup
          @options.map.removeLayer @_vectors[i].popup
        else if @popup and @popup.associatedFeature and @popup.associatedFeature is @_vectors[i]
          @options.map.removeLayer @popup
          @popup = null
      if @_vectors[i].vectors and @_vectors[i].vectors.length
        j = 0

        while j < @_vectors[i].vectors.length
          @options.map.removeLayer @_vectors[i].vectors[j]
          if @_vectors[i].vectors[j].popup
            @options.map.removeLayer @_vectors[i].vectors[j].popup
          else if @popup and @popup.associatedFeature and @popup.associatedFeature is @_vectors[i]
            @options.map.removeLayer @popup
            @popup = null
          j++
      i++

  
  # Show the vectors in the layer. This might get called if the layer is on and came back into scaleRange.
  _showVectors: ->
    i = 0

    while i < @_vectors.length
      @options.map.addLayer @_vectors[i].vector  if @_vectors[i].vector
      if @_vectors[i].vectors and @_vectors[i].vectors.length
        j = 0

        while j < @_vectors[i].vectors.length
          @options.map.addLayer @_vectors[i].vectors[j]
          j++
      i++

  
  # Hide the vectors, then empty the vectory holding array
  _clearFeatures: ->
    
    # TODO - Check to see if we even need to hide these before we remove them from the DOM
    @_hideVectors()
    @_vectors = []

  
  # Add an event hanlder to detect a zoom change on the map
  _addZoomChangeListener: ->
    
    # "this" means something different inside the on method. Assign it to "me".
    me = this
    me._zoomChangeListener = me._zoomChangeListenerTemplate()
    @options.map.on "zoomend", me._zoomChangeListener, me

  _zoomChangeListenerTemplate: ->
    
    # Whenever the map's zoom changes, check the layer's visibility (this.options.visibleAtScale)
    me = this
    ->
      me._checkLayerVisibility()

  
  # This gets fired when the map is panned or zoomed
  _idleListenerTemplate: ->
    me = this
    ->
      if me.options.visibleAtScale
        
        # Do they use the showAll parameter to load all features once?
        if me.options.showAll
          
          # Have we already loaded these features
          unless me._gotAll
            
            # Grab the features and note that we've already loaded them (no need to _getFeatures again
            me._getFeatures()
            me._gotAll = true
        else
          me._getFeatures()

  
  # Add an event hanlder to detect an idle (pan or zoom) on the map
  _addIdleListener: ->
    
    # "this" means something different inside the on method. Assign it to "me".
    me = this
    me._idleListener = me._idleListenerTemplate()
    
    # Whenever the map idles (pan or zoom) get the features in the current map extent
    @options.map.on "moveend", me._idleListener, me

  
  # Get the current map zoom and check to see if the layer should still be visible
  _checkLayerVisibility: ->
    
    # Store current visibility so we can see if it changed
    visibilityBefore = @options.visibleAtScale
    
    # Check current map scale and see if it's in this layer's range
    z = @options.map.getZoom()
    sr = @options.scaleRange
    @options.visibleAtScale = (z >= sr[0] and z <= sr[1])
    
    # Check to see if the visibility has changed
    
    # It did, hide or show vectors
    this[(if @options.visibleAtScale then "_showVectors" else "_hideVectors")]()  if visibilityBefore isnt @options.visibleAtScale
    
    # Check to see if we need to set or clear any intervals for auto-updating layers
    if visibilityBefore and not @options.visibleAtScale and @_autoUpdateInterval
      clearInterval @_autoUpdateInterval
    else if not visibilityBefore and @options.autoUpdate and @options.autoUpdateInterval
      me = this
      @_autoUpdateInterval = setInterval(->
        me._getFeatures()
      , @options.autoUpdateInterval)

  
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
      feature.popup.setContent feature.popupContent  if feature.popupContent isnt previousContent
    
    # The Popup is associated with the layer (singlePopup: true)
    else @popup.setContent feature.popupContent  if feature.popupContent isnt previousContent  if @popup and @popup.associatedFeature is feature

  
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
    unless @options.singlePopup
      
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

  
  # Get the appropriate Google Maps vector options for this feature
  _getFeatureVectorOptions: (feature) ->
    
    # Create an empty vectorOptions object to add to, or leave as is if no symbology can be found
    vectorOptions = {}
    atts = feature.properties
    
    # Is there a symbology set for this layer?
    if @options.symbology
      switch @options.symbology.type
        when "single"
          
          # It's a single symbology for all features so just set the key/value pairs in vectorOptions
          for key of @options.symbology.vectorOptions
            vectorOptions[key] = @options.symbology.vectorOptions[key]
        when "unique"
          
          # It's a unique symbology. Check if the feature's property value matches that in the symbology and style accordingly
          att = @options.symbology.property
          i = 0
          len = @options.symbology.values.length

          while i < len
            if atts[att] is @options.symbology.values[i].value
              for key of @options.symbology.values[i].vectorOptions
                vectorOptions[key] = @options.symbology.values[i].vectorOptions[key]
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
    changed = true  unless oldGeom.coordinates[0] is newGeom.coordinates[0] and oldGeom.coordinates[1] is newGeom.coordinates[1]
    changed

  _makeJsonpRequest: (url) ->
    head = document.getElementsByTagName("head")[0]
    script = document.createElement("script")
    script.type = "text/javascript"
    script.src = url
    head.appendChild script

  _processFeatures: (json) ->
    
    # Sometimes requests take a while to come back and
    # the user might have turned the layer off
    return  unless @options.map
    bounds = @options.map.getBounds()
    
    # Check to see if the _lastQueriedBounds is the same as the new bounds
    # If true, don't bother querying again.
    return  if @_lastQueriedBounds and @_lastQueriedBounds.equals(bounds) and not @options.autoUpdate
    
    # Store the bounds in the _lastQueriedBounds member so we don't have
    # to query the layer again if someone simply turns a layer on/off
    @_lastQueriedBounds = bounds
    data = {}
    data.features = []
    data.total = json.length
    data.type = "FeatureCollection" # Not really necessary, but let's follow the GeoJSON spec for a Feature
    # convert data to make it look like a GeoJSON FeatureCollection
    i = 0
    len = json.length

    while i < len
      data.features[i] = {}
      data.features[i].properties = {}
      for prop of json[i]
        if prop is "geojson"
          data.features[i].geometry = json[i].geojson
        else data.features[i].properties[prop] = json[i][prop]  unless prop is "properties"
      data.features[i].type = "Feature" # Not really necessary, but let's follow the GeoJSON spec for a Feature
      i++
    
    # remove json data
    delete json

    
    # If "data.features" exists and there's more than one feature in the array
    if data and data.features and data.features.length
      
      # Loop through the return features
      i = 0
      while i < data.features.length
        
        # All objects are assumed to be false until proven true (remember COPS?)
        onMap = false
        
        # Convert GeoJSON to Leaflet vector (Point, Polyline, Polygon)
        geometry = $.parseJSON(data.features[i].geometry)
        geometryOptions = @_getFeatureVectorOptions(data.features[i])
        vector_or_vectors = @_geoJsonGeometryToLeaflet(geometry, geometryOptions)
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
        i++
)

# Extend Layer to support GeoJSON geometry parsing

# Convert GeoJSON to Leaflet vectors
lvector.GeoJSONLayer = lvector.Layer.extend(_geoJsonGeometryToLeaflet: (geometry, opts) ->
  
  # Create a variable for a single vector and for multi part vectors.
  vector = undefined
  vectors = undefined
  switch geometry.type
    when "Point"
      vector = new L.Marker(new L.LatLng(geometry.coordinates[1], geometry.coordinates[0]), opts)
    when "MultiPoint"
      vectors = []
      i = 0
      len = geometry.coordinates.length

      while i < len
        vectors.push new L.Marker(new L.LatLng(geometry.coordinates[i][1], geometry.coordinates[i][0]), opts)
        i++
    when "LineString"
      latlngs = []
      i = 0
      len = geometry.coordinates.length

      while i < len
        latlngs.push new L.LatLng(geometry.coordinates[i][1], geometry.coordinates[i][0])
        i++
      vector = new L.Polyline(latlngs, opts)
    when "MultiLineString"
      vectors = []
      i = 0
      len = geometry.coordinates.length

      while i < len
        latlngs = []
        j = 0
        len2 = geometry.coordinates[i].length

        while j < len2
          latlngs.push new L.LatLng(geometry.coordinates[i][j][1], geometry.coordinates[i][j][0])
          j++
        vectors.push new L.Polyline(latlngs, opts)
        i++
    when "Polygon"
      latlngss = []
      i = 0
      len = geometry.coordinates.length

      while i < len
        latlngs = []
        j = 0
        len2 = geometry.coordinates[i].length

        while j < len2
          latlngs.push new L.LatLng(geometry.coordinates[i][j][1], geometry.coordinates[i][j][0])
          j++
        latlngss.push latlngs
        i++
      vector = new L.Polygon(latlngss, opts)
    when "MultiPolygon"
      vectors = []
      i = 0
      len = geometry.coordinates.length

      while i < len
        latlngss = []
        j = 0
        len2 = geometry.coordinates[i].length

        while j < len2
          latlngs = []
          k = 0
          len3 = geometry.coordinates[i][j].length

          while k < len3
            latlngs.push new L.LatLng(geometry.coordinates[i][j][k][1], geometry.coordinates[i][j][k][0])
            k++
          latlngss.push latlngs
          j++
        vectors.push new L.Polygon(latlngss, opts)
        i++
    when "GeometryCollection"
      vectors = []
      i = 0
      len = geometry.geometries.length

      while i < len
        vectors.push @_geoJsonGeometryToLeaflet(geometry.geometries[i], opts)
        i++
  vector or vectors
)
lvector.PRWSF = lvector.GeoJSONLayer.extend(
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
    lvector.Layer::initialize.call this, options
    
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
    fields: ""
    where: null
    limit: 100
    uniqueField: null

  _requiredParams: ["url", "geotable"]
  _getFeatures: ->
    
    # Build Query
    where = (if (@options.where) then "&parameters=" + encodeURIComponent(@options.where) else "")
    unless @options.showAll
      bounds = @options.map.getBounds()
      sw = bounds.getSouthWest()
      ne = bounds.getNorthEast()
      where += (if where.length then " AND " else "")
      if @options.srid
        where += @options.geomFieldName + " && st_setsrid(st_makebox2d(st_point(" + sw.lng + "," + sw.lat + "),st_point(" + ne.lng + "," + ne.lat + "))," + @options.srid + ")"
      else
        where += "" + @options.geomFieldName + ",4326) && st_setsrid(st_makebox2d(st_point(" + sw.lng + "," + sw.lat + "),st_point(" + ne.lng + "," + ne.lat + "))"
    
    # Build fields
    fields = ((if @options.fields.length then @options.fields + "," else "")) + "st_asgeojson(" + @options.geomFieldName + ") as geojson"
    
    # Build URL
    # The attribute query service
    # The table name
    # The table fields
    # The limit value
    url = @options.url + "v1/ws_geo_attributequery.php" + "?table=" + @options.geotable + "&fields=" + encodeURIComponent(fields) + where + "&limit=" + @options.limit + "&callback=" + @_globalPointer + "._processFeatures" # Need this for JSONP
    
    # JSONP request
    @_makeJsonpRequest url
)
