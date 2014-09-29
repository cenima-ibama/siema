// Generated by CoffeeScript 1.7.1
(function() {
  var acidentes, banhado, bingKey, bingMini, bingaerial, binghybrid, bingroad, biomaIBGE, blocoExploratorio, controlswitch, costao, duna, eixoDutoviario, eixoFerroviario, eixoRodoviario, estacaoFerroviaria, estuario, geoserverUrl, hidrografia, iconsURL, lagunas, legados, mangues, marTerritorial, marisma, openmapquest, openmapquestSub, openmapquestUrl, openstreet, openstreetUrl, orientationEvent, ponteTunel, portoTerminal, recifes, refinaria, restURL, restinga, supportsOrientationChange, terrasIndigenas, ucFederacao, ucIntegral, ucSustentavel, updateLayerByTimer;

  H5.Map = {
    base: null,
    layer: {},
    layerList: null
  };

  H5.Leaflet = {};

  $('#map').width("100%");

  $('#map').height($(window).height() - $('#navbar').height() - $('#barra-brasil').height());

  supportsOrientationChange = "onorientationchange" in window;

  orientationEvent = (supportsOrientationChange ? "orientationchange" : "resize");

  window.addEventListener(orientationEvent, (function() {
    $('#map').width($(window).width());
    return $('#map').height($(window).height() - $('#navbar').height());
  }), false);

  bingKey = "AsyRHq25Hv8jQbrAIVSeZEifWbP6s1nq1RQfDeUf0ycdHogebEL7W2dxgFmPJc9h";

  bingaerial = new L.BingLayer(bingKey, {
    type: "Aerial",
    attribution: "MarcosLindo.com"
  });

  bingroad = new L.BingLayer(bingKey, {
    type: "Road",
    attribution: ""
  });

  binghybrid = new L.BingLayer(bingKey, {
    type: "AerialWithLabels",
    attribution: ""
  });

  bingMini = new L.BingLayer(bingKey, {
    type: "AerialWithLabels",
    attribution: "",
    minZoom: 1,
    maxZoom: 11
  });

  openstreetUrl = "http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";

  openstreet = new L.TileLayer(openstreetUrl, {
    maxZoom: 18,
    attribution: ""
  });

  openmapquestUrl = "http://{s}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png";

  openmapquestSub = ['otile1', 'otile2', 'otile3', 'otile4'];

  openmapquest = new L.TileLayer(openmapquestUrl, {
    maxZoom: 18,
    subdomains: openmapquestSub
  });

  H5.Map.base = new L.Map("map", {
    center: new L.LatLng(-10.0, -58.0),
    zoom: 4,
    layers: [openstreet]
  });

  H5.Map.minimap = new L.Control.MiniMap(bingMini, {
    toggleDisplay: true,
    zoomLevelOffset: -4,
    autoToggleDisplay: false
  }).addTo(H5.Map.base);

  H5.Map.base.attributionControl.setPrefix("Desenvolvido com: &copy; Leaflet | Hexgis Hash5. map data: &copy; OpenStreetMap</a> contributors | Image courtesy of, Earthstar Geographics  SIO, | &copy; 2014 Microsoft Corporation, &copy; 2014 Nokia");

  geoserverUrl = "http://siscom.ibama.gov.br/geo-srv/cemam/wms";

  terrasIndigenas = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:terra_indigena",
    format: "image/png",
    transparent: true
  });

  ucSustentavel = new L.TileLayer.WMS("http://siscom.ibama.gov.br/geoserver/csr/wms", {
    layers: "csr:unidade_uso_sustentavel",
    format: "image/png",
    transparent: true
  });

  ucIntegral = new L.TileLayer.WMS("http://siscom.ibama.gov.br/geoserver/csr/wms", {
    layers: "csr:unidade_protecao_integral",
    format: "image/png",
    transparent: true
  });

  ucFederacao = new L.TileLayer.WMS("http://siscom.ibama.gov.br/geoserver/csr/wms", {
    layers: "csr:unidade_federacao",
    format: "image/png",
    transparent: true
  });

  blocoExploratorio = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:bloco_exploratorio",
    format: "image/png",
    transparent: true
  });

  biomaIBGE = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:bioma_ibge",
    format: "image/png",
    transparent: true
  });

  portoTerminal = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:porto_terminal",
    format: "image/png",
    transparent: true
  });

  marisma = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:marisma",
    format: "image/png",
    transparent: true
  });

  restinga = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:restinga",
    format: "image/png",
    transparent: true
  });

  recifes = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:recife_total",
    format: "image/png",
    transparent: true
  });

  mangues = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:mangue_siema",
    format: "image/png",
    transparent: true
  });

  lagunas = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:laguna",
    format: "image/png",
    transparent: true
  });

  estuario = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:estuario",
    format: "image/png",
    transparent: true
  });

  duna = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:duna",
    format: "image/png",
    transparent: true
  });

  costao = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:costao",
    format: "image/png",
    transparent: true
  });

  banhado = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:banhado",
    format: "image/png",
    transparent: true
  });

  hidrografia = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:Hidrografia",
    format: "image/png",
    transparent: true
  });

  eixoDutoviario = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:eixo_dutoviario",
    format: "image/png",
    transparent: true
  });

  eixoFerroviario = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:eixo_ferroviario",
    format: "image/png",
    transparent: true
  });

  eixoRodoviario = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:eixo_rodoviario",
    format: "image/png",
    transparent: true
  });

  estacaoFerroviaria = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:estacao_ferroviaria",
    format: "image/png",
    transparent: true
  });

  refinaria = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:refinaria",
    format: "image/png",
    transparent: true
  });

  ponteTunel = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:ponte_tunel",
    format: "image/png",
    transparent: true
  });

  marTerritorial = new L.TileLayer.WMS(geoserverUrl, {
    layers: "cemam:mar_territorial",
    format: "image/png",
    transparent: true
  });

  restURL = "http://" + document.domain + "/siema/rest";

  acidentes = new L.VectorLayer.Postgis({
    url: restURL,
    geotable: "vw_ocorrencia_mapa",
    fields: "id_ocorrencia, municipio, estado, data_acidente, origem_acidente, tipo_eventos, produtos, legado",
    srid: 4326,
    geomFieldName: "shape",
    showAll: true,
    cluster: true,
    popupTemplate: function(properties) {
      var html, text;
      html = '<div class="iw-content"><h4>' + properties.id_ocorrencia + '</h4>';
      html += '<table class="condensed-table bordered-table zebra-striped"><tbody>';
      text = "";
      if (properties.municipio) {
        text += properties.municipio;
      }
      text += " - ";
      if (properties.estado) {
        text += properties.estado;
      }
      if (!properties.municipio && !properties.estado) {
        text = "Sem informação";
      }
      html += '<tr><th>Município - Estado: </th><td style="max-width:200px;">' + text + '</td></tr>';
      text = "";
      if (properties.data_acidente) {
        text += properties.data_acidente;
      }
      if (!properties.data_acidente) {
        text = "Sem informação";
      }
      html += '<tr><th>Data: </th><td style="max-width:200px;">' + text + '</td></tr>';
      text = "";
      if (properties.origem_acidente !== "{}") {
        text += properties.origem_acidente.replace(/[{}]/g, "").replace(/,/g, ", ");
      }
      if (properties.origem_acidente === "{}") {
        text = "Sem informação";
      }
      html += '<tr><th>Origem do Acidente: </th><td style="max-width:200px;">' + text + '</td></tr>';
      text = "";
      if (properties.tipo_eventos !== "{}") {
        text += properties.tipo_eventos.replace(/[{}]/g, "").replace(/,/g, ", ");
      }
      if (properties.tipo_eventos === "{}") {
        text = "Sem informação";
      }
      html += '<tr><th>Tipo de Evento: </th><td style="max-width:200px;">' + text + '</td></tr>';
      text = "";
      if (properties.produtos !== "{}") {
        text += properties.produtos.replace(/[{}]/g, "").replace(/,/g, ", ");
      }
      if (properties.produtos === "{}") {
        text = "Sem informação";
      }
      html += '<tr><th>Produtos Envolvidos: </th><td style="max-width:200px;">' + text + '</td></tr>';
      html += '</tbody></table></div>';
      return html;
    },
    focus: false,
    where: 'legado IS FALSE',
    symbology: {
      type: "single",
      vectorStyle: {
        circleMarker: true,
        radius: 6,
        fillColor: "#ff0000",
        fillOpacity: 0.8,
        weight: 4.0,
        color: "#ff0000",
        opacity: 0.8
      }
    }
  });

  acidentes.setMap(H5.Map.base);

  new L.control.scale().addTo(H5.Map.base);

  new L.control.fullscreen({
    position: 'topleft',
    title: 'Fullscreen'
  }).addTo(H5.Map.base);

  new L.Control.GeoSearch({
    provider: new L.GeoSearch.Provider.Google,
    searchLabel: "Endereço, Estado - UF",
    notFoundMessage: "Endereço não encontrado.",
    showMarker: false
  }).addTo(H5.Map.base);

  new L.control.locate({
    position: "topleft",
    drawCircle: true,
    follow: false,
    stopFollowingOnDrag: false,
    circleStyle: {},
    markerStyle: {},
    followCircleStyle: {},
    followMarkerStyle: {},
    metric: true,
    onLocationError: function(err) {
      return alert(err.message);
    },
    onLocationOutsideMapBounds: function(context) {
      return alert(context.options.strings.outsideMapBoundsMsg);
    },
    setView: true,
    strings: {
      title: "Localizar minha posição",
      popup: "Você está a {distance} {unit} deste lugar",
      outsideMapBoundsMsg: "Você está em um outra dimensão! o.O"
    },
    locateOptions: {}
  }).addTo(H5.Map.base);

  legados = new L.VectorLayer.Postgis({
    url: restURL,
    map: H5.Map.base,
    geotable: "vw_ocorrencia_mapa",
    fields: "id_ocorrencia, municipio, estado, data_acidente, origem_acidente, tipo_eventos, produtos, legado",
    srid: 4326,
    geomFieldName: "shape",
    showAll: true,
    cluster: true,
    popupTemplate: function(properties) {
      var html, text;
      html = '<div class="iw-content"><h4>' + properties.id_ocorrencia + '</h4>';
      html += '<table class="condensed-table bordered-table zebra-striped"><tbody>';
      text = "";
      if (properties.municipio) {
        text += properties.municipio;
      }
      text += " - ";
      if (properties.estado) {
        text += properties.estado;
      }
      if (!properties.municipio && !properties.estado) {
        text = "Sem informação";
      }
      html += '<tr><th>Município - Estado: </th><td style="max-width:200px;">' + text + '</td></tr>';
      text = "";
      if (properties.data_acidente) {
        text += properties.data_acidente;
      }
      if (!properties.data_acidente) {
        text = "Sem informação";
      }
      html += '<tr><th>Data: </th><td style="max-width:200px;">' + text + '</td></tr>';
      text = "";
      if (properties.origem_acidente !== "{}") {
        text += properties.origem_acidente.replace(/[{}]/g, "").replace(/,/g, ", ");
      }
      if (properties.origem_acidente === "{}") {
        text = "Sem informação";
      }
      html += '<tr><th>Origem do Acidente: </th><td style="max-width:200px;">' + text + '</td></tr>';
      text = "";
      if (properties.tipo_eventos !== "{}") {
        text += properties.tipo_eventos.replace(/[{}]/g, "").replace(/,/g, ", ");
      }
      if (properties.tipo_eventos === "{}") {
        text = "Sem informação";
      }
      html += '<tr><th>Tipo de Evento: </th><td style="max-width:200px;">' + text + '</td></tr>';
      text = "";
      if (properties.produtos !== "{}") {
        text += properties.produtos.replace(/[{}]/g, "").replace(/,/g, ", ");
      }
      if (properties.produtos === "{}") {
        text = "Sem informação";
      }
      html += '<tr><th>Produtos Envolvidos: </th><td style="max-width:200px;">' + text + '</td></tr>';
      html += '</tbody></table></div>';
      return html;
    },
    singlePopup: true,
    focus: false,
    where: 'legado IS TRUE',
    scaleRange: [1, 13],
    symbology: {
      type: "single",
      vectorStyle: {
        circleMarker: true,
        radius: 6,
        fillColor: "#ff0000",
        fillOpacity: 0.8,
        weight: 4.0,
        color: "#ff0000",
        opacity: 0.8
      }
    }
  });

  legados.setMap(H5.Map.base);

  iconsURL = "http://" + document.domain + "/siema/assets/img/icons/";

  controlswitch = new L.control["switch"]({
    "OSM": {
      layer: openstreet
    },
    "Bing Aerial": {
      layer: bingaerial
    },
    "Bing Road": {
      layer: bingroad
    },
    "Bing Hybrid": {
      layer: binghybrid
    }
  }, {
    "Recifes": {
      layer: recifes,
      tab: "water"
    },
    "Mangues": {
      layer: mangues,
      tab: "water"
    },
    "Lagunas": {
      layer: lagunas,
      tab: "water"
    },
    "Estuário": {
      layer: estuario,
      tab: "water"
    },
    "Dunas": {
      layer: duna,
      tab: "water"
    },
    "Costão": {
      layer: costao,
      tab: "water"
    },
    "Banhado": {
      layer: banhado,
      tab: "water"
    },
    "Marisma": {
      layer: marisma,
      tab: "water"
    },
    "Restinga": {
      layer: restinga,
      tab: "water"
    },
    "Bloco Exploratório": {
      layer: blocoExploratorio,
      tab: "factory"
    },
    "Portos e Terminais": {
      layer: portoTerminal,
      tab: "factory"
    },
    "Eixos Dutoviários": {
      layer: eixoDutoviario,
      tab: "factory"
    },
    "Eixos Ferroviários": {
      layer: eixoFerroviario,
      tab: "factory"
    },
    "Eixos Rodoviários": {
      layer: eixoRodoviario,
      tab: "factory"
    },
    "Estações Ferroviárias": {
      layer: estacaoFerroviaria,
      tab: "factory"
    },
    "Refinarias": {
      layer: refinaria,
      tab: "factory"
    },
    "Pontes e Túneis": {
      layer: ponteTunel,
      tab: "factory"
    },
    "Terras Indígenas": {
      layer: terrasIndigenas
    },
    "UC Uso Sustentável": {
      layer: ucSustentavel
    },
    "UC Proteção Integral": {
      layer: ucIntegral
    },
    "UC Federação": {
      layer: ucFederacao
    },
    "Bioma IBGE": {
      layer: biomaIBGE
    },
    "Hidrografia": {
      layer: hidrografia
    },
    "Mar Territorial": {
      layer: marTerritorial
    },
    "Dados Legados": {
      layer: legados.layer,
      vectorLayer: {
        layer: legados
      }
    }
  }, {
    water: {
      icon: iconsURL + "water.png",
      name: null,
      selected: true
    },
    factory: {
      icon: iconsURL + "factory.png",
      name: null
    }
  }).addTo(H5.Map.base);

  $(document).ready(function() {
    return legados.hideLayer();
  });

  updateLayerByTimer = function() {
    if (acidentes !== null) {
      return acidentes.redraw();
    }
  };

}).call(this);
