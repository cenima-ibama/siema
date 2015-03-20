'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:line
 * @description
 * # line
 */
angular.module('estatisticasApp')
  .directive('line', function () {
    return {
      template: '<canvas class="chart-line" data="lineData" labels="lineLabels" legend="true" series="lineSeries"></canvas> ',
      restrict: 'E',
      link: function postLink($scope, element, attrs) {

		$scope.lineLabels = ["January", "February", "March", "April", "May", "June", "July"];
		$scope.lineSeries = ['Series A', 'Series B'];
		$scope.lineData = [
			[65, 59, 80, 81, 56, 55, 40],
			[28, 48, 40, 19, 86, 27, 90]
		];

      }
    };
  });
