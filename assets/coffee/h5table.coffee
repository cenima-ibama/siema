window.parent.window.parent.H5.Data.restURL = "//" + document.location.host + document.location.pathname + "/rest"
$(document).ready ->

  # Adding tables to the configuration panel on the administrator area

  table = new H5.Table (
    container: "table1"
    title: "Tipo de Eventos"
    url: window.parent.H5.Data.restURL
    table: "tipo_evento"
    fields:
      id_tipo_evento:
        tableName: "id_tipo_evento"
        columnName: "Identificador"
        isVisible: true
        validation: (string)->
          if !$.isNumeric(string)
            return "Não é número minino!!!"
          else
            return null
      nome:
        tableName: "nome"
        columnName: "Tipo do Evento"
        isVisible: true
        validation: null

    uniqueField:
      field: "id_tipo_evento"
      insertable: true
    buttons:
      minimize: true
      maximize: true
      export: true
  )

  table = new H5.Table (
    container: "table2"
    title: "Tipo de Produtos"
    url: window.parent.H5.Data.restURL
    table: "tipo_produto"
    fields:
      id_tipo_produto:
        tableName: "id_tipo_produto"
        columnName: "Identificador"
        validation: (string)->
          if !$.isNumeric(string)
            return "Não é número diabos!!!!!"
          else
            return null
      nome:
        tableName: "nome"
        columnName: "Tipo do Produto"
        validation: null
    uniqueField:
      field: "id_tipo_produto"
      insertable: false
    buttons:
      minimize: true
      maximize: true
      export: true
  )

  table = new H5.Table (
    container: "table3"
    title: "Fontes de Informação"
    url: window.parent.H5.Data.restURL
    table: "tipo_fonte_informacao"
    fields:
      id_tipo_fonte_informacao:
        tableName: "id_tipo_fonte_informacao"
        columnName: "Identificador"
        validation: (string)->
          if !$.isNumeric(string)
            return "Não é número!!!"
          else
            return null
      nome:
        tableName: "nome"
        columnName: "Fonte da Informação"
        validation: null
    uniqueField:
      field: "id_tipo_fonte_informacao"
      insertable: true
    buttons:
      minimize: true
      maximize: true
      export: true
  )
