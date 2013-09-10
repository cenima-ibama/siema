# LAYERS {{{
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

bingMini = new L.BingLayer(bingKey,
  type: "AerialWithLabels"
  attribution: ""
  minZoom: 1
  maxZoom: 11
)

openstreetUrl = "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png"
openstreet = new L.TileLayer(openstreetUrl,
  maxZoom: 18
  attribution: ""
)

openmapquestUrl = "http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png"
openmapquestSub = ['otile1','otile2','otile3','otile4']
openmapquest = new L.TileLayer(openmapquestUrl,
  maxZoom: 18
  subdomains: openmapquestSub
)

geoserverUrl = "http://siscom.ibama.gov.br/geo-srv/cemam/wms"

terrasIndigenas = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:t_indigena"
  format: "image/png"
  transparent: true
)

unidadeConservacao = new L.TileLayer.WMS(geoserverUrl,
  layers: "ceman:uc_federal"
  format: "image/png"
  transparent: true
)

blocoR9 = new L.TileLayer.WMS(geoserverUrl,
  layers: "ceman:bloco_r9"
  format: "image/png"
  transparent: true
)

blocoExploratorio = new L.TileLayer.WMS(geoserverUrl,
  layers: "ceman:bloco_exploratorio"
  format: "image/png"
  transparent: true
)

biomaIBGE = new L.TileLayer.WMS(geoserverUrl,
  layers: "ceman:bioma_ibge"
  format: "image/png"
  transparent: true
)

portoTerminal = new L.TileLayer.WMS(geoserverUrl,
  layers: "ceman:porto_terminal"
  format: "image/png"
  transparent: true
)

# }}}
# SCREEN SIZE {{{
# update size of the map container
$( '#map' ).width( $( window ).width() )
$( '#map' ).height( $( window ).height() - $('#navbar').height())

# Detect whether device supports orientationchange event,
# otherwise fall back to the resize event.
supportsOrientationChange = "onorientationchange" of window
orientationEvent = (if supportsOrientationChange then "orientationchange" else "resize")

# update chart if orientation or the size of the screen changed
window.addEventListener orientationEvent, (->
  $( '#map' ).width( $( window ).width() )
  $( '#map' ).height( $( window ).height() - $('#navbar').height())
), false
# }}}
# MAP LAYER {{{
H5.Map.base = new L.Map("map",
  center: new L.LatLng(-10.0, -58.0)
  zoom: 6
  layers: [binghybrid]
)

H5.Map.minimap = new L.Control.MiniMap(bingMini,
  toggleDisplay: true
  zoomLevelOffset: -4
  autoToggleDisplay: false
).addTo(H5.Map.base)

# add custom attribution
H5.Map.base.attributionControl.setPrefix "Hexgis Hash5"

# add scale
new L.control.scale().addTo(H5.Map.base)

# add fullscreen control
new L.control.fullscreen(
  position: 'topleft'
  title: 'Fullscreen'
).addTo(H5.Map.base)

new L.control.GeoSearch(
  provider: new L.GeoSearch.Provider.Google
  searchLabel: "Endereço, Estado - UF"
  notFoundMessage: "Endereço não encontrado."
  showMarker: false
).addTo(H5.Map.base)

new L.control.locate(
  position: "topleft"
  drawCircle: true
  follow: false
  stopFollowingOnDrag: false
  circleStyle: {}
  markerStyle: {}
  followCircleStyle: {}
  followMarkerStyle: {}
  metric: true
  onLocationError: (err) ->
    alert err.message

  onLocationOutsideMapBounds: (context) ->
    alert context.options.strings.outsideMapBoundsMsg

  setView: true
  strings:
    title: "Localizar minha posição"
    popup: "Você está a {distance} {unit} deste lugar"
    outsideMapBoundsMsg: "Você está em um outra dimensão! o.O"

  locateOptions: {}
).addTo(H5.Map.base)

# display somethings
H5.Data.restURL = "http://" + document.domain + "/siema/rest"

# H5.Map.layer.alerta = new H5.Leaflet.Postgis(
#   url: H5.Data.restURL
#   geotable: H5.DB.alert.table
#   fields: "id_des, tipo, data_imagem, area_km2, dominio"
#   srid: 4326
#   geomFieldName: "shape"
#   popupTemplate: (properties) ->
#     html = '<div class="iw-content"><h4>' + properties.id_des + '</h4>'
#     html += '<h5>' + properties.tipo + '</h5>'
#     html += '<table class="condensed-table bordered-table zebra-striped"><tbody>'
#     html += '<tr><th>Data: </th><td>' + properties.data_imagem.split(" ", 1) + '</td></tr>'
#     html += '<tr><th>Área: </th><td>' + properties.area_km2+ '</td></tr>'
#     if properties.dominio.length > 1
#       html += '<tr><th>Domínio: </th><td>' + properties.dominio + '</td></tr>'
#     html += '</tbody></table></div>'
#     return html
#   singlePopup: true
#   where: "ano = '2013'"
#   showAll: false
#   limit: 200
#   scaleRange: [9, 20]
#   symbology:
#     type: "single"
#     vectorStyle:
#       fillColor: "#ff0000"
#       fillOpacity: 0.6
#       weight: 4.0
#       color: "#ff0000"
#       opacity: 0.8
# )
# H5.Map.layer.alerta.setMap H5.Map.base

# display acidentes
H5.Map.layer.acidentes = new H5.Leaflet.Postgis(
  url: H5.Data.restURL
  geotable: "tmp_pon"
  fields: "id_ocorrencia"
  srid: 4326
  geomFieldName: "shape"
  showAll: true
  cluster: true
  popupTemplate: null
  focus: false
  symbology:
    type: "single"
    vectorStyle:
      circleMarker: true
      radius: 6
      fillColor: "#ff0000"
      fillOpacity: 0.8
      weight: 4.0
      color: "#ff0000"
      opacity: 0.8
)
H5.Map.layer.acidentes.setMap H5.Map.base

new H5.Leaflet.LayerControl(
  "OSM":
    layer: openstreet
  "Bing Aerial":
    layer: bingaerial
  "Bing Road":
    layer: bingroad
  "Bing Hybrid":
    layer: binghybrid
,
  "Terras Indígenas":
    layer: terrasIndigenas
    overlayControl: false
  "Unidade de Convservação":
    layer: unidadeConservacao
    overlayControl: false
  "Bloco R9":
    layer: blocoR9
    overlayControl: false
  "Bloco Exploratório":
    layer: blocoExploratorio
    overlayControl: false
  "Bioma IBGE":
    layer: biomaIBGE
    overlayControl: false
  "Portos e Terminais":
    layer: portoTerminal
    overlayControl: false
  "Acidentes Ambientais":
    layer: H5.Map.layer.acidentes.layer
).addTo(H5.Map.base)

# }}}
