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

H5.Map.base = new L.Map("map-container",
  center: new L.LatLng(-10.0, -58.0)
  zoom: 6
  layers: [bingroad]
  zoomControl: true
)

# add custom attribution
H5.Map.base.attributionControl.setPrefix "Hexgis Hash5"

# add scale
L.control.scale().addTo H5.Map.base

# display stations
H5.Map.layer.alerta = new H5.Leaflet.Postgis(
  url: "../painel/rest/"
  geotable: H5.DB.alert.table
  fields: "objectid"
  srid: 4618
  geomFieldName: "shape"
  showAll: true
  popupTemplate: "<div class=\"iw-content\"><center><h3>{objectid}</h3></center></div>"
  singlePopup: true
  where: "ano = '2013'"
  symbology:
    type: "single"
    vectorStyle:
      fillColor: "#ff0000"
      fillOpacity: 0.6
      weight: 4.0
      color: "#ff0000"
      opacity: 0.8
)
H5.Map.layer.alerta.setMap H5.Map.base

H5.Map.layerList = new H5.Leaflet.LayerControl(
  "OSM": openstreet
  "Bing Aerial": bingaerial
  "Bing Road": bingroad
  "Bing Hybrid": binghybrid
)

# add layer menu
H5.Map.base.addControl H5.Map.layerList
