bingApiKey = "AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h"

bingaerial = new L.BingLayer(bingApiKey,
  type: "Aerial"
  attribution: ""
)

bingroad = new L.BingLayer(bingApiKey,
  type: "Road"
  attribution: ""
)

binghybrid = new L.BingLayer(bingApiKey,
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

$(".map").addClass "overlay"
map = new L.Map("map-container",
  center: new L.LatLng(-10.0, -58.0)
  zoom: 6
  layers: [bingroad]
  zoomControl: true
)
$(".map").removeClass "overlay"

baseMaps =
  "Bing Aerial": bingaerial
  "Bing Road": bingroad
  "Bing Hybrid": binghybrid
  "OSM": openstreet

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
