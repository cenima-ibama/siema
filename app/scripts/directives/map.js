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
      controller: function($scope, $cookies){
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

		  var shape = layer.toGeoJSON()
		  var shape_for_db = JSON.stringify(shape);
		});


      },
    };
  });
