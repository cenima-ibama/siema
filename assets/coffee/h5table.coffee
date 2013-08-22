$(document).ready ->

  # Adding tables to the configuration panel on the administrator area

  table = new H5.Table (
    container: "table1"
    title: "Tipo de Eventos"
    url: "../../../siema/rest_v2"       # Alter to the defined url
    table: "tipo_evento"
    fields:
      id_tipo_evento:
        columnName: "Identificador"
        validation: (string)->
          if !$.isNumeric(string)
            return "Não é número minino!!!"
          else
            return null
      nome:
        columnName: "Tipo do Evento"
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
    url: "../../../siema/rest_v2"       # Alter to the defined url
    table: "tipo_produto"
    fields:
      id_tipo_produto:
        columnName: "Identificador"
        validation: (string)->
          if !$.isNumeric(string)
            return "Não é número diabos!!!!!"
          else
            return null
      nome:
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
    url: "../../../siema/rest_v2"       # Alter to the defined url
    table: "tipo_fonte_informacao"
    fields:
      id_tipo_fonte_informacao:
        columnName: "Identificador"
        validation: (string)->
          if !$.isNumeric(string)
            return "Não é número!!!"
          else
            return null
      nome:
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