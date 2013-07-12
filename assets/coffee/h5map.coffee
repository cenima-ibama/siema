bingKey = "AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h"
bingaerial = new L.BingLayer(bingKey,
  type: "Aerial"
  attribution: ""
)

bingroad = new L.BingLayer(bingKey,
  type: "Road"
  attribution: ""
)

binghybrid = new L.BingLayer(bingKey,
  type: "AerialWithLabels"
  attribution: ""
)

openstreetUrl = "http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png"
openstreetSub = ['otile1','otile2','otile3','otile4']
openstreet = new L.TileLayer(openstreetUrl,
  maxZoom: 18
  subdomains: openstreetSub
)

openstreetMini = new L.TileLayer(openstreetUrl,
  minZoom: 0
  maxZoom: 11
  subdomains: openstreetSub
)

terrasIndigenas = new L.TileLayer.WMS("http://siscom.ibama.gov.br/geo-srv/cemam/wms",
  layers: "cemam:t_indigena"
  format: "image/png"
  transparent: true
)

ucFederais = new L.TileLayer.WMS("http://siscom.ibama.gov.br/geo-srv/cemam/wms",
  layers: "cemam:uc_federal"
  format: "image/png"
  transparent: true
)

# update size of the map container
$( '#map-container' ).width( $( window ).width() )
$( '#map-container' ).height( $( window ).height() - $('#navbar').height() - 1)

H5.Map.base = new L.Map("map-container",
  center: new L.LatLng(-10.0, -58.0)
  zoom: 6
  layers: [binghybrid]
  zoomControl: true
)

H5.Map.minimap = new L.Control.MiniMap(openstreetMini, { toggleDisplay: true, zoomLevelOffset: -4 }).addTo(H5.Map.base)

# add custom attribution
H5.Map.base.attributionControl.setPrefix "Hexgis Hash5"

# add scale
L.control.scale().addTo H5.Map.base

# display stations
H5.Map.layer.alerta = new H5.Leaflet.Postgis(
  url: "../painel/rest/"
  geotable: H5.DB.alert.table
  fields: "id_des, tipo, data_imagem, area_km2, dominio"
  srid: 4326
  geomFieldName: "shape"
  popupTemplate: (properties) ->
    html = '<div class="iw-content"><h4>' + properties.id_des + '</h4>'
    html += '<h5>' + properties.tipo + '</h5>'
    html += '<table class="condensed-table bordered-table zebra-striped"><tbody>'
    html += '<tr><th>Data: </th><td>' + properties.data_imagem.split(" ", 1) + '</td></tr>'
    html += '<tr><th>Área: </th><td>' + properties.area_km2+ '</td></tr>'
    if properties.dominio.length > 1
      html += '<tr><th>Domínio: </th><td>' + properties.dominio + '</td></tr>'
    html += '</tbody></table></div>'
    return html
  singlePopup: true
  where: "ano = '2013'"
  showAll: false
  limit: 200
  scaleRange: [9, 20]
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

customMarker = L.Icon.extend(
  options:
    iconUrl: "../painel/assets/img/ibama_marker.png"
    shadowUrl: null
    iconSize: new L.Point(0, 0)
    iconAnchor: new L.Point(0, 0)
    popupAnchor: new L.Point(0, 0)
    clickable: false
)

# display stations
H5.Map.layer.clusters = new H5.Leaflet.Postgis(
  url: "../painel/rest/"
  geotable: H5.DB.alert.table
  fields: "id_des"
  srid: 4326
  geomFieldName: "centroide"
  showAll: true
  cluster: true
  popupTemplate: null
  where: "ano = '2013'"
  symbology:
    type: "single"
    vectorStyle:
      icon: new customMarker()
)
H5.Map.layer.clusters.setMap H5.Map.base

H5.Map.layerList = new H5.Leaflet.LayerControl(
  "OSM": openstreet
  "Bing Aerial": bingaerial
  "Bing Road": bingroad
  "Bing Hybrid": binghybrid
  "Alerta [Indicadores]": H5.Map.layer.clusters.layer
  "Alerta [Polígonos]": H5.Map.layer.alerta.layer
)

# add layer menu
H5.Map.base.addControl H5.Map.layerList
