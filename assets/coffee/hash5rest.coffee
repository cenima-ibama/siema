class H5.Rest

  options:
    url: null
    restService: null
    table: null
    fields: null
    parameters: null
    order: null
    limit: null

  data: null

  constructor: (options) ->
    # If the url wasn't passed with a trailing /, add it.
    if options.url.substr(options.url.length - 1, 1) != "/"
      options.url += "/"

    # configure object with the options
    @options = $.extend({}, @options, options)

  request: (service) ->
    if service
      @options.restService = "ws_geo_" + service + ".php"
    else
      @options.restService = "ws_geo_attributequery.php"

    url = @options.url + "v1/" + @options.restService

    query = {}
    if @options.table then query.table = @options.table
    if @options.parameters then query.parameters = @options.parameters
    if @options.fields then query.fields = @options.fields
    if @options.order then query.order = @options.order
    if @options.limit then query.limit = @options.limit

    @_get(url, query)

    return @data

  getURLParam: (param) ->
    search = window.location.search.substring(1)
    compareKeyValuePair = (pair) ->
      key_value = pair.split("=")
      decodedKey = decodeURIComponent(key_value[0])
      decodedValue = decodeURIComponent(key_value[1])
      return decodedValue  if decodedKey is param
      null

    comparisonResult = null
    if search.indexOf("&") > -1
      params = search.split("&")
      i = 0

      while i < params.length
        comparisonResult = compareKeyValuePair(params[i])
        break  if comparisonResult isnt null
        i++
    else
      comparisonResult = compareKeyValuePair(search)

    return comparisonResult

  _get: (url, query) ->
    $.ajax
      type: "GET"
      async: false
      url: url
      data: query
      dataType: "jsonp"
      success: (data) =>
        @_done(data)
      error: (error, status, desc) ->
        console.log status, desc

  _done: (data) ->
    @data = data
