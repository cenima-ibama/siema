/*
*function onMapClick(e) {
*  var params = {
*    REQUEST: "GetFeatureInfo",
*    EXCEPTIONS: "application/vnd.ogc.se_xml",
*    BBOX: map.getBounds().toBBoxString(),
*    SERVICE: alertaLayer.wmsParams.service,
*    INFO_FORMAT: 'text/html',
*    QUERY_LAYERS: alertaLayer.wmsParams.layers,
*    FEATURE_COUNT: 5,
*    Layers: alertaLayer.wmsParams.layers,
*    WIDTH: map.getSize().x,
*    HEIGHT: map.getSize().y,
*    format: alertaLayer.wmsParams.format,
*    styles: alertaLayer.wmsParams.styles,
*    srs: 'EPSG:4618',
*    version: alertaLayer.wmsParams.version,
*    x: map.layerPointToContainerPoint(e.layerPoint).x,
*    y: map.layerPointToContainerPoint(e.layerPoint).y
*  };
*  $.ajax({
*    type: "GET",
*    url: alertaLayer._url,
*    data: params,
*    dataType: "html",
*    success: function(data) {
*      if (data.indexOf("<table") != -1) {
*        console.log(data);
*        popup.setContent(data);
*        popup.setLatLng(e.latlng);
*        map.openPopup(popup);
*      }
*    }
*  });
*}
*/

//var popup = new L.Popup({
//maxWidth: 400
//});

//// call data from a specific data
//function loadGeoJson(data) {
//console.log(data);
//if (data) {
//$.each(data.features,function(i, item){
//var popupContent = "<p>"+item.properties.objectid+"</p>";
//popup.setContent(popupContent);
//popup.setLatLng(popupPosition);
//map.openPopup(popup);
//});
//}
//}

//// capture mouse clicks
//map.addEventListener('click', onMapClick);
//// store the value of the position clicked
//var popupPosition;

//function onMapClick(e) {
//popupPosition=e.latlng;
//console.log(map.getBounds().toBBoxString());
//console.log(alertaLayer.wmsParams.srs);
//var filter = "http://10.1.8.29:8080/geoserver/wfs?service=wfs&version=2.0.0&request=GetFeature&typeName=cemam:alerta&maxFeatures=1&filter=%3CPropertyIsEqualTo%3E%3CPropertyName%3Eestado%3C/PropertyName%3E%3CLiteral%3EAM%3C/Literal%3E%3C/PropertyIsEqualTo%3E";
//var geoJsonUrl = "http://10.1.8.29:8080/geoserver/wfs?service=wfs&version=2.0.0&request=GetFeature&typeName=cemam:alerta&maxFeatures=1&outputFormat=json&format_options=callback:loadGeoJson";
//$.ajax({
//url: geoJsonUrl,
//dataType: 'jsonp',
//});
//}


