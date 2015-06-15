'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:pdf
 * @description
 * # pdf
 */
angular.module('estatisticasApp')
  .directive('pdf', function () {
    return {
      templateUrl: 'views/acidente.html',
      restrict: 'E',
      controller: function($scope){
        console.log('isOn');
      }

    };
  });
