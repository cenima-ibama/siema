'use strict';

/**
 * @ngdoc directive
 * @name estatisticasApp.directive:calendar
 * @description
 * # calendar
 */
angular.module('estatisticasApp')
  .directive('calendar', function () {
    return {
      require: 'ngModel',
      restrict: 'EA',
      link: function (scope, el, attr, ngModel) {
        $(el).datepicker({
          format: 'dd/mm/yyyy',
          autoclose: true,
          language: "pt-BR"
          // onSelect: function (dateText) {
          //   scope.$apply(function () {
          //     ngModel.$setViewValue(dateText);
          //   });
          // }
        });
      }
    };
  });
