# INIT {{{
H5.Map =
  base: null
  layer: {}
  layerList: null

H5.Leaflet = {}
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
# BASE LAYERS {{{
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
# }}}
# OVERLAYER {{{
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

blocoExploratorio = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:blocoexploratorio"
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

marisma = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:marisma"
  format: "image/png"
  transparent: true
)

restinga = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:restinga"
  format: "image/png"
  transparent: true
)

recifes = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:recife_total"
  format: "image/png"
  transparent: true
)

mangues = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:mangue_siema"
  format: "image/png"
  transparent: true
)

lagunas = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:laguna"
  format: "image/png"
  transparent: true
)

estuario = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:estuario"
  format: "image/png"
  transparent: true
)

duna = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:duna"
  format: "image/png"
  transparent: true
)

costao = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:costao"
  format: "image/png"
  transparent: true
)

banhado = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:banhado"
  format: "image/png"
  transparent: true
)

hidrografia = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:hidrografia_lin"
  format: "image/png"
  transparent: true
)

eixoDutoviario = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:eixo_dutoviario"
  format: "image/png"
  transparent: true
)

eixoFerroviario = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:eixo_ferroviario"
  format: "image/png"
  transparent: true
)

eixoRodoviario = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:eixo_rodoviario"
  format: "image/png"
  transparent: true
)

estacaoFerroviaria = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:estacao_ferroviaria"
  format: "image/png"
  transparent: true
)

refinaria = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:refinaria"
  format: "image/png"
  transparent: true
)

ponteTunel = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:ponte_tunel"
  format: "image/png"
  transparent: true
)

marTerritorial = new L.TileLayer.WMS(geoserverUrl,
  layers: "cemam:mar_territorial"
  format: "image/png"
  transparent: true
)

restURL = "http://" + document.domain + "/siema/rest"

# display acidentes
acidentes = new L.VectorLayer.Postgis (
  url: restURL
  geotable: "vw_ocorrencia_shape"
  fields: "id_ocorrencia, municipio, estado, data_acidente, origem_acidente, tipo_eventos, produtos"
  srid: 4326
  geomFieldName: "shape"
  showAll: true
  cluster: true
  popupTemplate: (properties) ->
    html = '<div class="iw-content"><h4>' + properties.id_ocorrencia + '</h4>'
    html += '<table class="condensed-table bordered-table zebra-striped"><tbody>'

    text = ""
    if properties.municipio then text += properties.municipio
    text += " - "
    if properties.estado then text += properties.estado
    if !properties.municipio and !properties.estado then text = "Sem informação"
    html += '<tr><th>Municipio - Estado: </th><td>' + text + '</td></tr>'

    text = ""
    if properties.data_acidente then text += properties.data_acidente
    if !properties.data_acidente then text = "Sem informação"
    html += '<tr><th>Data: </th><td>' + text + '</td></tr>'

    text = ""
    if properties.origem_acidente isnt "{}" then text += properties.origem_acidente.replace(/[{}]/g,"")
    if properties.origem_acidente is "{}" then text = "Sem informação"
    html += '<tr><th>Origem do Acidente: </th><td>' + text  + '</td></tr>'

    text = ""
    if properties.tipo_eventos isnt "{}" then text += properties.tipo_eventos.replace(/[{}]/g,"")
    if properties.tipo_eventos is "{}" then text = "Sem informação"
    html += '<tr><th>Tipo de Evento: </th><td>' + text + '</td></tr>'

    text = ""
    if properties.produtos isnt "{}" then text += properties.produtos.replace(/[{}]/g,"")
    if properties.produtos is "{}" then text = "Sem informação"
    html += '<tr><th>Produtos Envolvidos: </th><td>' + text + '</td></tr>'

    html += '</tbody></table></div>'
    return html
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
acidentes.setMap H5.Map.base
# }}}
# CONTROLLERS {{{

# add scale
new L.control.scale().addTo(H5.Map.base)

# add fullscreen control
new L.control.fullscreen(
  position: 'topleft'
  title: 'Fullscreen'
).addTo(H5.Map.base)

new L.Control.GeoSearch(
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


# H5.Map.layer.alerta = new L.VectorLayer.Postgis (
#   url: restURL
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

# icons
iconsURL = "http://" + document.domain + "/siema/assets/img/icons/"

controlswitch = new L.control.switch(
  "OSM":
    layer: openstreet
  "Bing Aerial":
    layer: bingaerial
  "Bing Road":
    layer: bingroad
  "Bing Hybrid":
    layer: binghybrid
,
  "Recifes":
    layer: recifes
    tab: "water"
  "Mangues":
    layer: mangues
    tab: "water"
  "Lagunas":
    layer: lagunas
    tab: "water"
  "Estuário":
    layer: estuario
    tab: "water"
  "Dunas":
    layer: duna
    tab: "water"
  "Costão":
    layer: costao
    tab: "water"
  "Banhado":
    layer: banhado
    tab: "water"
  "Marisma":
    layer: marisma
    tab: "water"
  "Restinga":
    layer: restinga
    tab: "water"
  "Bloco Exploratório":
    layer: blocoExploratorio
    tab: "factory"
  "Portos e Terminais":
    layer: portoTerminal
    tab: "factory"
  "Eixos Dutoviários":
    layer: eixoDutoviario
    tab: "factory"
  "Eixos Ferroviários":
    layer: eixoFerroviario
    tab: "factory"
  "Eixos Rodoviários":
    layer: eixoRodoviario
    tab: "factory"
  "Estações Ferroviárias":
    layer: estacaoFerroviaria
    tab: "factory"
  "Refinarias":
    layer: refinaria
    tab: "factory"
  "Pontes e Túneis":
    layer: ponteTunel
    tab: "factory"
  "Terras Indígenas":
    layer: terrasIndigenas
  "Unidade de Conservação":
    layer: unidadeConservacao
  "Bioma IBGE":
    layer: biomaIBGE
  "Hidrografia":
    layer: hidrografia
  "Mar Territorial":
    layer: marTerritorial
,
  water:
    icon: iconsURL + "water.png"
    name: null
    selected: true
  factory:
    name: null
    icon: iconsURL + "factory.png"
).addTo(H5.Map.base)
# }}}

vectorLayer = new L.VectorLayer.Layer (
  autoUpdate: true
  map: document.getElementById("map")
)
