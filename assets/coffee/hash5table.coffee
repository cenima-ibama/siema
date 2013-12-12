class H5.Table

  options:
    insertNewType: null      # if it is possible to insert
    container: null       # represents where the table will be created
    title: null           # Title shown in the html table
    table: null           # Table used to create the fields that will be show on the html table
    primaryTable: null    # table used on remotion when the html table is composed of 2 or more tables
    url: ""               # URL that the rest will send the insert, delete and update jsons.
    registUpdate: false   # Field for registering the update/create timestamp that the fields are altered/inserted.
    fields: null          # object that holds the fields listed on the html table
    # fields structure
    #   columnName: string (name of the column on the database that the info will come)
    #   validation: function (function to validate on inserting)
    #   tableName:  string (name of the table that this field is related, in case the field is a formated field)
    #   isVisible:  boolean (in case you want a field to be shown on the form but not be visible to the user)
    #   searchData:  array (in case it is a typeahead field, you can pass the source for the search)
    #   primaryField:  string (in case the field is a formated info, you can pass the principal source of the information, for example a id field)
    #   defaultValue: string (in case you want to pass a default value for the field)
    #   selectArray:  array (in case it is a select field, you can pass a object with the elements for the select field)
    #     id: name_of_the_field   (the id and the name)
    uniqueField:
      field: null         # string (pass for the framework which field is the primary field (id) of the table, in cases of deletion or insertion)
      insertable: false   # boolean (represents if the field primary field is auto increment, or it accepts the user to define it. By the default, the id is created by the database)
    # Buttons showns on the html table
    buttons:
      arrows: false
      export: false       # Button to export the table info (Not implemented)
      minimize: false     # Button to minimize the table
      maximize: false     # Button to maximize the table
      close: false        # Button to close the table

  # stores the values of the table.
  data: null

  constructor: (options) ->
    # configure object with the options
    @options = $.extend({}, @options, options)

    @_createContainer()

  # Create the container (where the html table will be staying)
  _createContainer: ->
    @_container = document.getElementById(@options.container)

    boxHeader = document.createElement("div")
    boxHeader.className = "box-header"
    @_boxHeader = boxHeader

    boxTitle = document.createElement("h2")
    boxTitle.innerHTML = @options.title
    @_boxTitle = boxTitle

    leftCtrl = document.createElement("div")
    leftCtrl.className = "btn-group chart-icon btn-left"
    @_leftCtrl = leftCtrl

    rightCtrl = document.createElement("div")
    rightCtrl.className = "btn-group chart-icon btn-right"
    @_rightCtrl = rightCtrl

    boxContent = document.createElement("div")
    boxContent.id = "database-" + @options.container
    boxContent.className = "box-content-database"
    @_boxContent = boxContent

    $(@_boxHeader).append @_leftCtrl, @_boxTitle, @_rightCtrl
    $(@_container).append @_boxHeader, @_boxContent, @_chartFooter

    pipeline = "<span class=\"break\"></span>"

    if @options.buttons.arrows
      # add break
      $(@_boxTitle).prepend pipeline
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

    if @options.buttons.export

      # add export button
      exportBtn = document.createElement("button")
      exportBtn.id = @options.container + "-btn-export"
      exportBtn.className = "btn"
      @_exportBtn = exportBtn

      exportIcon = document.createElement("i")
      exportIcon.className = "icon-download-alt"
      @_exportIcon = exportIcon
      $(@_exportBtn).append @_exportIcon

      $(@_rightCtrl).append @_exportBtn

      @_enableExport()

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

      @_enableMinimize()

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

      @_enableMaximize()

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

      @_enableClose()
    @_createTable()

  # Function that do the minimizing event
  _enableMinimize: ->
    $(@_minBtn).on "click", (event) =>
      event.preventDefault()

      if $(@_boxContent).is(":visible")
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

      if $(@_boxTable).is(":visible")
        $(@_boxTable).slideToggle("fast", "linear")

      $(@_boxContent).slideToggle("fast", "linear")

  # Function that do the maximizing event
  _enableMaximize: ->
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

      # always hide the charttable div
      $(@_boxTable).hide()
      $(@_boxTable).toggleClass "box-table-overlay"

      $(@_container).toggleClass @defaultClass
      $(@_container).toggleClass "box-overlay"
      $("body").toggleClass "body-overlay"

      $(@_boxContent).toggleClass "content-overlay"
      $(@_boxTable).toggleClass "content-overlay"
      $(@_boxContent).hide()
      $(@_boxContent).fadeToggle(500, "linear")

  # Function that do the close event
  _enableClose: ->
    $(@_closeBtn).on "click", (event) =>
      event.preventDefault()
      $(@_container).hide("slide", "linear", 600)

  # Function that do the export event
  _enableExport: ->
      generateCSV = =>
        str = ""
        line = ""

        $.each @options.fields, (key, value) ->
          if !(value.isVisible? and !value.isVisible)
            line += "\"" + value.columnName + "\","

        str += line + "\r\n"

        $.each @data, (key, value) ->
          line = ""
          $.each value, (key,field) ->
            line += "\"" + field + "\","

          str += line + "\r\n"

        return str

      $(@_exportBtn).click ->
        csv = generateCSV()
        window.open "data:text/csv;charset=utf-8," + escape(csv)

  # Function that format the fields into a sql string
  _formatFields: ->
    formatedFields = ""
    $.each @options.fields, (key, properties) ->
      if properties.tableName?
        formatedFields += properties.tableName + ","
      else
        formatedFields += key + ","
    return formatedFields.substring(0,formatedFields.length-1)

  _createTable: ->
    # create the table
    @_table = document.createElement("table")
    @_table.className = "table table-striped"

    # Get the data from the database
    rest = new H5.Rest (
      url: @options.url
      table: @options.table
      fields: @_formatFields()
      order: @options.uniqueField.field
      parameters: @options.parameters
    )

    # Stores the data on the class
    @data = rest.data

    # Add a row
    # for  i in @fields
    $.each @data, (key, properties) =>
      row = @_table.insertRow()
      # for j in i
      i = 0
      $.each properties, (nameField, nameTable) =>
        # Creating the field
        span = document.createElement("span")

        # Adding the span field
        field = row.insertCell(i++)
        $(field).append span

        # Verifies if the new field added to the row has the editable property
        # It will have whenever it's not a unique field (primary key) of the table
        if !(nameField is @options.uniqueField.field and !@options.uniqueField.insertable)
          # Verifies if the field is of type typeahead
          if @options.fields[nameField].searchData?

            value = ''

            if @options.fields[nameField].defaultValue?
              value = @options.fields[nameField].defaultValue
            else
              $.grep @options.fields[nameField].searchData, (e) ->
                  if e.text is nameTable
                   value = e.value

            # Add the source info for the typeahead input
            $(span).editable(
              type: 'typeahead'
              placement: 'right'
              source: @options.fields[nameField].searchData
              value: value
              validate: (value)=>
                if @options.fields[nameField].validation?
                  @options.fields[nameField].validation(value)
              # Function to save the editted value on the database
              url: (params)=>
                where = ""

                # Gets the key of the row, to update on the database
                $.each row.children, (key,cell) =>
                  tableCell = cell.children[0]
                  if $(tableCell).attr("data-field") is @options.uniqueField.field
                    where = @options.uniqueField.field + "%3D" + tableCell.innerHTML

                # Construct the query on the database
                if params.value?
                  fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"
                else
                  fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"

                # Verifies if the there's a primaryTable for the CRUD action.
                if @options.primaryTable?

                  # Verifies if it's demanding to save the timestamp of the alteration
                  if @options.registUpdate
                    fields = fields + ",dt_registro%3Dnow\(\)"

                  # Make the request
                  rest = new H5.Rest (
                    url: @options.url
                    table: @options.primaryTable
                    fields: fields
                    parameters: where
                    restService: "ws_updatequery.php"
                  )
                else
                  # Make the request
                  rest = new H5.Rest (
                    url: @options.url
                    table: @options.table
                    fields: fields
                    parameters: where
                    restService: "ws_updatequery.php"
                  )


                # Reload the table
                @_reloadTable()
            )
          else if @options.fields[nameField].selectArray?
            value = ''

            if @options.fields[nameField].defaultValue?
              value = @options.fields[nameField].defaultValue
            else
              $.grep @options.fields[nameField].selectArray, (e) ->
                  if e.value is nameTable.trim()
                   value = e.value

            # Add the source info for the typeahead input
            $(span).editable(
              type: 'select'
              placement: 'left'
              source: @options.fields[nameField].selectArray
              value: value
              validate: (value)=>
                if @options.fields[nameField].validation?
                  @options.fields[nameField].validation(value)
              # Function to save the editted value on the database
              url: (params)=>
                where = ""

                # Gets the key of the row, to update on the database
                $.each row.children, (key,cell) =>
                  tableCell = cell.children[0]
                  if $(tableCell).attr("data-field") is @options.uniqueField.field
                    where = @options.uniqueField.field + "%3D" + tableCell.innerHTML

                # Construct the query on the database
                if params.value?
                  fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"
                else
                  fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"

                # Verifies if the there's a primaryTable for the CRUD action.
                if @options.primaryTable?

                  # Verifies if it's demanding to save the timestamp of the alteration
                  if @options.registUpdate
                    fields = fields + ",dt_registro%3Dnow\(\)"

                  # Make the request
                  rest = new H5.Rest (
                    url: @options.url
                    table: @options.primaryTable
                    fields: fields
                    parameters: where
                    restService: "ws_updatequery.php"
                  )
                else
                  # Make the request
                  rest = new H5.Rest (
                    url: @options.url
                    table: @options.table
                    fields: fields
                    parameters: where
                    restService: "ws_updatequery.php"
                  )


                # Reload the table
                @_reloadTable()
            )
          else
            $(span).editable(
              type: 'text'
              pk: key
              value:
                nameTable
              validate: (value)=>
                if @options.fields[nameField].validation?
                  @options.fields[nameField].validation(value)
              # Function to save the editted value on the database
              url: (params)=>
                where = ""

                # Gets the key of the row, to update on the database
                $.each row.children, (key,cell) =>
                  tableCell = cell.children[0]
                  if $(tableCell).attr("data-field") is @options.uniqueField.field
                    where = @options.uniqueField.field + "%3D" + tableCell.innerHTML

                # Construct the query on the database
                fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"

                if @options.primaryTable?

                  # Verifies if it's demanding to save the timestamp of the alteration
                  if @options.registUpdate
                    fields = fields + ",dt_registro%3Dnow\(\)"

                  # Make the request
                  rest = new H5.Rest (
                    url: @options.url
                    table: @options.primaryTable
                    fields: fields
                    parameters: where
                    restService: "ws_updatequery.php"
                  )
                else
                  # Make the request
                  rest = new H5.Rest (
                    url: @options.url
                    table: @options.table
                    fields: fields
                    parameters: where
                    restService: "ws_updatequery.php"
                  )

                # Reload the table
                @_reloadTable()
            )
        else
          # If it's the unique field of the table, it must not be editable. Insert only his value, if asked for.
          span.innerHTML = nameTable

        # Add the name of the field on the database. It helps on control.
        if @options.fields[nameField].primaryField
          $(span).attr "data-field", @options.fields[nameField].primaryField
        else
          $(span).attr "data-field", nameField

        if @options.fields[nameField].isVisible? and !@options.fields[nameField].isVisible
          $(field).attr "style", "display:none;"

      # Adding the group buttons
      field = row.insertCell(i++)

      # Create the delete button and add it to a div field
      delBtn = document.createElement("a")
      delBtn.id = "delRowBtn"
      delBtn.className = "btn "
      icon = document.createElement("i")
      icon.className = "icon-trash"
      $(delBtn).append icon

      # Add the div field to the action field on the row
      actionBtns = document.createElement("div")
      $(actionBtns).append delBtn

      # Add the action field to the row on the table
      $(field).append actionBtns

      # Gives the delete button the delete function
      @_delFields(delBtn, row)

      # Saves the last row of the table, for future deletions
      @_lastRow = row

    # Creating Add Button
    addBtn = document.createElement("a")
    addBtn.id = "addBotao"
    addBtn.className = "btn"

    iconBtn = document.createElement("i")
    iconBtn.className = "icon-plus"
    $(addBtn).append iconBtn

    unless @options.buttons.minimize and
      not @options.buttons.maximize and
      not @options.buttons.close
        $(addBtn).append " Adicionar"

    @_addBtn = addBtn

    @_addTypeBtn = null

    if @options.insertNewType?
      # Creating Add Type Button
      addTypeBtn = document.createElement("a")
      addTypeBtn.id = "addTipoBotao"
      addTypeBtn.className = "btn"

      iconBtn = document.createElement("i")
      iconBtn.className = "icon-plus"
      $(addTypeBtn).append iconBtn

      defaultName = " Novo Tipo"

      unless @options.buttons.minimize and
        not @options.buttons.maximize and
        not @options.buttons.close
          $(addTypeBtn).append defaultName

      $(addTypeBtn).editable(
        type: 'text'
        value: ""
        placement: 'right'
        display: false
        url: (params)=>

          if params.value isnt ''
            # Insert the new product
            rest = new H5.Rest (
              url: @options.url
              table: @options.insertNewType
              fields: " (nome) values ('" + params.value + "') "
              restService: "ws_insertquery.php"
            )

          @_reloadSearchData()
      )

      @_addTypeBtn = addTypeBtn

    # Add the button to the table, on top.
    $(@_rightCtrl).append addBtn
    $(@_rightCtrl).append addTypeBtn

    # Gives the add buttons the add fuction
    @_addFields()
    # @_addNewType()

    # Add the created table to the container on the page
    $(@_boxContent).append @_table


    # Create the header of the table
    head = @_table.createTHead()
    row = head.insertRow(0)
    i = 0
    $.each @options.fields, (key,value) ->
      field = row.insertCell(i++)
      if !(value.isVisible? and !value.isVisible)
        field.innerHTML = "<strong>" + value.columnName + "</strong>"
      else
        $(field).attr 'style', 'display:none;'
    field = row.insertCell(i++)
    $(field).width(37)


  # Function that add the add function to add buttons.
  _addNewType: ()->
    $(@_addTypeBtn).on "click", (event) =>

      event.preventDefault()


      # $(@_addTypeBtn).editable(
      #   type: 'text'
      #   value: value
      #   placement: 'right'
      # )



  # Function that add the add function to add buttons.
  _addFields: ()->
    $(@_addBtn).on "click", (event) =>

      event.preventDefault()

      # Create the new row on the table.
      newRow = document.createElement("tr")

      i = 0

      $.each @options.fields, (key, properties) =>

        td = newRow.insertCell(i++)
        span = document.createElement("span")

        # Verifies if the table has a unique field (primary key), and if it has, if it is editable
        if (key isnt @options.uniqueField.field or @options.uniqueField.insertable)
          value = ""

          if properties.defaultValue?
            value = properties.defaultValue

          if properties.searchData?
            $(span).editable(
              type: 'typeahead'
              value: value
              source: properties.searchData
              placement: 'right'
            )
          else if properties.selectArray?
            $(span).editable(
              type: 'select'
              value: value
              source: properties.selectArray
              placement: 'left'
            )
          else
            $(span).editable(
              type: 'text'
              value: value
            )

        if properties.primaryField?
          dataField = properties.primaryField
        else
          dataField = key

        if properties.isVisible? and !properties.isVisible
          $(td).attr "style", "display:none"

        # Stores the name of the field on the database
        $(span).attr "data-field", dataField


        # Add the new field to the new row.
        $(td).append span


      # Creates and configures the new delete button
      delBtn = document.createElement("a")
      delBtn.id = "deletarBotaoTabela"
      delBtn.className = "btn "
      $(delBtn).attr("style","display:none")
      icon = document.createElement("i")
      icon.className = "icon-trash "
      $(delBtn).append icon
      # Adds the delete function to the delete button
      @_delFields(delBtn, newRow)

      # Creates and configures the new save button
      saveBtn = document.createElement("a")
      saveBtn.id = "salvarBotaoTabela"
      saveBtn.className = "btn btn-primary editable-submit "
      icon = document.createElement("i")
      icon. className = "icon-ok "
      $(saveBtn).append icon
      # Adds the save function to the save button
      @_saveFields(saveBtn, delBtn, newRow)

      # Adds both to the new action div
      div = document.createElement("div")
      $(div).append saveBtn
      $(div).append delBtn

      # Adds the action div to the new row
      td = newRow.insertCell(i++)
      $(td).append div

      # Adds the new row to the table.
      if @_lastRow?
        $(newRow).insertAfter $(@_lastRow)
      else
        # tbody = document.getElementsByClassName("table")[0].appendChild(document.createElement('tbody'))
        tbody = document.getElementsByName(_this.options.container)[0].getElementsByClassName('table')[0].appendChild(document.createElement('tbody'))
        tbody.appendChild newRow


      # Stores the new row as the last row of the table
      @_lastRow = newRow

  # Function that add the delete function to delete buttons
  _delFields: (delBtn, tableRow)->
    $(delBtn).on "click", (event) =>

      event.preventDefault()

      # Asks for the user permition to alter the database
      if(confirm "Você deseja excluir essa linha do banco de dados?" )
        # If the deleting row is the last row of the table, stores the previous one as last row
        if @_lastRow is tableRow
          if @_table.rows.length > 2
            @_lastRow = @_table.rows.item(@_table.rows.length-2)
          else
            @_lastRow = null

        where = ""

        # Gets the key of the row to be deleted and creates the query for the deletion
        $.each tableRow.children, (key,cell) =>
          span = cell.children[0]
          if $(span).attr("data-field") is @options.uniqueField.field
            where = @options.uniqueField.field + "%3D" + span.innerHTML

        table = ''

        if @options.primaryTable?
          table = @options.primaryTable
        else
          table =  @options.table

        # Send the request for the deletion
        rest = new H5.Rest (
          url: @options.url
          table: table
          parameters: where
          restService: "ws_deletequery.php"
        )

        # Reload table
        @_reloadTable()

      else
        alert "Operação cancelada"


  # Function that add the save function to save buttons
  _saveFields: (saveBtn, delBtn, tableRow) ->
    $(saveBtn).on "click", (event) =>

      event.preventDefault()

      save = true
      fields = ""
      values = ""
      i = 0

      $.each @options.fields, (key, properties) =>
        span = tableRow.children[i].children[0]

        # Verifies if the field is a unique field and if it's editable. If it is, stores in the query string
        if @options.uniqueField.field isnt key or @options.uniqueField.insertable

          if properties.primaryField?
            fields +=  properties.primaryField + ","
          else
            fields +=  key + ","

          if properties.searchData?
            val = null
            val = $.grep properties.searchData, (e)=>
              if e.text is span.innerHTML
                e

            # Run the validation actions so that the values inserted can be saved

            if properties.validation?
              ret = properties.validation(span.innerHTML)

              if ret isnt ""
                save = false
                alert properties.columnName + ": " + ret

            if !$.isEmptyObject(val)
              values += "'" + val[0].value + "',"
          else if properties.selectArray?

            val = null
            val = $.grep properties.selectArray, (e)=>
              if e.text == span.innerHTML
                return e.value

            values += "'" + val[0].value + "',"
          else
            # Run the validation actions so that the values inserted can be saved
            if properties.validation?
              ret = properties.validation(span.innerHTML)

              if ret isnt ""
                save = false
                alert properties.columnName + ": " + ret


            values += "'" + span.innerHTML + "',"


          if @options.primaryTable?
            $(span).editable(
              validate: (value) =>
                if @options.fields[key].validation?
                  @options.fields[key].validation(value)
              url: (params)=>
                where = ""

                # Gets the key to the row.
                $.each row.children, (key,cell) =>
                  tableCell = cell.children[0]
                  if $(tableCell).attr("data-field") is @options.uniqueField.field
                    where = @options.uniqueField.field + "%3D" + tableCell.innerHTML

                # Creates the string query
                fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"

                # Verifies if it's demanding to save the timestamp of the alteration
                if @options.registUpdate
                  fields = fields + ",dt_registro%3Dnow\(\)"

                # Sends the request
                rest = new H5.Rest (
                  url: @options.url
                  table: @options.primaryTable
                  fields: fields
                  parameters: where
                  restService: "ws_updatequery.php"
                )

                # Reload the table
                @_reloadTable()
            )
          else
            $(span).editable(
              validate: (value) =>
                if @options.fields[key].validation?
                  @options.fields[key].validation(value)
              url: (params)=>
                where = ""

                # Gets the key of the row, to update on the dtabase
                $.each row.children, (key,cell) =>
                  tableCell = cell.children[0]
                  if $(tableCell).attr("data-field") is @options.uniqueField.field
                    where = @options.uniqueField.field + "%3D" + tableCell.innerHTML

                # Construct the query on the database
                if params.value?
                  fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"
                else
                  fields = $(span).attr("data-field") + "%3D'" +  params.value + "'"

                # Verifies if it's demanding to save the timestamp of the alteration
                if @options.registUpdate
                  fields = fields + ",dt_registro%3Dnow\(\)"

                # Make the request
                rest = new H5.Rest (
                  url: @options.url
                  table: @options.table
                  fields: fields
                  parameters: where
                  restService: "ws_updatequery.php"
                )

                # Reload the table
                @_reloadTable()
            )


        # Verifies if the passed field has a value
        # in case it doesnt have any value ("" or Empty), the field is not added to the query string
        else if span.innerHTML isnt "" and span.innerHTML isnt "Vazio"
          fields +=  "" + key + ","
          values += "'" + span.innerHTML + "',"

        i++

      # Removes the last comma on the string
      fields = fields.substring(0,fields.length-1)
      values = values.substring(0,values.length-1)

      # Verifies if it's demanding to save the timestamp of the alteration
      if @options.registUpdate
        fields = fields + ",dt_registro"
        values = values + ",now()"

      # Finish creating the query string for the insert function
      fields = " (" + fields + ") values (" + values + ") "

      if save
        # Send the request for the database to insert a new row on the table
        if @options.primaryTable?
          rest = new H5.Rest (
            url: @options.url
            table: @options.primaryTable
            fields: fields
            # parameters: @options.parameters
            restService: "ws_insertquery.php"
          )
        else
          rest = new H5.Rest (
            url: @options.url
            table: @options.table
            fields: fields
            # parameters: @options.parameters
            restService: "ws_insertquery.php"
          )


        # Reload table
        @_reloadTable()

  # Function that reloads the table on the page
  _reloadTable : ()->

    # For each child element of the container, remove it from the page.
    $.each $(@_container.children), (key,childs) ->
      $(childs).remove()

    # Cleaning the table right before reloading it
    @._lastRow = null

    # Calls the constructor of the classe
    @constructor(@options)

  # Function that reloads the table on the page
  _reloadSearchData : ()->

    # For each child element of the container, remove it from the page.
    $.each @options.fields, (key,childs) =>
      if childs.searchData?

        # Get the product name from the database, by ajax
        rest = new H5.Rest (
          url: @options.url
          table: "produto_outro"
          fields: "id_produto_outro,nome"
          order: "nome"
        )

        searchData = []

        $.each rest.data, ()->

          elements =
            value: @id_produto_outro
            text: $.trim(@nome)

          searchData.push(elements)

        childs.searchData = searchData
