class H5.Draw

  options:
    map: null
    url: null
    buttons:
      marker: true
      line: true
      polygon: true
      rectangle: true
      circle: true
      edit: true
      remove: true
    tables: null
      # tipo:
        # nomeTabela: "nomeTabela"
        #   nome1: ""
        #   nome2: "default value"



  # stores the values of the table.
  data: null

  # Next Id that would be stored the new drawn figure
  idMarker: ""
  idLine: ""
  idPolygon: ""
  idRectangle: ""
  idCircle: ""


  constructor: (options) ->
    # configure object with the options
    @options = $.extend({}, @options, options)
    @_addDrawFunction()

  _addDrawFunction: ->
    drawnItems = new L.FeatureGroup()

    @options.map.addLayer(drawnItems)

    drawControl = new L.Control.Draw({
      draw: {
        marker: @options.buttons.marker,
        polyline: @options.buttons.line,
        polygon: @options.buttons.polygon,
        rectangle: @options.buttons.rectangle,
        circle: @options.buttons.circle
      },
      edit: {
        featureGroup: drawnItems,
        edit: @options.buttons.edit
        remove: @options.buttons.remove
      }
    })

    @options.map.addControl(drawControl)

    _getNextIdTable()

  _getNextIdTable: ->
    # Get the product name from the database, by ajax
    rest = new H5.Rest (
      url: H5.Data.restURL
      fields: "nextval('tmp_pol_id_tmp_pol_seq') as lastval"
      table: "tipo_fonte_informacao"
      limit: "1"
    )
    idPolygon = rest.data[0].lastval


    rest = new H5.Rest (
      url: H5.Data.restURL
      fields: "nextval('" + @options.tables.Line + "_seq') as lastval"
      table: "tipo_fonte_informacao"
      limit: "1"
    )
    idLine = rest.data[0].lastval

  # _createFigure: ->
  #   @options.map.on 'draw:created', (e)->
  #   type = e.layerType

  #   layer = e.layer

  #   if (type is 'polygon')
  #     # Saves a polygon
  #     firstPoint = ""

  #     layer._leaflet_id = ++idPol

  #     sql = "(nro_ocorrencia, shape) values ( " + nroOcorrencia + ",ST_MakePolygon(ST_GeomFromText('LINESTRING("

  #     $.each layer._latlngs, ->
  #       if firstPoint is ""
  #         firstPoint = @

  #       sql = sql + @.lat + " " + @.lng

  #       sql = sql +  ","

  #     sql = sql + firstPoint.lat + " " + firstPoint.lng + ")', " + $("#inputEPSG").val() + ")))"

  #     console.log(sql)

  #     # Insert the figure in a temporary table.
  #     rest = new H5.Rest (
  #      url: H5.Data.restURL
  #      fields: sql
  #      table: "tmp_pol"
  #      restService: "ws_insertquery.php"
  #     )
  #   else if (type is 'polyline')
  #     # Saves a polyline
  #     firstPoint = ""

  #     layer._leaflet_id = ++idLin

  #     sql = "(nro_ocorrencia, shape) values ( " + nroOcorrencia + ",ST_GeomFromText('LINESTRING("

  #     $.each layer._latlngs, ->
  #       if firstPoint is ""
  #         firstPoint = true
  #         sql = sql + @.lat + " " + @.lng
  #       else
  #         sql = sql + "," + @.lat + " " + @.lng

  #     sql = sql + ")', " + $("#inputEPSG").val() + "))"

  #     console.log(sql)

  #     # Insert the figure in a temporary table.
  #     rest = new H5.Rest (
  #      url: H5.Data.restURL
  #      fields: sql
  #      table: "tmp_lin"
  #      restService: "ws_insertquery.php"
  #     )
  #   else if (type is 'rectangle')

  #     layer._leaflet_id = ++idPol

  #     sql = "(nro_ocorrencia, shape) values ( " + nroOcorrencia + ",ST_MakeEnvelope("

  #     sql = sql +
  #           layer._latlngs[0].lat + "," + layer._latlngs[0].lng + ", " +
  #           layer._latlngs[2].lat + "," + layer._latlngs[2].lng

  #     sql = sql + ", " + $("#inputEPSG").val() + "))"

  #     console.log sql

  #     # Insert the figure in a temporary table.
  #     rest = new H5.Rest (
  #       url: H5.Data.restURL
  #       fields: sql
  #       table: "tmp_pol"
  #       restService: "ws_insertquery.php"
  #     )
  #   else if (type is 'circle')

  #     console.log layer

  #     layer._leaflet_id = ++idPol

  #     sql = "(nro_ocorrencia, shape) values ( " + nroOcorrencia + ", ST_Buffer(ST_GeomFromText('POINT(" +
  #           layer._latlng.lat + " " + layer._latlng.lng + ")'," + $("#inputEPSG").val() + "),"

  #     sql = sql + layer._mRadius/100010 + "))"

  #     console.log sql

  #     rest = new H5.Rest (
  #       url: H5.Data.restURL
  #       fields: sql
  #       table: "tmp_pol"
  #       restService: "ws_insertquery.php"
  #     )

  #   drawnItems.addLayer(layer)


  # _enableMaximize: ->
  #   $(@_maxBtn).on "click", (event) =>
  #     event.preventDefault()

  #     if @_maxIcon.className is "icon-resize-full"
  #       @defaultClass = @_container.className
  #       $(@_minBtn).prop "disabled", true
  #       $(@_closeBtn).prop "disabled", true
  #       @_maxIcon.className = "icon-resize-small"
  #       $("#navbar").hide()
  #     else
  #       $(@_minBtn).prop "disabled", false
  #       $(@_closeBtn).prop "disabled", false
  #       @_maxIcon.className = "icon-resize-full"
  #       $("#navbar").show()

  #     # always hide the charttable div
  #     $(@_boxTable).hide()
  #     $(@_boxTable).toggleClass "box-table-overlay"

  #     $(@_container).toggleClass @defaultClass
  #     $(@_container).toggleClass "box-overlay"
  #     $("body").toggleClass "body-overlay"

  #     $(@_boxContent).toggleClass "content-overlay"
  #     $(@_boxTable).toggleClass "content-overlay"
  #     $(@_boxContent).hide()
  #     $(@_boxContent).fadeToggle(500, "linear")

  # _enableClose: ->
  #   $(@_closeBtn).on "click", (event) =>
  #     event.preventDefault()
  #     $(@_container).hide("slide", "linear", 600)

  # _enableExport: ->
  #     generateCSV = =>
  #       str = ""
  #       line = ""

  #       $.each @options.fields, (key, value) ->
  #         if !(value.isVisible? and !value.isVisible)
  #           line += "\"" + value.columnName + "\","

  #       str += line + "\r\n"

  #       $.each @data, (key, value) ->
  #         line = ""
  #         $.each value, (key,field) ->
  #           line += "\"" + field + "\","

  #         str += line + "\r\n"

  #       return str

  #     $(@_exportBtn).click ->
  #       csv = generateCSV()
  #       window.open "data:text/csv;charset=utf-8," + escape(csv)

  # _formatFields: ->
  #   formatedFields = ""
  #   $.each @options.fields, (key, properties) ->
  #     formatedFields += properties.tableName + ","
  #   return formatedFields.substring(0,formatedFields.length-1)

  # _createTable: ->
  #   # create the table
  #   @_table = document.createElement("table")
  #   @_table.className = "table table-striped"

  #   # Get the data from the database
  #   rest = new H5.Rest (
  #     url: @options.url
  #     table: @options.table
  #     fields: @_formatFields()
  #     order: @options.uniqueField.field
  #     parameters: @options.parameters
  #   )

  #   # Stores the data on the class
  #   @data = rest.data

  #   # Add a row
  #   # for  i in @fields
  #   $.each @data, (key, properties) =>
  #     row = @_table.insertRow()
  #     # for j in i
  #     i = 0
  #     $.each properties, (nameField, nameTable) =>
  #       # Creating the field
  #       span = document.createElement("span")

  #       # Adding the span field
  #       field = row.insertCell(i++)
  #       $(field).append span

  #       # Verifies if the new field added to the row has the editable function
  #       # It will whenever it's not a unique field (primary key) of the table
  #       if !(nameField is @options.uniqueField.field and !@options.uniqueField.insertable)
  #         if @options.fields[nameField].searchData?

  #           value = ''

  #           if @options.fields[nameField].defaultValue?
  #             value = @options.fields[nameField].defaultValue
  #           else
  #             $.grep @options.fields[nameField].searchData, (e) ->
  #                 if e.text is nameTable
  #                  value = e.value

  #           $(span).editable(
  #             type: 'typeahead'
  #             placement: 'right'
  #             source: @options.fields[nameField].searchData
  #             value: value
  #             validate: (value)=>
  #               if @options.fields[nameField].validation?
  #                 @options.fields[nameField].validation(value)
  #             # Function to save the editted value on the database
  #             url: (params)=>
  #               where = ""

  #               # Gets the key of the row, to update on the database
  #               $.each row.children, (key,cell) =>
  #                 tableCell = cell.children[0]
  #                 if $(tableCell).attr("data-field") is @options.uniqueField.field
  #                   where = @options.uniqueField.field + "%3D" + tableCell.innerHTML

  #               # Construct the query on the database
  #               if params.value?
  #                 fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"
  #               else
  #                 fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"


  #               if @options.primaryTable?
  #                 # Make the request
  #                 rest = new H5.Rest (
  #                   url: @options.url
  #                   table: @options.primaryTable
  #                   fields: fields
  #                   parameters: where
  #                   restService: "ws_updatequery.php"
  #                 )
  #               else
  #                 # Make the request
  #                 rest = new H5.Rest (
  #                   url: @options.url
  #                   table: @options.table
  #                   fields: fields
  #                   parameters: where
  #                   restService: "ws_updatequery.php"
  #                 )


  #               # Reload the table
  #               @_reloadTable()
  #           )
  #         else
  #           $(span).editable(
  #             type: 'text'
  #             pk: key
  #             value:
  #               nameTable
  #             validate: (value)=>
  #               if @options.fields[nameField].validation?
  #                 @options.fields[nameField].validation(value)
  #             # Function to save the editted value on the database
  #             url: (params)=>
  #               where = ""

  #               # Gets the key of the row, to update on the database
  #               $.each row.children, (key,cell) =>
  #                 tableCell = cell.children[0]
  #                 if $(tableCell).attr("data-field") is @options.uniqueField.field
  #                   where = @options.uniqueField.field + "%3D" + tableCell.innerHTML

  #               # Construct the query on the database
  #               fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"

  #               if @options.primaryTable?
  #                 # Make the request
  #                 rest = new H5.Rest (
  #                   url: @options.url
  #                   table: @options.primaryTable
  #                   fields: fields
  #                   parameters: where
  #                   restService: "ws_updatequery.php"
  #                 )
  #               else
  #                 # Make the request
  #                 rest = new H5.Rest (
  #                   url: @options.url
  #                   table: @options.table
  #                   fields: fields
  #                   parameters: where
  #                   restService: "ws_updatequery.php"
  #                 )

  #               # Reload the table
  #               @_reloadTable()
  #           )
  #       else
  #         # If it's the unique field of the table, it must not be editable. Insert only his value, if asked for.
  #         span.innerHTML = nameTable

  #       # Add the name of the field on the database. It helps on control.
  #       if @options.fields[nameField].primaryField
  #         $(span).attr "data-field", @options.fields[nameField].primaryField
  #       else
  #         $(span).attr "data-field", nameField

  #       if @options.fields[nameField].isVisible? and !@options.fields[nameField].isVisible
  #         $(field).attr "style", "display:none;"

  #     # Adding the group buttons
  #     field = row.insertCell(i++)

  #     # Create the delete button and add it to a div field
  #     delBtn = document.createElement("a")
  #     delBtn.id = "delRowBtn"
  #     delBtn.className = "btn "
  #     icon = document.createElement("i")
  #     icon.className = "icon-trash"
  #     $(delBtn).append icon

  #     # Add the div field to the action field on the row
  #     actionBtns = document.createElement("div")
  #     $(actionBtns).append delBtn

  #     # Add the action field to the row on the table
  #     $(field).append actionBtns

  #     # Gives the delete button the delete function
  #     @_delFields(delBtn, row)

  #     # Saves the last row of the table, for future deletions
  #     @_lastRow = row

  #   # Creating Add Button
  #   addBtn = document.createElement("a")
  #   addBtn.id = "addBotao"
  #   addBtn.className = "btn"

  #   iconBtn = document.createElement("i")
  #   iconBtn.className = "icon-plus"
  #   $(addBtn).append iconBtn
  #   @_addBtn = addBtn

  #   # Add the button to the table, on top.
  #   $(@_rightCtrl).append addBtn

  #   # Gives the add button the add fuction
  #   @_addFields()

  #   # Add the created table to the container on the page
  #   $(@_boxContent).append @_table


  #   # Create the header of the table
  #   head = @_table.createTHead()
  #   row = head.insertRow(0)
  #   i = 0
  #   $.each @options.fields, (key,value) ->
  #     field = row.insertCell(i++)
  #     if !(value.isVisible? and !value.isVisible)
  #       field.innerHTML = "<strong>" + value.columnName + "</strong>"
  #     else
  #       $(field).attr 'style', 'display:none;'
  #   field = row.insertCell(i++)
  #   $(field).width(37)

  # # Function that add the add function to add buttons.
  # _addFields: ()->
  #   $(@_addBtn).on "click", (event) =>

  #     event.preventDefault()

  #     # Create the new row on the table.
  #     newRow = document.createElement("tr")

  #     $.each @options.fields, (key, properties) =>

  #       td = newRow.insertCell()
  #       span = document.createElement("span")

  #       # Verifies if the table has a unique field (primary key), and if it has, if it is editable
  #       if (key isnt @options.uniqueField.field or @options.uniqueField.insertable)
  #         value = ""

  #         if properties.defaultValue?
  #           value = properties.defaultValue

  #         if properties.searchData?
  #           $(span).editable(
  #             type: 'typeahead'
  #             value: value
  #             source: properties.searchData
  #             placement: 'right'
  #           )
  #         else
  #           $(span).editable(
  #             type: 'text'
  #             value: value
  #           )

  #       if properties.primaryField?
  #         dataField = properties.primaryField
  #       else
  #         dataField = key

  #       if properties.isVisible? and !properties.isVisible
  #         $(td).attr "style", "display:none"


  #         # # Avoiding copying the previous key to the new element on the table
  #         # if (key isnt @options.uniqueField.field or @options.uniqueField.insertable)
  #         #   invisibleField = ""
  #         #   $.each @_lastRow.children, (Key, Child)->
  #         #     if $(Child.children[0]).attr('data-field') is dataField
  #         #       invisibleField = $(Child.children[0]).html()

  #         #   span.innerHTML = invisibleField


  #       # Stores the name of the field on the database
  #       $(span).attr "data-field", dataField


  #       # Add the new field to the new row.
  #       $(td).append span


  #     # Creates and configures the new delete button
  #     delBtn = document.createElement("a")
  #     delBtn.id = "deletarBotaoTabela"
  #     delBtn.className = "btn "
  #     delBtn.style = "display:none;"
  #     icon = document.createElement("i")
  #     icon.className = "icon-trash "
  #     $(delBtn).append icon
  #     # Adds the delete function to the delete button
  #     @_delFields(delBtn, newRow)

  #     # Creates and configures the new save button
  #     saveBtn = document.createElement("a")
  #     saveBtn.id = "salvarBotaoTabela"
  #     saveBtn.className = "btn btn-primary editable-submit "
  #     icon = document.createElement("i")
  #     icon. className = "icon-ok "
  #     $(saveBtn).append icon
  #     # Adds the save function to the save button
  #     @_saveFields(saveBtn, delBtn, newRow)

  #     # Adds both to the new action div
  #     div = document.createElement("div")
  #     $(div).append saveBtn
  #     $(div).append delBtn

  #     # Adds the action div to the new row
  #     td = newRow.insertCell()
  #     $(td).append div

  #     # Adds the new row to the table.
  #     if @_lastRow?
  #       $(newRow).insertAfter $(@_lastRow)
  #     else
  #       tbody = document.getElementsByClassName("table")[0].appendChild(document.createElement('tbody'))
  #       tbody.appendChild newRow


  #     # Stores the new row as the last row of the table
  #     @_lastRow = newRow

  # # Function that add the delete function to delete buttons
  # _delFields: (delBtn, tableRow)->
  #   $(delBtn).on "click", (event) =>

  #     event.preventDefault()

  #     # Asks for the user permition to alter the database
  #     if(confirm "Você deseja excluir essa linha do banco de dados?" )
  #       # If the deleting row is the last row of the table, stores the previous one as last row
  #       if @_lastRow is tableRow
  #         @_lastRow = @_table.rows.item(@_table.rows.length-2)

  #       where = ""

  #       # Gets the key of the row to be deleted and creates the query for the deletion
  #       $.each tableRow.children, (key,cell) =>
  #         span = cell.children[0]
  #         if $(span).attr("data-field") is @options.uniqueField.field
  #           where = @options.uniqueField.field + "%3D" + span.innerHTML

  #       table = ''

  #       if @options.primaryTable?
  #         table = @options.primaryTable
  #       else
  #         table =  @options.table

  #       # Send the request for the deletion
  #       rest = new H5.Rest (
  #         url: @options.url
  #         table: table
  #         parameters: where
  #         restService: "ws_deletequery.php"
  #       )

  #       # Reload table
  #       @_reloadTable()

  #     else
  #       alert "Operação cancelada"


  # # Function that add the save function to save buttons
  # _saveFields: (saveBtn, delBtn, tableRow) ->
  #   $(saveBtn).on "click", (event) =>

  #     event.preventDefault()

  #     fields = ""
  #     values = ""
  #     i = 0

  #     $.each @options.fields, (key, properties) =>
  #       span = tableRow.children[i].children[0]

  #       # Verifies if the field is a unique field and if it's editable. If it is, stores in the query string
  #       if @options.uniqueField.field isnt key or @options.uniqueField.insertable
  #         if properties.primaryField?
  #           fields +=  properties.primaryField + ","
  #         else
  #           fields +=  key + ","

  #         if properties.searchData?
  #           val = null
  #           val = $.grep properties.searchData, (e)=>
  #             if e.text is span.innerHTML
  #               e

  #           values += "'" + val[0].value + "',"
  #         else
  #           values += "'" + span.innerHTML + "',"


  #         if @options.primaryTable?
  #           $(span).editable(
  #             validate: (value) =>
  #               if @options.fields[key].validation?
  #                 @options.fields[key].validation(value)
  #             url: (params)=>
  #               where = ""

  #               # Gets the key to the row.
  #               $.each row.children, (key,cell) =>
  #                 tableCell = cell.children[0]
  #                 if $(tableCell).attr("data-field") is @options.uniqueField.field
  #                   where = @options.uniqueField.field + "%3D" + tableCell.innerHTML

  #               # Creates the string query
  #               fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"

  #               # Sends the request
  #               rest = new H5.Rest (
  #                 url: @options.url
  #                 table: @options.primaryTable
  #                 fields: fields
  #                 parameters: where
  #                 restService: "ws_updatequery.php"
  #               )

  #               # Reload the table
  #               @_reloadTable()
  #           )
  #         else
  #           $(span).editable(
  #             validate: (value) =>
  #               if @options.fields[key].validation?
  #                 @options.fields[key].validation(value)
  #             url: (params)=>
  #               where = ""

  #               # Gets the key of the row, to update on the database
  #               $.each row.children, (key,cell) =>
  #                 tableCell = cell.children[0]
  #                 if $(tableCell).attr("data-field") is @options.uniqueField.field
  #                   where = @options.uniqueField.field + "%3D" + tableCell.innerHTML

  #               # Construct the query on the database
  #               if params.value?
  #                 fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"
  #               else
  #                 fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"

  #               # Make the request
  #               rest = new H5.Rest (
  #                 url: @options.url
  #                 table: @options.table
  #                 fields: fields
  #                 parameters: where
  #                 restService: "ws_updatequery.php"
  #               )

  #               # Reload the table
  #               @_reloadTable()
  #           )


  #       # Verifies if the passed field has a value
  #       # in case it doesnt have any value ("" or Empty), the field is not added to the query string
  #       else if span.innerHTML isnt "" and span.innerHTML isnt "Empty"
  #         fields +=  "" + key + ","
  #         values += "'" + span.innerHTML + "',"

  #       i++

  #     # Removes the last comma on the string
  #     fields = fields.substring(0,fields.length-1)
  #     values = values.substring(0,values.length-1)

  #     # Finish creating the query string for the insert function
  #     fields = " (" + fields + ") values (" + values + ") "

  #       # Send the request for the database to insert a new row on the table
  #     if @options.primaryTable?
  #       rest = new H5.Rest (
  #         url: @options.url
  #         table: @options.primaryTable
  #         fields: fields
  #         # parameters: @options.parameters
  #         restService: "ws_insertquery.php"
  #       )
  #     else
  #       rest = new H5.Rest (
  #         url: @options.url
  #         table: @options.table
  #         fields: fields
  #         # parameters: @options.parameters
  #         restService: "ws_insertquery.php"
  #       )


  #     # Reload table
  #     @_reloadTable()

  # # Function that reloads the table on the page
  # _reloadTable : ()->

  #   # For each child element of the container, remove it from the page.
  #   $.each $(@_container.children), (key,childs) ->
  #     $(childs).remove()

  #   # Calls the constructor of the classe
  #   @constructor(@options)
