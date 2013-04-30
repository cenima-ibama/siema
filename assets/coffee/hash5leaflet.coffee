get_param = (param) ->
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

processTMS = (data) ->
  onEachFeature = (feature, layer) ->
    popupContent = "<p>I started out as a GeoJSON " + feature.geometry.type + ", but now I'm a Leaflet vector!</p>"
    popupContent += feature.properties.popupContent  if feature.properties and feature.properties.popupContent
    layer.bindPopup popupContent
    layer.on "mouseover click", (e) ->
      checkLayer = (layerId) ->
        i = 0
        while i < rapidEye.listLayers.length
          return false  if layerId is rapidEye.listLayers[i]
          i++
        true
      tmsUrl = feature.properties.url_tiles + "{z}/{x}/{y}.png"
      layer.setStyle
        fillColor: "transparent"
        stroke: false

      if checkLayer(layer._leaflet_id)

        # add tile inside polygon
        rapidEye[rapidEye.count].setUrl tmsUrl
        rapidEye[rapidEye.count].redraw()

        # add layer id to the list of layers
        rapidEye.listLayers.push layer._leaflet_id

        # compare if number of tiles is bigger then the
        # number of layers
        rapidEye.listLayers.shift()  if rapidEye.listLayers.length > rapidEye.numberOfLayers

        # make next tile avaliable
        rapidEye.count++

        # if the tile number is bigger then the number of layers
        # reset the controller
        rapidEye.count = 1  if rapidEye.count > rapidEye.numberOfLayers

    layer.on "mouseout", (e) ->
      layer.setStyle stroke: true

  # create object for the tiles
  rapidEye = {}
  rapidEye.count = 1
  rapidEye.listLayers = []
  rapidEye.numberOfLayers = 4
  i = 1
  while i <= rapidEye.numberOfLayers
    rapidEye[i] = new L.TileLayer("",
      minZoom: 3
      maxZoom: 17
      tms: true
    )
    rapidEye[i].addTo map
    i++
  geojson = L.geoJson(data,
    style:
      fillColor: "transparent"
      color: "purple"
      weight: 4

    onEachFeature: onEachFeature
  )
  geojson.addTo map

bingaerial = new L.BingLayer("AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h",
  type: "Aerial"
  attribution: ""
)

bingroad = new L.BingLayer("AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h",
  type: "Road"
  attribution: ""
)

binghybrid = new L.BingLayer("AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h",
  type: "AerialWithLabels"
  attribution: ""
)

openstreetUrl = "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
openstreetAttribution = ""
openstreet = new L.TileLayer(openstreetUrl,
  maxZoom: 18
  attribution: openstreetAttribution
)

cloudmadeUrl = "http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png"
cloudmadeAttribution = ""
cloudmade = new L.TileLayer(cloudmadeUrl,
  maxZoom: 18
  attribution: cloudmadeAttribution
)

map = new L.Map("map-container",
  center: new L.LatLng(-10.0, -58.0)
  zoom: 6
  layers: [bingroad]
  zoomControl: true
)
$.getJSON "geojson/rapideye/pampas.json", (data) ->
  processTMS data

baseMaps =
  "Bing Aerial": bingaerial
  "Bing Road": bingroad
  "Bing Hybrid": binghybrid
  OSM: openstreet

overlayMaps = {}

# add layer menu
L.control.layers(baseMaps, overlayMaps).addTo map

# add custom attribution
map.attributionControl.setPrefix "Hexgis Hash5"

# add scale
L.control.scale().addTo map

# display stations
alertaLayer = new lvector.PRWSF(
  url: "../painel/rest/"
  geotable: "alerta"
  fields: "objectid"
  srid: 4618
  geomFieldName: "shape"
  showAll: true
  popupTemplate: "<div class=\"iw-content\"><center><h3>{objectid}</h3></center></div>"
  singlePopup: true
  where: "ano = '2013'"
  symbology:
    type: "single"
    vectorOptions:
      fillColor: "#ff0000"
      fillOpacity: 0.6
      weight: 1.2
      color: "#ff0000"
      opacity: 0.8
)
alertaLayer.setMap map
