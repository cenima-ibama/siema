'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:pie
 * @description
 * # pie
 */

angular.module('estatisticasApp')
  .directive('pie', function () {
    return {
      template: '<canvas class="chart-pie" data="pieData" labels="pieLabels" legend="true"></canvas> ',
      restrict: 'E',
      link: function postLink($scope, element, attrs) {
		  Chart.defaults.global.colours = ['#00B2EE', '#F7464A', '#46BFBD', '#7B68EE', '#FDB45C', '#949FB1', '#8B5742'];

		  $scope.pieLabels = ['Sales', 'In-Store', 'Mail-Order', 'Down', 'test'];
		  $scope.pieData = [300, 500, 100, 200, 150];
      }
    };
  });
