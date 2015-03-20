'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:mensal
 * @description
 * # mensal
 */
angular.module('estatisticasApp')
  .directive('mensal', function () {
    return {
      template: '<canvas class="chart-bar" data="barData" labels="barLabel" legend="true" series="barSeries"></canvas>',
      restrict: 'E',
      link: function postLink($scope, element, attrs) {
      	// RestApi.query({},
      	// 	function success(data, status){

      	// 	}
      	// );

		Chart.defaults.global.colours = ["#00B2EE", "#F7464A", "#46BFBD", "#7B68EE", "#FDB45C", "#949FB1", "#8B5742"];

		$scope.barLabel = ['2006', '2007', '2008', '2009', '2010', '2011', '2012'];
		$scope.barSeries = ['Series A', 'Series B'];

		$scope.barData = [
			[65, 59, 80, 81, 56, 55, 40],
			[28, 48, 40, 19, 86, 27, 90]
		];
      			

      }
    };
  });
