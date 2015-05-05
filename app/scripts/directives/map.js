'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:map
 * @description
 * # map
 */
angular.module('estatisticasApp')
  .directive('map', function () {
    return {
        template: '<div id="map" class="map"></div>',
        restrict: 'AE',
        controller: function($scope, $cookies, $rootScope){


            $scope.mapa.acidente = null;


    		$scope.map = L.map('map').setView([-12, -53], 3);
    		var osm = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    		  attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    		}).addTo($scope.map);

    		var drawnItems = new L.FeatureGroup();
    		$scope.map.addLayer(drawnItems);

    		// Initialise the draw control and pass it the FeatureGroup of editable layers
    		var drawControl = new L.Control.Draw({
    		    edit: {
    		        featureGroup: drawnItems
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
                var d = parseFloat(dms.split("°")[0]);
                var m = parseFloat(dms.split("°")[1].split("'")[0]);
                var s = parseFloat(dms.split("°")[1].split("'")[1].split("\"")[0]);
                var dd = Math.sign(d) * (Math.abs(d) + (m / 60.0) + (s / 3600.0));
                return dd;
            };


            $scope.mapa.mudarMarcador = function(){
                if($scope._timeout){ //if there is already a timeout in process cancel it
                  $timeout.cancel($scope._timeout);
                }
                $scope._timeout = $timeout(function(){
                    var lat =  $scope.mapa.converterDD($scope.localizacao.lat);
                    var lng =  $scope.mapa.converterDD($scope.localizacao.lng);

                    $scope.map.removeLayer($scope.mapa.acidente);
                    $scope.mapa.acidente = new L.Marker([$scope.localizacao.lat, $scope.localizacao.lng]);
                    $scope.map.addLayer($scope.mapa.acidente);
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
