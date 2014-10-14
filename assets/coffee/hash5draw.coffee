# NOT IMPLEMENTED POINT () OR EDIT FUNCTIONS.

class H5.Draw

  options:
    map: null
    url: null
    srid: null
    uniquePoint: null
    loadDrawn: false
    reloadShape: true
    buttons:
      marker: true
      line: true
      polygon: true
      rectangle: true
      circle: true
      edit: true
      remove: true
    tables: null
    # Structure of the field tables:
    #   marker:
    #     tableName:"tableName"
    #     columnFields: ["field1","field2",...]
    #     uniqueField: "uniqueField"
    #     defaultValues:
    #       field1: "defaultValue1"
    #       field2: "defaultValue2"
    #       .
    #       .



  # stores the values of the table.
  data: null

  # Next Id that would be stored the new drawing figure. Rectangles and Circles are all saved on polygon tables
  idMarker: ""
  idPolyline: ""
  idPolygon: ""

  # Stores the shape where the drawing are inserted
  drawnItems: null

  # Variable that determines if the framework already loaded the shapes to be edited. It controls when to pull from the database and when to pull from the html.

  constructor: (options) ->
    # configure object with the options
    @options = $.extend({}, @options, options)
    @_addDrawFunction()

  # Function that adds buttons and functionalities to the map.
  _addDrawFunction: ()->
    # Shape that will store the drawings did by the user
    @drawnItems = new L.FeatureGroup()

    @options.map.addLayer(@drawnItems)

    # Selecting which buttons will appear in the map.
    drawControl = new L.Control.Draw({
      draw: {
        marker: @options.buttons.marker,
        polyline: @options.buttons.polyline,
        polygon: @options.buttons.polygon,
        rectangle: @options.buttons.rectangle,
        circle: @options.buttons.circle
      },
      edit: {
        featureGroup: @drawnItems,
        edit: @options.buttons.edit
        remove: @options.buttons.remove
      }
    })
    @options.map.addControl(drawControl)

    # In case it is necessary to reload the shape,
    # calls the function that retrieves it from the tmp table on the database
    if @.options.reloadShape then @reloadShape()

    # Gets the next id from the database
    @_getNextIdTable()

    # Function that links the draw functionality to each element (point, line, polygon)
    @_addDrawButtonActions()

    # Not implemented yet!!!
    # Function that links the edit funcionality to each element (point, line, polygon)
    # @_addEditButtonActions()

    # Function that links the remove functionality to each element (point, line, polygon)
    @_addRemoveButtonActions()

  # Function that gets the next id of the drawing from the database, keeping the unity of the key
  _getNextIdTable: ()->
    $.each @.options.buttons, (key, value)=>
      # get the key for each element (point, line, polygon)
      if value is true
        # Our database stores rectangle, circle and polygon in the same table: they're all polygons
        if key is 'polygon' or key is 'rectangle' or key is 'circle'
          if @idPolygon is ""
            rest = new H5.Rest (
              url: @.options.url
              fields: "nextval('" + @.options.tables['polygon'].table + "_" + @.options.tables['polygon'].uniqueField + "_seq') as lastval"
              restService: "ws_selectonlyquery.php"
            )
            # Saves the Id from the database on a Global variable.
            @idPolygon = rest.data[0].lastval
        else if key is 'polyline'
          if @idPolyline is ""
            rest = new H5.Rest (
              url: @.options.url
              fields: "nextval('" + @.options.tables[key].table + "_" + @.options.tables[key].uniqueField + "_seq') as lastval"
              restService: "ws_selectonlyquery.php"
            )

            # Saves the Id from the database on a Global variable.
            @idPolyline = rest.data[0].lastval
        else if key is 'marker'
          if @idMarker is ""
            rest = new H5.Rest (
              url: @.options.url
              fields: "nextval('" + @.options.tables[key].table + "_" + @.options.tables[key].uniqueField + "_seq') as lastval"
              restService: "ws_selectonlyquery.php"
            )

            # Saves the Id from the database on a Global variable.
            @idMarker = rest.data[0].lastval

  # Add functionality to the buttons inserted
  _addDrawButtonActions: ()->
    # Add the create functionality
    @options.map.on 'draw:created', (e)=>
      type = e.layerType

      layer = e.layer

      if (type is 'polygon')
        # Saves a polygon
        firstPoint = ""

        layer._leaflet_id = ++@idPolygon

        columns = ""
        values = ""

        $.each @.options.tables[type].fields, (key,field)=>
          if @.options.tables[type].defaultValues[field]
            columns = columns + field + ","
            values = values + @.options.tables[type].defaultValues[field] + ","

        columns = columns + "shape,dt_registro"
        values = values + "ST_MakePolygon(ST_GeomFromText('LINESTRING("

        $.each layer._latlngs, ->
          if firstPoint is ""
            firstPoint = @

          values = values + @.lng + " " + @.lat
          values = values +  ","

        values = values + firstPoint.lng + " " + firstPoint.lat + ")', " + @.options.srid + "))"
        values = values + ",now()"

        sql = "(" + columns + ") values (" + values + ")"

        # Insert the figure in a temporary table.
        rest = new H5.Rest (
         url: H5.Data.restURL
         fields: sql
         table: "tmp_pol"
         restService: "ws_insertquery.php"
        )
      else if (type is 'polyline')
        # Saves a polyline
        firstPoint = ""

        layer._leaflet_id = ++@idPolyline

        columns = ""
        values = ""

        $.each @.options.tables[type].fields, (key,field)=>
          if @.options.tables[type].defaultValues[field]
            columns = columns + field + ","
            values = values + @.options.tables[type].defaultValues[field] + ","

        columns = columns + "shape,dt_registro"
        values = values + "ST_GeomFromText('LINESTRING("

        $.each layer._latlngs, ->
          if firstPoint is ""
            firstPoint = true
            values = values + @.lng + " " + @.lat
          else
            values = values + "," + @.lng + " " + @.lat

        values = values + ")', " + @.options.srid + ")"
        values = values + ",now()"

        sql = "(" + columns + ") values (" + values + ")"

        # Insert the figure in a temporary table.
        rest = new H5.Rest (
         url: H5.Data.restURL
         fields: sql
         table: "tmp_lin"
         restService: "ws_insertquery.php"
        )
      else if (type is 'rectangle')
        # Rectangles and Circles are all represented as Polygons, on the DB
        type = 'polygon'

        layer._leaflet_id = ++@idPolygon

        columns = ""
        values = ""

        $.each @.options.tables[type].fields, (key,field)=>
          if @.options.tables[type].defaultValues[field]
            columns = columns + field + ","
            values = values + @.options.tables[type].defaultValues[field] + ","

        columns = columns + "shape,dt_registro"
        values = values + "ST_MakeEnvelope("

        values = values +
              layer._latlngs[0].lng + "," + layer._latlngs[0].lat + ", " +
              layer._latlngs[2].lng + "," + layer._latlngs[2].lat
        values = values + ", " + @.options.srid + ")"
        values = values + ",now()"

        sql = "(" + columns + ") values (" + values + ")"

        # Insert the figure in a temporary table.
        rest = new H5.Rest (
          url: H5.Data.restURL
          fields: sql
          table: "tmp_pol"
          restService: "ws_insertquery.php"
        )
      else if (type is 'circle')

        # Rectangles and Circles are all represented as Polygons, on the DB
        type = 'polygon'

        layer._leaflet_id = ++@idPolygon

        columns = ""
        values = ""

        $.each @.options.tables[type].fields, (key,field)=>
          if @.options.tables[type].defaultValues[field]
            columns = columns + field + ","
            values = values + @.options.tables[type].defaultValues[field] + ","

        columns = columns + "shape,dt_registro"
        values = values + "ST_Buffer(ST_GeomFromText('POINT("
        values = values + layer._latlng.lng + " " + layer._latlng.lat + ")'," + @.options.srid + "),"
        values = values + layer._mRadius/100010 + ")"
        values = values + ",now()"

        sql = "(" + columns + ") values (" + values + ")"

        rest = new H5.Rest (
          url: H5.Data.restURL
          fields: sql
          table: "tmp_pol"
          restService: "ws_insertquery.php"
        )
      else if (type is 'marker')

        layer._leaflet_id = ++@idMarker

        sql = "ST_Intersects(br_mar.geom, ST_SetSRID(ST_MakePoint("
        sql = sql + layer._latlng.lng + "," + layer._latlng.lat + ")," + @.options.srid + "))"

        rest = new H5.Rest (
          url: H5.Data.restURL
          fields: sql
          table: "br_mar"
        )

        if rest.data[0].st_intersects
          if (!@.options.uniquePoint?) or (@.options.uniquePoint is true)

            columns = ""
            values = ""

            $.each @.options.tables[type].fields, (key,field)=>
              if @.options.tables[type].defaultValues[field]
                columns = columns + field + ","
                values = values + @.options.tables[type].defaultValues[field] + ","

            columns = columns + "shape,dt_registro"
            values = values + "ST_SetSRID(ST_MakePoint("
            values = values + layer._latlng.lng + "," + layer._latlng.lat + ")," + @.options.srid + ")"
            values = values + ",now()"

            sql = "(" + columns + ") values (" + values + ")"

            rest = new H5.Rest (
              url: H5.Data.restURL
              fields: sql
              table: "tmp_pon"
              restService: "ws_insertquery.php"
            )
          else
            layer._leaflet_id = @.options.uniquePoint._leaflet_id
            sql = "shape=ST_SetSRID(ST_MakePoint(" +
                  layer._latlng.lng + "," + layer._latlng.lat + ")," +
                  @.options.srid + ")"

            sql = sql + ",dt_registro=now()"

            rest = new H5.Rest (
              url: H5.Data.restURL
              fields: sql
              table: "tmp_pon"
              parameters: "id_tmp_pon%3D" + layer._leaflet_id
              restService: "ws_updatequery.php"
            )

          if document.getElementById('inputLat')? and document.getElementById('inputLng')?
            $("#inputLat").val(@decimalDegree2DMS(layer._latlng.lat))
            $("#inputLng").val(@decimalDegree2DMS(layer._latlng.lng))

        else
          --@idMarker

          popup = L.popup()
          .setLatLng(layer._latlng)
          .setContent('<p>O ponto está fora da área limite do Brasil!</p>')
          .openOn(@options.map);

          $("#inputLat").val ""
          $("#inputLng").val ""
          $('#dropdownUF option:eq(0)').prop 'selected', true
          $('#dropdownMunicipio option:eq(0)').prop 'selected', true
          $('#inputEndereco').val ""

      if (!@.options.uniquePoint? || (@.options.uniquePoint? and type isnt 'marker'))
        @drawnItems.addLayer(layer)
      else if @idMarker == layer._leaflet_id
        @drawnItems.removeLayer(@.options.uniquePoint)
        @.options.uniquePoint = layer
        @drawnItems.addLayer(@.options.uniquePoint)



  # Not Functioning!!!

  # Add the edit functionality
  # _addEditButtonActions: ()->
  #   @.options.map.on 'draw:edited', (e)=>

  #     type = ""
  #     sqlPon = ""
  #     sqlLin = ""
  #     this._map=@.options.map

  #     $.each e.layers._layers, (key,layer)=>

  #       firstPoint = ""

  #       type = layer.toGeoJSON().geometry.type

  #       if type is 'Polygon'
  #         sql = "shape%3DST_MakePolygon(ST_GeomFromText('LINESTRING("

  #         $.each layer._latlngs, ->
  #           if firstPoint is ''
  #             firstPoint = @

  #           sql = sql + "" + @.lat + " " + @.lng

  #           sql = sql + ","

  #         sql = sql + firstPoint.lat + " " + firstPoint.lng + ")', " + $("#inputEPSG").val() + "))"

  #         # # Remove lines
  #         rest = new H5.Rest (
  #           url: H5.Data.restURL
  #           table: "tmp_pol"
  #           fields: sql
  #           parameters: "id_tmp_pol%3D" + key
  #           restService: "ws_updatequery.php"
  #         )
  #       else if type is 'LineString'
  #         sqlLin = sqlLin + "or id_tmp_lin=" + key + " "
  #         sql = "shape%3DST_Envelope(ST_GeomFromText('LINESTRING("

  #         sql = sql +
  #               layer._latlngs[0].lat + " " + layer._latlngs[0].lng + ", " +
  #               layer._latlngs[2].lat + " " + layer._latlngs[2].lng

  #         sql = sql + ")', " + $("#inputEPSG").val() + ")))"


  # Add the remove functionality
  _addRemoveButtonActions: ->
    @.options.map.on 'draw:deleted', (e)=>

      type = ""
      sqlPol = "id_tmp_pol=0 "
      sqlLin = "id_tmp_lin=0 "
      sqlPon = "id_tmp_pon=0 "

      $.each e.layers._layers, (key,layer)=>

        type = layer.toGeoJSON().geometry.type

        if type is 'Polygon'
          sqlPol = sqlPol + "or id_tmp_pol=" + key + " "

          $.each @.options.tables['polygon'].defaultValues, (key,field)->
            sqlPol = sqlPol + "and " + key + "='" +
                     field + "'"
        else if type is 'LineString'
          sqlLin = sqlLin + "or id_tmp_lin=" + key + " "

          $.each @.options.tables['polyline'].defaultValues, (key,field)->
            sqlLin = sqlLin + "and " + key + "='" +
                     field + "'"
        else if type is 'Point'
          # In case it is a circle (post inserting)
          if layer._mRadius?
            sqlPol = sqlPol + "or id_tmp_pol=" + key + " "

            $.each @.options.tables['polygon'].defaultValues, (key,field)->
              sqlPol = sqlPol + "and " + key + "='" +
                       field + "'"
          # In case it is a marker
          else
            sqlPon = sqlPon + "or id_tmp_pon=" + key + " "

            $.each @.options.tables['marker'].defaultValues, (key,field)->
              sqlPon = sqlPon + "and " + key + "='" + field + "'"

            if document.getElementById('inputLat')? and document.getElementById('inputLng')?
              $("#inputLat").val ""
              $("#inputLng").val ""
              $('#dropdownUF option:eq(0)').prop 'selected', true
              $('#dropdownMunicipio option:eq(0)').prop 'selected', true
              $('#inputEndereco').val ""
              # $("#inputLng").val('').trigger('change')

            if @.options.uniquePoint?
              @.options.uniquePoint = true


      # Remove lines
      rest = new H5.Rest (
        url: H5.Data.restURL
        table: "tmp_pon"
        parameters: sqlPon
        restService: "ws_deletequery.php"
      )
      rest = new H5.Rest (
        url: H5.Data.restURL
        table: "tmp_pol"
        parameters: sqlPol
        restService: "ws_deletequery.php"
      )
      rest = new H5.Rest (
        url: H5.Data.restURL
        table: "tmp_lin"
        parameters: sqlLin
        restService: "ws_deletequery.php"
      )

  decimalDegree2DMS: (decdegree)->
    deg = decdegree | 0;
    frac = Math.abs(decdegree - deg);
    min = (frac * 60) | 0;
    sec = frac * 3600 - min * 60;

    dms = deg + "°" + min + "\'" + sec

    # console.log dms

    return dms


  DMS2DecimalDegree: (dms)->
    if !dms
      return Number.NaN

    neg = if dms.match(/(^\s?-)|(\s?[SW]\s?$)/)!=null then -1.0 else 1.0
    dms = dms.replace /(^\s?-)|(\s?[NSEW]\s?)$/,''
    dms = dms.replace /\s/g,''
    parts = dms.match /(\d{1,3})[.,°d]?(\d{0,2})[']?(\d{0,2})[.,]?(\d{0,})(?:["]|[']{2})?/

    # console.log parts

    if parts is null
      return Number.NaN

    d = if parts[1] then parts[1] else '0.0'
    d = d  * 1.0

    m = if parts[2] then parts[2] else '0.0'
    m = m * 1.0

    s = if parts[3] then parts[3] else '0.0'
    s = s * 1.0

    r = if parts[4] then '0.' + parts[4] else '0.0'
    r = r * 1.0

    dec = (d + (m/60.0) + (s/3600.0) + (r/3600.0)) * neg

    console.log dec

    return dec




  # Sets the marker, if the uniquePoint is defined
  setPoint: (latlng, srid)->

    @drawnItems.removeLayer(@.options.uniquePoint)

    srid = if srid isnt "" then srid else @.options.srid

    # Create the uniquePoint in case it doesnt exists.
    if (!@.options.uniquePoint?) or (@.options.uniquePoint is true)
      # Create the marker to put on the map
      @.options.uniquePoint = new L.Marker([0 ,0])

      @.options.uniquePoint._leaflet_id = ++@idMarker

      # Insert that marker on the Database
      columns = ""
      values = ""

      $.each @.options.tables['marker'].fields, (key,field)=>
        if @.options.tables['marker'].defaultValues[field]
          columns = columns + field + ","
          values = values + @.options.tables['marker'].defaultValues[field] + ","

      columns = columns + "shape,dt_registro"
      values = values + "ST_SetSRID(ST_MakePoint("
      values = values + latlng.lng + "," + latlng.lat + ")," + srid + ")"

      values = values + ",now()"

      sql = "(" + columns + ") values (" + values + ")"

      rest = new H5.Rest (
        url: H5.Data.restURL
        fields: sql
        table: "tmp_pon"
        restService: "ws_insertquery.php"
      )
    @.options.uniquePoint._latlng.lat = latlng.lat
    @.options.uniquePoint._latlng.lng = latlng.lng

    @drawnItems.addLayer(@.options.uniquePoint)

  # Return the marker
  getPoint: ()->
    @.options.uniquePoint

  # Sets the epsg of the class
  setSRID: (newSRID)->
    @.options.srid = newSRID

  # Reload the shape with the drawings inserted, in case the page is reloaded
  reloadShape: ()->
    # Add possibles vectors already created (be when reloading the page, be when loading a saved report)
    # Search on database vectors already on the tmp_pol table
    rest = new H5.Rest (
      url: @.options.url
      fields: 'id_tmp_lin, ST_AsGeoJson(ST_FlipCoordinates(shape)) as shape'
      table: "tmp_lin"
      parameters: "nro_ocorrencia='" + @.options.tables['polyline'].defaultValues.nro_ocorrencia + "'"
    )
    polylineList = rest.data

    $.each polylineList, (key,line)=>

      element = JSON.parse(line.shape)

      polyline = new L.Polyline(element.coordinates)
      polyline._leaflet_id = line.id_tmp_lin
      @drawnItems.addLayer(polyline)

      @idPolyline = line.id_tmp_lin

    # Add possibles vectors already created (be when reloading the page, be when loading a saved report)
    # Search on database vectors already on the tmp_pol table
    rest = new H5.Rest (
      url: @.options.url
      fields: 'id_tmp_pol, ST_AsGeoJson(ST_FlipCoordinates(shape)) as shape'
      table: "tmp_pol"
      parameters: "nro_ocorrencia='" + @.options.tables['polygon'].defaultValues.nro_ocorrencia + "'"
    )
    polygonList = rest.data

    $.each polygonList, (key,pol)=>

      element = JSON.parse(pol.shape)

      polygon = new L.Polygon(element.coordinates)
      polygon._leaflet_id = pol.id_tmp_pol
      @drawnItems.addLayer(polygon)

      @idPolygon = pol.id_tmp_pol

    # Add possibles vectors already created (be when reloading the page, be when loading a saved report)
    # Search on database vectors already on the tmp_pol table
    rest = new H5.Rest (
      url: @.options.url
      fields: 'id_tmp_pon, ST_AsGeoJson(ST_FlipCoordinates(shape)) as shape'
      table: "tmp_pon"
      parameters: "nro_ocorrencia='" + @.options.tables['marker'].defaultValues.nro_ocorrencia + "'"
    )
    markerList = rest.data

    $.each markerList, (key,pon)=>

      element = JSON.parse(pon.shape)

      point = new L.Marker(element.coordinates)
      point._leaflet_id = pon.id_tmp_pon
      @drawnItems.addLayer(point)

      # @idMarker = pon.id_tmp_pon

      if (@.options.uniquePoint?)
        @.options.uniquePoint = point

  editShapes: (pointTable, polygonTable, lineTable)->
  # Retrieve every draw from the table specified and copy it to the tmp table

    marker = @.options.tables.marker
    fields = ''
    values = ''

    $.each marker.fields, (key, value)->
      fields = fields + value + ","
      if marker.defaultValues[value]
        values = values + marker.defaultValues[value] + " as " + value + ","
      else
        values = values + pointTable.fields[key] + ","

    sql = "(" + fields + "dt_registro) select " + values + "now() as dt_registro from "  + pointTable.name + " where " + pointTable.parameters.field + "%3D" + pointTable.parameters.value

    console.log sql

    rest = new H5.Rest (
      url: @.options.url
      fields: sql
      table: @.options.tables.marker.table
      restService: "ws_insertquery.php"
    )

    polygon = @.options.tables.polygon
    fields = ''
    values = ''

    $.each polygon.fields, (key, value)->
      fields = fields + value + ","
      if polygon.defaultValues[value]
        values = values + polygon.defaultValues[value] + " as " + value + ","
      else
        values = values + polygonTable.fields[key] + ","

    sql = "(" + fields + "dt_registro) select " + values + "now() as dt_registro from "  + polygonTable.name + " where " + polygonTable.parameters.field + "%3D" + polygonTable.parameters.value

    console.log sql

    rest = new H5.Rest (
      url: @.options.url
      fields: sql
      table: polygon.table
      restService: "ws_insertquery.php"
    )

    line = @.options.tables.polyline
    fields = ''
    values = ''

    $.each line.fields, (key, value)->
      fields = fields + value + ","
      if line.defaultValues[value]
        values = values + line.defaultValues[value] + " as " + value + ","
      else
        values = values + lineTable.fields[key] + ","

    sql = "(" + fields + "dt_registro) select " + values + "now() as dt_registro from "  + lineTable.name + " where " + lineTable.parameters.field + "%3D" + lineTable.parameters.value

    console.log sql

    rest = new H5.Rest (
      url: @.options.url
      fields: sql
      table: line.table
      restService: "ws_insertquery.php"
    )

    @reloadShape()



