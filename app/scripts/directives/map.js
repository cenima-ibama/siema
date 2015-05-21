'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:map
 * @description
 * # map
 */
angular.module('estatisticasApp')
  .directive('map', function (RestApi) {
    return {
        template: '<div id="map" class="map"></div>',
        restrict: 'AE',
        controller: function($scope, $cookies, $rootScope, $timeout){


            $scope.mapa.acidente = null;

            L.Icon.Default.imagePath = 'images';


    		$scope.map = L.map('map').setView([-12, -53], 3);
    		var osm = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    		  attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    		}).addTo($scope.map);

    		var drawnItems = new L.FeatureGroup();
    		$scope.map.addLayer(drawnItems);

    		// Initialise the draw control and pass it the FeatureGroup of editable layers
    		var drawControl = new L.Control.Draw({
    		    // edit: {
    		    //     featureGroup: drawnItems
    		    // }

                edit: {
                    featureGroup: drawnItems
                },
                draw: {
                    polygon:false,
                    polyline: false,
                    rectangle: false,
                    circle: false,
                    marker: true
                }
    		});
    		$scope.map.addControl(drawControl);


    		$scope.map.on('draw:created', function (e) {
                var type = e.layerType;
                var layer = e.layer;

                var shape = layer.toGeoJSON();
                if ($scope.map.hasLayer($scope.mapa.acidente)) {
                    $scope.map.removeLayer($scope.mapa.acidente);
                }

                $scope.mapa.acidente = new L.Marker(shape.geometry.coordinates.reverse());
                $scope.map.addLayer($scope.mapa.acidente);

                $scope.localizacao.lat = shape.geometry.coordinates[0];
                $scope.localizacao.lng = shape.geometry.coordinates[1];

                // var data = {}
                // data.lat = $scope.mapa.converterDMS(shape.geometry.coordinates[0]);
                // data.lng = $scope.mapa.converterDMS(shape.geometry.coordinates[1]);


                // $scope.$broadcast('mudar_latlng', data);

                $scope.localizacao.lat = $scope.mapa.converterDMS(shape.geometry.coordinates[0]);
                $scope.localizacao.lng = $scope.mapa.converterDMS(shape.geometry.coordinates[1]);

                // console.log(shape.geometry.coordinates[0]);
                // console.log($scope.mapa.converterDD($scope.localizacao.lat));

                $scope.$apply();
    		});


            $scope.mapa.converterDMS = function(dd){
                var deg = dd | 0; // truncate dd to get degrees
                var frac = Math.abs(dd - deg); // get fractional part
                var min = (frac * 60) | 0; // multiply fraction by 60 and truncate
                var sec = frac * 3600 - min * 60;
                return deg + "°" + min + "'" + sec.toFixed(3);
            };


            $scope.mapa.converterDD = function(dms){
                var d = NaN;
                var m = NaN;
                var s = NaN;

                if (dms) {
                    d = dms.split("°")[0];
                    m = dms.split("°")[1] ? dms.split("°")[1].split("'")[0] : 0;
                    s = dms.split("°")[1] && dms.split("°")[1].split("'")[1] ?  dms.split("°")[1].split("'")[1].split("\"")[0]  : 0;
                }

                var dd = NaN;

                if ($.isNumeric(d) && $.isNumeric(m) && $.isNumeric(s)) {
                    dd = Math.sign(d) * (Math.abs(d) + (m / 60.0) + (s / 3600.0));
                }

                return dd;
            };


            $scope.mapa.validarMarcador = function(marcador) {
                RestApi.query({query: 'verificar_marcador', 'lat': marcador.getLatLng().lat, 'lng': marcador.getLatLng().lng},
                    function success(data, status){

                        angular.forEach(data, function(value, key){
                            if (value.intersects == 't') {
                                ret = 1;
                            } else {
                                ret = 0;
                            }
                        });
                    }
                );

                return ret;
            };


            $scope.mapa.mudarMarcador = function(){
                if($scope._timeout){ //if there is already a timeout in process cancel it
                  $timeout.cancel($scope._timeout);
                }
                $scope._timeout = $timeout(function(){
                    var lat =  $scope.mapa.converterDD($scope.localizacao.lat);
                    var lng =  $scope.mapa.converterDD($scope.localizacao.lng);

                    if (lat && lng) {
                        var novoMarcador = new L.Marker([lat, lng]);

                        if ($scope.map.hasLayer($scope.mapa.acidente)) {
                            $scope.map.removeLayer($scope.mapa.acidente);
                        }

                        // if ($scope.mapa.validarMarcador(novoMarcador)) {
                            $scope.mapa.acidente = novoMarcador;
                            $scope.map.addLayer($scope.mapa.acidente);
                        // } else {
                        //     alert("CHUPA SOCIEDADE!");
                        // }
                    }

                    // else if (!lat) {
                    // } else if(!lng) {
                    // }

                    $scope._timeout = null;
                },1000);
            };


            $scope.$on('carregar_mapa', function(event, data){
                $scope.mapa.acidente = new L.Marker(JSON.parse(data[0].coordinate_json).coordinates.reverse());
                $scope.map.addLayer($scope.mapa.acidente);
            });
        },
    };
});
