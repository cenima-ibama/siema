class @H5

  version: 0.6

  author: "Helmuth Saatkamp <helmuthdu@gmail.com>"

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
    comparisonResult
